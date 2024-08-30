<?php

namespace App\Services;

use App\Enums\Database\FriendEnum;
use App\Enums\Database\GroupEnum;
use App\Enums\Database\MessageEnum;
use App\Enums\Database\UserEnum;
use App\Models\Friend;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\User;

class AssistantService extends BaseService
{
    private array $aiList;

    private int $time;

    private array $assistant;

    public function __construct()
    {
        $this->assistant = config('assistant');
        $this->time = time();
        // ai小助手
        $this->aiList = User::query()->whereIn('id', array_keys($this->assistant))->where('status', UserEnum::STATUS_NORMAL)->get()->toArray();
    }

    /**
     * 用户注册时让小助手成为朋友
     * @param array $user
     * @return void
     */
    public function becomeFriendWhenRegister(array $user): void
    {
        $batchAiData = [];
        $batchMessageData = [];
        foreach ($this->aiList as $ai) {
            $aiConf = $this->assistant[$ai['id']] ?? [];
            $batchAiData[] = [
                'owner' => $user['id'],
                'friend' => $ai['id'],
                'nickname' => $ai['nickname'],
                'type' => FriendEnum::TYPE_VERIFY,
                'status' => FriendEnum::STATUS_PASS,
                'unread' => 1,
                'top' => $this->time,
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'time' => $this->time,
                'display' => 1,
                'remark' => $ai['sign'],
                'setting' => json_encode(config('user.friend.setting')),
                'source' => FriendEnum::SOURCE_ASSISTANT,
                'created_at' => $this->time
            ];
            $batchMessageData[] = [
                'from_user' => $ai['id'],
                'to_user' => $user['id'],
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'created_at' => $this->time
            ];
        }
        Friend::query()->insert($batchAiData);
        Message::query()->insert($batchMessageData);
    }

    /**
     * 创建群聊时让小助手加入群聊
     * @param int $groupId
     * @return void
     */
    public function joinGroupWhenCreateGroup(int $groupId): void
    {
        $batchAiData = [];
        $batchMessageData = [];
        foreach ($this->aiList as $ai) {
            $batchAiData[] = [
                'group_id' => $groupId,
                'user_id' => $ai['id'],
                'role' => GroupEnum::ROLE_ASSISTANT,
                'invite_id' => 0,
                'unread' => 0,
                'setting' => '{}',
                'created_at' => $this->time
            ];
            $batchMessageData[] = [
                'from_user' => $ai['id'],
                'to_user' => $groupId,
                'content' => $aiConf['desc'] ?? $ai['sign'],
                'is_group' => MessageEnum::GROUP,
                'created_at' => $this->time
            ];
        }
        GroupUser::query()->insert($batchAiData);
        Message::query()->insert($batchMessageData);
    }
}
