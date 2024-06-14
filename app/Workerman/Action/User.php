<?php

namespace App\Workerman\Action;

use GatewayWorker\Lib\Gateway;
use App\Models\GroupUser;

class User extends Base
{
    public function login(string $clientId, array $data)
    {
        $uid = $data['uid'];
        Gateway::bindUid($clientId, $uid);
        $groupIds = $this->getJoinGroupIds($uid);
        foreach ($groupIds as $groupId) {
            Gateway::joinGroup($clientId, $groupId);
        }
    }

    public function logout(string $clientId)
    {
        $uid = Gateway::getUidByClientId($clientId);
        Gateway::unbindUid($clientId, $uid);
        $groupIds = $this->getJoinGroupIds($uid);
        foreach ($groupIds as $groupId) {
            Gateway::leaveGroup($clientId, $groupId);
        }
    }

    private function getJoinGroupIds($uid): array
    {
        $groups = GroupUser::query()->where('user_id', $uid)->get(['group_id'])->toArray();
        return array_column($groups, 'group_id');
    }
}
