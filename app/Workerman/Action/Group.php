<?php

namespace App\Workerman\Action;

use App\Enums\Database\GroupEnum;
use App\Enums\Database\MessageEnum;
use App\Enums\WorkerManEnum;
use App\Models\Message;
use GatewayWorker\Lib\Gateway;
use App\Models\User;

class Group
{
    /**
     * 创建群聊或邀请好友进群
     * @param string $clientId
     * @param array $data
     * @return void
     */
    public function action(string $clientId, array $data): void
    {
        $groupId = $data['group_id'];
        $groupName = $data['group_name'];
        $groupUsers = $data['group_users'];
        $action = $data['action'];
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
            'content' => "‘{$user->nickname}’创建了群聊‘{$groupName}’",
            'is_tips' => 1,
            'is_group' => MessageEnum::GROUP,
            'type' => MessageEnum::TEXT,
            'created_at' => time(),
            'deleted_users' => $userId
        ];
        if ($action == GroupEnum::ACTION_INVITE) {
            $message['content'] = "‘{$user->nickname}’邀请你进入群聊‘{$groupName}’";
        }
        Message::query()->insert($message);
        $message['content'] = "你创建了群聊‘{$groupName}’";
        if ($action == GroupEnum::ACTION_INVITE) {
            $cnt = count($groupUsers);
            $message['content'] = "你邀请了{$cnt}人进入群聊‘{$groupName}’";
        }
        $message['deleted_users'] = implode(',', $groupUsers);
        Message::query()->insert($message);
        $sendData = [
            'who' => WorkerManEnum::WHO_MESSAGE,
            'action' => WorkerManEnum::ACTION_SEND,
            'data' => []
        ];
        Gateway::sendToGroup($groupId, json_encode($sendData, JSON_UNESCAPED_UNICODE), [$clientId]);
    }
}
