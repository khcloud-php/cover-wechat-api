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
    const RELATIONSHIP_GO_CHECK = 'go_check';
    const RELATIONSHIP_WAIT_CHECK = 'wait_check';
    const RELATIONSHIP_FRIEND = 'friend';

    const PASS_MESSAGE = '我通过了你的好友验证请求，现在我们可以开始聊天了';
}
