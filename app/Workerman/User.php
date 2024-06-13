<?php

namespace App\Workerman;

use GatewayWorker\Lib\Gateway;

class User extends Base
{
    public function login(string $clientId, array $data)
    {
        Gateway::bindUid($clientId, $data['id']);
    }

    public function logout(string $clientId)
    {
        $uid = Gateway::getUidByClientId($clientId);
        Gateway::unbindUid($clientId, $uid);
    }
}
