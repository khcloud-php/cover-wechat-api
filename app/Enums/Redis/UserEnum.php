<?php

namespace App\Enums\Redis;

class UserEnum
{
    const STORE = 'user';
    const JOIN_GROUPS = 'join_groups:%s';
    const BIND_UID = 'bind_uid:%s';
}
