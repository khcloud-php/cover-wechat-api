<?php

namespace App\Enums;

class WorkerManEnum
{
    const LOG_CHANNEL = 'workerman';

    const ACTION_SEND = 'send';
    const ACTION_APPLY = 'apply';
    const ACTION_LOGOUT = 'logout';
    const ACTION_AT = 'at';
    const ACTION_QUOTE = 'quote';
    const ACTION_LIKE = 'like';
    const ACTION_COMMENT = 'comment';

    const WHO_MESSAGE = 'message';
    const WHO_FRIEND = 'friend';
    const WHO_USER = 'user';
    const WHO_MOMENT = 'moment';

}
