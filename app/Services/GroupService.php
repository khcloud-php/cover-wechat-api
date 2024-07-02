<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Database\GroupEnum;
use App\Enums\Database\MessageEnum;
use App\Models\Friend;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Facades\DB;

class GroupService extends BaseService
{
    public function list(array $params): array
    {
        $userId = (int)$params['user']->id;
        $groupInfoList = GroupUser::query()
            ->where('user_id', $userId)
            ->get(['group_id', 'name'])->toArray();
        $groupIds = array_column($groupInfoList, 'group_id');
        $groupInfoList = array_column($groupInfoList, null, 'group_id');
        $groupUserList = GroupUser::query()
            ->with(['user' => function ($query) {
                $query->select(['id', 'avatar']);
            }])
            ->whereIn('group_id', $groupIds)
            ->orderByDesc('group_id')
            ->orderByDesc('created_at')
            ->get(['group_id', 'user_id'])->toArray();
        if (empty($groupUserList)) return [];
        $groupAvatars = [];
        foreach ($groupUserList as $groupUser) {
            //群聊最多四个头像
            if (isset($groupAvatars[$groupUser['group_id']]) && count($groupAvatars[$groupUser['group_id']]) > 3) {
                continue;
            }
            $groupAvatars[$groupUser['group_id']][] = $groupUser['user']['avatar'];
        }

        unset($groupUserList, $groupUser);
        $groupList = Group::query()
            ->whereIn('id', $groupIds)
            ->get()->toArray();
        $data = [];
        foreach ($groupList as $group) {
            $nickname = $group['name'];
            if (!empty($groupInfoList[$group['id']]['name'])) {
                $nickname = $groupInfoList[$group['id']]['name'];
            }
            $data[] = [
                'id' => $group['id'],
                'nickname' => $nickname,
                'is_group' => MessageEnum::GROUP,
                'to' => [
                    'id' => $group['id'],
                    'avatars' => $groupAvatars[$group['id']] ?? [],
                ]
            ];
        }

        return $data;
    }

    public function create(array $params): array
    {
        $userId = $params['user']->id;
        $friendList = Friend::query()->with(['to' => function ($query) {
            $query->select(['id', 'nickname']);
        }])->where('owner', $userId)
            ->whereIn('friend', $params['group_users'])
            ->where('status', FriendEnum::STATUS_PASS)
            ->get(['id', 'nickname', 'friend'])->toArray();

        $groupUsers = [$userId];
        $groupUsers = array_merge($groupUsers, array_column($friendList, 'friend'));
        $name = "群聊";
        $data = [];
        DB::beginTransaction();
        try {
            $groupData = [
                'name' => $name,
                'owner' => $userId,
                'notice' => '',
                'send_user' => $userId,
                'content' => '创建了群聊',
                'time' => time(),
                'setting' => json_encode([]),
                'created_at' => time(),
            ];
            $id = Group::query()->insertGetId($groupData);
            $batch = [];
            foreach ($groupUsers as $groupUser) {
                $batch[] = [
                    'group_id' => $id,
                    'user_id' => $groupUser,
                    'role' => $groupUser == $userId ? GroupEnum::ROLE_SUPER : GroupEnum::ROLE_USER,
                    'invite_id' => $userId,
                    'name' => $name,
                    'setting' => json_encode([]),
                    'created_at' => time(),
                ];
            }
            GroupUser::query()->insertOrIgnore($batch);
            $data = ['group_id' => $id, 'group_users' => $groupUsers, 'group_name' => $name];
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
        return $data;
    }
}
