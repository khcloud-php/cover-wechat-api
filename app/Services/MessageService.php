<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FileEnum;
use App\Enums\Database\MessageEnum;
use App\Exceptions\BusinessException;
use App\Models\File;
use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\DB;

class MessageService extends BaseService
{

    public function list(array $params): array
    {
        $fromUser = $params['user']->id;
        $me = [
            'id' => $fromUser,
            'nickname' => $params['user']->nickname,
            'avatar' => $params['user']->avatar
        ];
        $toUser = $params['to_user'];
        if (!in_array($params['is_group'], MessageEnum::IS_GROUP)) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }

        $list = [];
        $messages = Message::query()->whereRaw("((from_user = {$fromUser} AND to_user = {$toUser}) OR (from_user = {$toUser} AND to_user = {$fromUser}))")
            ->orderBy('created_at')
            ->get()
            ->toArray();
        if (!$messages) return $list;

        //引用消息
        $parentIds = array_column($messages, 'pid');
        $parentIds = array_filter($parentIds);
        $parentMessages = [];
        if ($parentIds) {
            $parentMessages = Message::query()->whereIn('id', $parentIds)->get(['id', 'content'])->toArray();
            $parentMessages = array_column($parentMessages, 'content', 'id');
        }

        if ($params['is_group'] == MessageEnum::GROUP) {
            //群聊
            $userIds = array_column($messages, 'from_user');
            $userIds = array_unique($userIds);
        } else {
            //私聊
            $user = User::query()->where('id', $toUser)->first(['id', 'nickname', 'avatar']);
            $friend = Friend::query()->where('owner', $fromUser)
                ->where('friend', $toUser)
                ->first(['id', 'nickname']);
            $user->nickname = $friend->nickname ?? $user->nickname;
            foreach ($messages as $message) {
                $item = [
                    'id' => $message['id'],
                    'from' => $message['from_user'] == $fromUser ? $me : $user->toArray(),
                    'to_user' => $toUser,
                    'content' => $message['content'],
                    'type' => $message['type'],
                    'created_at' => $message['created_at'],
                    'file' => [],
                    'extends' => [],
                    'pid' => 0,
                    'pcontent' => '',
                    'at_users' => []
                ];
                if (in_array($message['type'], FileEnum::TYPE)) {
                    $fileId = $message['file_id'];
                    $file = File::query()->find($fileId);
                    if ($file) {
                        $item['extends'] = [
                            'path' => $file->path,
                            'format' => $file->format,
                            'width' => $file->width,
                            'height' => $file->height,
                            'duration' => $file->duration
                        ];
                        $item['file'] = [
                            'id' => $fileId,
                            'name' => $file->name,
                            'type' => $file->type,
                            'size' => $file->size
                        ];
                    }

                }
                !empty($message['at_users']) && $item['at_users'] = explode(',', $message['at_users']);
                !empty($parentMessages[$message['pid']]) && $item['pcontent'] = $parentMessages[$message['pid']];
                $item['right'] = $message['from_user'] == $fromUser;
                $list[] = $item;
            }

        }
        return $list;
    }

    /**
     * 发送消息
     * @throws BusinessException
     */
    public function send(array $params): array
    {
        $fromUser = $params['user']->id;
        $toUser = $params['to_user'];
        if (!in_array($params['is_group'], MessageEnum::IS_GROUP)) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        $from = [
            'id' => $fromUser,
            'avatar' => $params['user']->avatar,
            'nickname' => $params['friend']->nickname ?? '',
        ];

        $sendData = [
            'who' => 'message',
            'action' => 'send',
            'data' => [
                'from' => $from,
                'to_user' => $toUser,
                'content' => $params['content'],
                'type' => $params['type'],
                'file' => [],
                'extends' => [],
                'pid' => 0,
                'pcontent' => '',
                'at_users' => [],
                'right' => false
            ]
        ];

        $data = [
            'from_user' => $fromUser,
            'to_user' => $toUser,
            'content' => $params['content'],
            'is_group' => $params['is_group'],
            'type' => $params['type'],
            'pid' => $params['pid'] ?? 0,
            'at_users' => $params['at_users'] ?? '',
            'created_at' => time()
        ];

        //文件消息处理
        if (in_array($params['type'], FileEnum::TYPE)) {
            if (empty($params['file_id'])) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
            $fileId = $params['file_id'];
            $file = File::query()->find($fileId);
            if ($file) {
                $data['file_id'] = $file->id;
                $data['file_name'] = $file->name;
                $data['file_type'] = $file->type;
                $data['file_size'] = $file->size;
                $data['extends'] = $sendData['data']['extends'] = [
                    'path' => $file->path,
                    'format' => $file->format,
                    'width' => $file->width,
                    'height' => $file->height,
                    'duration' => $file->duration
                ];
                $sendData['data']['file'] = [
                    'id' => $fileId,
                    'name' => $file->name,
                    'type' => $file->type,
                    'size' => $file->size
                ];
            }

        }

        DB::beginTransaction();
        try {
            if ($params['is_group'] == MessageEnum::GROUP) {
                Message::query()->where('to_user', $toUser)
                    ->where('is_group', MessageEnum::GROUP)
                    ->update([
                        'is_last' => 0
                    ]);
            } else {
                Message::query()->whereRaw("(from_user = {$fromUser} and to_user = {$toUser}) OR (from_user = {$toUser} and to_user = {$fromUser})")
                    ->where('is_group', MessageEnum::PRIVATE)
                    ->update([
                        'is_last' => 0
                    ]);
            }

            $sendData['data']['id'] = Message::query()->insertGetId($data);

            DB::commit();

            //at用户处理
            if (!empty($params['at_users'])) {
                $sendAtData = [
                    'who' => 'message',
                    'action' => 'at',
                    'data' => [
                    ]
                ];
                $sendData['data']['at_users'] = $atUsers = explode(',', $params['at_users']);
                //通知被at的用户
                Gateway::sendToUid($atUsers, json_encode($sendAtData, JSON_UNESCAPED_UNICODE));
            }

            //引用消息处理
            if (!empty($params['pid'])) {
                $message = Message::query()->find($params['pid'], ['content']);
                $sendData['data']['pcontent'] = $message->content;
                $sendQuoteData = [
                    'who' => 'message',
                    'action' => 'quote',
                    'data' => [

                    ]
                ];
                //通知被引用消息的用户
                Gateway::sendToUid($message->from_user, json_encode(
                    $sendQuoteData, JSON_UNESCAPED_UNICODE));
            }

            //发送消息通知
            if ($params['is_group'] == MessageEnum::GROUP) {
                $excludeClientId = Gateway::getClientIdByUid($fromUser);
                Gateway::sendToGroup($toUser, json_encode($sendData, JSON_UNESCAPED_UNICODE), $excludeClientId);
            } else {
                Gateway::sendToUid($toUser, json_encode($sendData, JSON_UNESCAPED_UNICODE));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }

        $sendData['data']['right'] = true;
        $sendData['data']['from']['nickname'] = $params['user']->nickname;
        return $sendData['data'];
    }

    public function read(array $params): int
    {
        $fromUser = $params['user']->id;
        $toUser = $params['to_user'];
        return Message::query()->where('from_user', $toUser)
            ->where('to_user', $fromUser)
            ->update(['is_read' => 1]);
    }
}
