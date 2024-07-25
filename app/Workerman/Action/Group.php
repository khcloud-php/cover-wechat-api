<?php

namespace App\Workerman\Action;

use App\Enums\Database\GroupEnum;
use App\Enums\Database\MessageEnum;
use App\Enums\WorkerManEnum;
use App\Models\Message;
use GatewayWorker\Lib\Gateway;
use App\Models\User;
use App\Models\Group as GroupModel;

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
        $time = time();
        $message = [
            'from_user' => $userId,
            'to_user' => $groupId,
            'content' => "‘{$user->nickname}’创建了群聊‘{$groupName}’",
            'is_tips' => 1,
            'is_group' => MessageEnum::GROUP,
            'type' => MessageEnum::TEXT,
            'created_at' => $time
        ];

        if ($action == GroupEnum::ACTION_INVITE) {
            $users = User::query()->whereIn('id', $groupUsers)->get(['nickname'])->toArray();
            $cnt = count($users);
            $userNicknames = array_column($users, 'nickname');
            $message['content'] = "‘{$user->nickname}’邀请" . implode('、', $userNicknames) . "等{$cnt}人进入群聊";
            GroupModel::query()->where('id', $groupId)->update(['send_user' => $userId, 'content' => $message['content'], 'time' => $time]);
        }
        Message::query()->insert($message);
        $sendData = [
            'who' => WorkerManEnum::WHO_MESSAGE,
            'action' => WorkerManEnum::ACTION_SEND,
            'data' => [
                'from' => [
                    'id' => $userId,
                    'avatar' => $user->avatar,
                    'nickname' => $user->nickname
                ],
                'from_user' => $userId,
                'to_user' => $groupId,
                'content' => $message['content'],
                'type' => MessageEnum::TEXT,
                'file' => [],
                'extends' => [],
                'pid' => 0,
                'is_tips' => 1,
                'is_undo' => 0,
                'pcontent' => '',
                'at_users' => [],
                'is_group' => MessageEnum::GROUP,
                'right' => false,
                'time' => $time,
            ]
        ];
        Gateway::sendToGroup($groupId, json_encode($sendData, JSON_UNESCAPED_UNICODE), [$clientId]);
    }
}
