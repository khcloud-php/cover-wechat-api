<?php

namespace App\Workerman\Action;

use App\Enums\Redis\UserEnum;
use App\Enums\WorkerManEnum;
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
//        $_SESSION[sprintf(UserEnum::BIND_UID, $clientId)] = $uid;
//        $_SESSION[sprintf(UserEnum::JOIN_GROUPS, $clientId)] = json_encode($groupIds);
        Cache::store(UserEnum::STORE)->set(sprintf(UserEnum::BIND_UID, $clientId), $uid);
        Cache::store(UserEnum::STORE)->set(sprintf(UserEnum::JOIN_GROUPS, $clientId), $groupIds);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function logout(string $clientId): void
    {
//        $uid = $_SESSION[sprintf(UserEnum::BIND_UID, $clientId)];
        $uid = Cache::store(UserEnum::STORE)->get(sprintf(UserEnum::BIND_UID, $clientId));
        echo "uid:{$uid} 离线了\n";
        if ($uid) {
            $groupIds = $this->getJoinGroupIdsByClientId($clientId);
            if ($groupIds) {
                foreach ($groupIds as $groupId) {
                    Gateway::leaveGroup($clientId, $groupId);
                }
//                unset($_SESSION[sprintf(UserEnum::JOIN_GROUPS, $clientId)]);
                Cache::store(UserEnum::STORE)->forget(sprintf(UserEnum::JOIN_GROUPS, $clientId));
            }
            Gateway::unbindUid($clientId, $uid);
//            unset($_SESSION[sprintf(UserEnum::BIND_UID, $clientId)]);
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
//        $groupIds = $_SESSION[sprintf(UserEnum::JOIN_GROUPS, $clientId)];
//        $groupIds = json_decode($groupIds);
        $groupIds = Cache::store(UserEnum::STORE)->get(sprintf(UserEnum::JOIN_GROUPS, $clientId));
        return $groupIds ?: [];
    }

    public function call(string $clientId, $data)
    {
        $uid = Gateway::getUidByClientId($clientId);
        $data['who'] = WorkerManEnum::WHO_USER;
        $data['action'] = WorkerManEnum::ACTION_CALL;
        Gateway::sendToUid($uid, $data);
    }
}
