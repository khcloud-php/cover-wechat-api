<?php

namespace App\Workerman\Action;

use App\Enums\Database\MessageEnum;
use App\Models\Message;
use GatewayWorker\Lib\Gateway;
use App\Models\User;

class Group
{
    public function create(string $clientId, array $data): void
    {
        $groupId = $data['group_id'];
        $groupName = $data['group_name'];
        $groupUsers = $data['group_users'];
        $userId = $groupUsers[0];
        $clientIds = [$clientId];
        foreach ($groupUsers as $groupUser) {
            $clientIds = array_merge($clientIds, Gateway::getClientIdByUid($groupUser));
        }
        foreach ($clientIds as $v) {
            Gateway::joinGroup($v, $groupId);
        }
        $user = User::query()->find($userId);

        array_shift($groupUsers);
        $message = [
            'from_user' => $userId,
            'to_user' => $groupId,
            'content' => $user->nickname . '邀请你进入群聊‘' . $groupName . '’',
            'is_tips' => 1,
            'is_group' => MessageEnum::GROUP,
            'type' => MessageEnum::TEXT,
            'created_at' => time(),
            'deleted_users' => $userId
        ];
        Message::query()->insert($message);
        $message['content'] = '你创建了群聊‘' . $groupName . '’';
        $message['deleted_users'] = implode(',', $groupUsers);
        Message::query()->insert($message);
        $sendData = [
            'who' => 'message',
            'action' => 'send',
            'data' => []
        ];
        Gateway::sendToGroup($groupId, json_encode($sendData, JSON_UNESCAPED_UNICODE), [$clientId]);
    }
}
