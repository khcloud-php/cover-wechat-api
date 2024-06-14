<?php

namespace App\Enums\Database;

class MessageEnum
{
    const TEXT = 'text';
    const GROUP = 1;
    const PRIVATE = 0;

    const TYPE = [
        self::TEXT,
        FileEnum::FILE,
        FileEnum::IMAGE,
        FileEnum::VIDEO
    ];

    const IS_GROUP = [self::PRIVATE, self::GROUP];
}
