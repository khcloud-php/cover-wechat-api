<?php

namespace App\Workerman\Action;

use App\Enums\Database\GroupEnum;
use GatewayWorker\Lib\Gateway;

class Group extends Base
{
    public function create(string $clientId, array $data)
    {
        $groupUsers = $data['group_users'];
        $clientIds = [$clientId];
        foreach ($groupUsers as $groupUser) {
            $clientIds = array_merge($clientIds, Gateway::getClientIdByUid($groupUser));
        }
        foreach ($clientIds as $clientId) {
            Gateway::joinGroup($clientId, $data['group_id']);
        }
    }

    public function leave(string $clientId, array $data)
    {
        Gateway::leaveGroup($clientId, $data['group_id']);
    }
}
