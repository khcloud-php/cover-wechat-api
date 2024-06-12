<?php

namespace App\Enums\Redis;

class FriendEnum
{
    const STORE = 'friend';

    const LIST = 'list:%s';

    const APPLY_LIST = 'apply_list:%s';
}
