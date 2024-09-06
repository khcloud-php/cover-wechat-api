<?php

namespace App\Enums;

class RedisEnum
{
    const LOG_CHANNEL = 'redis';
    const USER_REQUEST_API_LIMIT = 'api:%s:%s:limit';
    const IP_REQUEST_API_LIMIT = 'ip:%s:%s:limit';
}
