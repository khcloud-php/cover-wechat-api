<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Database\GroupEnum;
use App\Models\Friend;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Facades\DB;

class GroupService extends BaseService
{
    public function list(array $params)
    {

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
