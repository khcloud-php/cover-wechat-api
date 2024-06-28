<?php

namespace App\Workerman\Action;

use App\Enums\Redis\UserEnum;
use GatewayWorker\Lib\Gateway;
use App\Models\GroupUser;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class User
{


    /**
     * @throws InvalidArgumentException
     */
    public function login(string $clientId, array $data): void
    {
        echo "clientId:{$clientId} 上线了\n";
        $uid = $data['uid'];
        echo "uid:{$uid} 上线了\n";
        Gateway::bindUid($clientId, $uid);
        $groupIds = $this->getJoinGroupIds($uid);
        if (!$groupIds) return;
        foreach ($groupIds as $groupId) {
            Gateway::joinGroup($clientId, $groupId);
        }
        Cache::store(UserEnum::STORE)->set(sprintf(UserEnum::BIND_UID, $clientId), $uid);
        Cache::store(UserEnum::STORE)->set(sprintf(UserEnum::JOIN_GROUPS, $clientId), $groupIds);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function logout(string $clientId): void
    {
        $uid = Cache::store(UserEnum::STORE)->get(sprintf(UserEnum::BIND_UID, $clientId));
        echo "uid:{$uid} 离线了\n";
        if ($uid) {
            $groupIds = $this->getJoinGroupIdsByClientId($clientId);
            if ($groupIds) {
                foreach ($groupIds as $groupId) {
                    Gateway::leaveGroup($clientId, $groupId);
                }
                Cache::store(UserEnum::STORE)->forget(sprintf(UserEnum::JOIN_GROUPS, $clientId));
            }
            Gateway::unbindUid($clientId, $uid);
            Cache::store(UserEnum::STORE)->forget(sprintf(UserEnum::BIND_UID, $clientId));
        }


    }

    private function getJoinGroupIds(int|string $uid): array
    {
        $groups = GroupUser::query()->where('user_id', $uid)->get(['group_id'])->toArray();
        return $groups ? array_column($groups, 'group_id') : [];
    }

    public function getJoinGroupIdsByClientId(string $clientId): array
    {
        $groupIds = Cache::store(UserEnum::STORE)->get(sprintf(UserEnum::JOIN_GROUPS, $clientId));
        return $groupIds ?: [];
    }
}
