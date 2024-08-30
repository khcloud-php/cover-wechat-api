<?php

namespace App\Enums\Redis;

class ChatEnum
{
    const LIST = 'chat_list:%s:%s';

    const MESSAGES = 'chat_messages:%s:%s';

    const ASSISTANT_REPLY = 'chat_assistant_reply';
}
