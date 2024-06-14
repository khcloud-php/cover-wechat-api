<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FileEnum;
use App\Enums\Database\MessageEnum;
use App\Exceptions\BusinessException;
use App\Models\File;
use App\Models\Message;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\DB;

class MessageService extends BaseService
{

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
        $sendData = [
            'who' => 'message',
            'action' => 'send',
            'data' => []
        ];

        $data = [
            'from_user' => $fromUser,
            'to_user' => $toUser,
            'content' => $params['content'],
            'is_group' => $params['is_group'],
            'type' => $params['type']
        ];

        //文件消息处理
        if (in_array($params['type'], FileEnum::TYPE)) {
            if (empty($params['file_id'])) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
            $fileId = $params['file_id'];
            $file = File::query()->find($fileId);
            if (!$file) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
            $data['file_id'] = $file->id;
            $data['file_name'] = $file->name;
            $data['file_type'] = $file->type;
            $data['file_size'] = $file->size;
            $data['extends'] = [
                'path' => $file->path,
                'format' => $file->format,
                'width' => $file->width,
                'height' => $file->height,
                'duration' => $file->duration
            ];
        }

        //引用消息处理
        if (!empty($params['pid'])) {
            $message = Message::query()->find($params['pid']);
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
        //at用户处理
        if (!empty($params['at_users'])) {
            $data['at_users'] = $params['at_users'];
            $sendAtData = [
                'who' => 'message',
                'action' => 'at',
                'data' => [
                ]
            ];
            $atUsers = explode(',', $params['at_users']);
            //通知被at的用户
            Gateway::sendToUid($atUsers, json_encode($sendAtData, JSON_UNESCAPED_UNICODE));
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
            $id = Message::query()->insertGetId($data);
            DB::commit();

            $sendData['data'] = Message::query()->with(['from' => function ($query) {
                $query->select('id', 'nickname', 'avatar', 'mobile', 'wechat');
            }])->find($id);

            $sendData['data']['at_user'] = $sendData['data']['at_user'] ? explode(',', $sendData['data']['at_user']) : [];

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
        return $sendData['data'];
    }
}
