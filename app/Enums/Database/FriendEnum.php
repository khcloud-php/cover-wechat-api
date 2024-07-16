<?php

namespace App\Enums\Database;

class FriendEnum
{
    const TYPE_APPLY = 'apply';
    const TYPE_VERIFY = 'verify';
    const STATUS_CHECK = 'check';
    const STATUS_PASS = 'pass';
    const STATUS_OVERDUE = 'overdue';
    const SOURCE_MOBILE = 'mobile';
    const SOURCE_WECHAT = 'wechat';
    const SOURCE_GROUP = 'group';

    const PASS_MESSAGE = '我通过了你的好友验证请求，现在我们可以开始聊天了';
}
