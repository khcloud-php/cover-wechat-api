<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FileEnum;
use App\Enums\Database\MessageEnum;
use App\Enums\WorkerManEnum;
use App\Exceptions\BusinessException;
use App\Models\File;
use App\Models\Friend;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\DB;

class MessageService extends BaseService
{

    /**
     * @throws BusinessException
     */
    public function list(array $params): array
    {
        $fromUser = (int)$params['user']->id;
        $toUser = (int)$params['to_user'];
        $me = [
            'id' => $fromUser,
            'nickname' => $params['user']->nickname,
            'avatar' => $params['user']->avatar,
            'wechat' => $params['user']->wechat,
        ];

        $list = [];
        //聊天记录
        $messages = Message::query()
            ->whereRaw("((from_user = {$fromUser} AND to_user = {$toUser} AND is_group = 0) OR (from_user = {$toUser} AND to_user = {$fromUser} AND is_group = 0) OR (to_user = {$toUser} AND is_group=1))")
            ->whereRaw("(FIND_IN_SET('{$fromUser}', deleted_users) = '')")
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

        $fileIds = array_column($messages, 'file_id');
        $fileIds = array_filter($fileIds);
        $files = [];
        if ($fileIds) {
            $files = File::query()->whereIn('id', $fileIds)->get()->toArray();
            $files = array_column($files, null, 'id');
        }

        foreach ($messages as $message) {
            $item = [
                'id' => $message['id'],
                'from_user' => $message['from_user'],
                'to_user' => $toUser,
                'content' => $message['content'],
                'type' => $message['type'],
                'is_undo' => $message['is_undo'],
                'is_tips' => $message['is_tips'],
                'created_at' => $message['created_at'],
                'file' => [],
                'extends' => [],
                'pid' => 0,
                'pcontent' => '',
                'at_users' => []
            ];
            if (in_array($message['type'], FileEnum::TYPE)) {
                $fileId = $message['file_id'];
                $file = $files[$fileId] ?? [];
                if ($file) {
                    $item['content'] = env('STATIC_FILE_URL') . '/' . $file['path'];
                    $item['extends'] = [
                        'thumbnail' => $file['thumbnail_path'] ? env('STATIC_FILE_URL') . '/' . $file['thumbnail_path'] : '',
                        'format' => $file['format'],
                        'width' => $file['width'],
                        'height' => $file['height'],
                        'duration' => $file['duration']
                    ];
                    $item['file'] = [
                        'id' => $fileId,
                        'name' => $file['name'],
                        'type' => $file['type'],
                        'size' => $file['size']
                    ];
                }

            }
            !empty($message['at_users']) && $item['at_users'] = explode(',', $message['at_users']);
            !empty($parentMessages[$message['pid']]) && $item['pcontent'] = $parentMessages[$message['pid']];
            $item['right'] = $message['from_user'] == $fromUser;
            $list[] = $item;
        }
        unset($message, $messages);

        if ($params['is_group'] == MessageEnum::GROUP) {
            //群聊
            $userIds = array_column($list, 'from_user');
            $userIds = array_unique($userIds);
            $userList = User::query()->whereIn('id', $userIds)->get(['id', 'nickname', 'avatar', 'wechat'])->toArray();

            $groupUserList = GroupUser::query()
                ->where('group_id', $toUser)
                ->whereIn('user_id', $userIds)
                ->get(['nickname', 'user_id'])->toArray();
            $groupUserList = array_column($groupUserList, 'nickname', 'user_id');
            foreach ($userList as &$user) {
                if (!empty($groupUserList[$item['id']])) {
                    $user['nickname'] = $groupUserList[$user['id']];
                }
            }
            unset($user, $groupUserList, $userIds);

            $userList = array_column($userList, null, 'id');

            foreach ($list as &$item) {
                //撤回处理
                if ($item['is_undo']) {
                    $who = $item['from_user'] == $fromUser ? "你" : $userList[$fromUser]['nickname'];
                    $item['content'] = $who . "撤回了一条消息";
                }
                $item['from'] = $item['from_user'] == $fromUser ? $me : $userList[$item['from_user']];
            }
            unset($item);
        } else {
            //私聊
            $user = User::query()->where('id', $toUser)->first(['id', 'nickname', 'avatar', 'wechat']);
            $friend = Friend::query()->where('owner', $fromUser)
                ->where('friend', $toUser)
                ->first(['id', 'nickname']);
            $user->nickname = $friend->nickname ?: $user->nickname;
            foreach ($list as &$item) {
                //撤回处理
                if ($item['is_undo']) {
                    $who = $item['from_user'] == $fromUser ? "你" : $user->nickname;
                    $item['content'] = $who . "撤回了一条消息";
                }
                $item['from'] = $item['from_user'] == $fromUser ? $me : $user->toArray();
            }
            unset($item);
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
        $time = time();
        $sendData = [
            'who' => WorkerManEnum::WHO_MESSAGE,
            'action' => WorkerManEnum::ACTION_SEND,
            'data' => [
                'from' => $params['from'],
                'from_user' => $fromUser,
                'to_user' => $toUser,
                'content' => $params['content'],
                'type' => $params['type'],
                'file' => [],
                'extends' => [],
                'pid' => 0,
                'is_tips' => 0,
                'is_undo' => 0,
                'pcontent' => '',
                'at_users' => [],
                'is_group' => $params['is_group'],
                'right' => false,
                'time' => $time,
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
            'created_at' => $time
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
                $sendData['data']['extends'] = [
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
                $data['extends'] = json_encode($sendData['data']['extends']);
                $data['content'] = FileEnum::CONTENT[$file->type] ?? '[文件信息]';
                $sendData['content'] = env('STATIC_FILE_URL') . '/' . $file->path;
            }

        }

        DB::beginTransaction();
        try {
            if ($params['is_group'] == MessageEnum::GROUP) {
                Group::query()->where('id', $toUser)
                    ->update([
                        'send_user' => $fromUser,
                        'content' => $data['content'],
                        'time' => $time
                    ]);
                GroupUser::query()
                    ->where('group_id', $toUser)
                    ->update([
                        'display' => 1
                    ]);
                GroupUser::query()
                    ->where('group_id', $toUser)
                    ->where('user_id', '<>', $fromUser)
                    ->increment('unread');
            } else {
                Friend::query()
                    ->whereRaw("((owner = $fromUser AND friend = $toUser) OR (friend = $fromUser AND owner = $toUser))")
                    ->update([
                        'display' => 1,
                        'content' => $data['content'],
                        'time' => $time
                    ]);
                Friend::query()
                    ->whereRaw("(friend = $fromUser AND owner = $toUser)")
                    ->increment('unread');
            }

            $sendData['data']['id'] = Message::query()->insertGetId($data);

            DB::commit();

            //at用户处理
            if (!empty($params['at_users'])) {
                $sendAtData = [
                    'who' => WorkerManEnum::WHO_MESSAGE,
                    'action' => WorkerManEnum::ACTION_AT,
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
                    'who' => WorkerManEnum::WHO_MESSAGE,
                    'action' => WorkerManEnum::ACTION_QUOTE,
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
        $isGroup = $params['is_group'];
        if ($isGroup == MessageEnum::GROUP) {
            return GroupUser::query()->where('user_id', $fromUser)->where('group_id', $toUser)->update(['unread' => 0]);
        }
        return Friend::query()->where('owner', $fromUser)->where('friend', $toUser)->update(['unread' => 0]);
    }

    public function undo(array $params): bool
    {
        $id = $params['id'];
        DB::beginTransaction();
        try {
            $message = Message::query()->find($id);
            if (!$message) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
            $message->is_undo = 1;
            $message->is_tips = 1;
            $message->updated_at = time();
            $message->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
        return true;
    }

    public function unread(int $userId): array
    {
        $group = GroupUser::query()
            ->where('user_id', $userId)
            ->where('display', 1)
            ->where('unread', '>', 0)
            ->sum('unread');
        $private = Friend::query()
            ->where('owner', $userId)
            ->where('display', 1)
            ->where('unread', '>', 0)
            ->sum('unread');
        $apply = Friend::query()
            ->where('friend', $userId)
            ->where('is_read', 0)
            ->count();
        return [
            'chat' => $group + $private,
            'apply' => $apply,
            'friend' => $apply,
            'discover' => 0
        ];
    }
}
