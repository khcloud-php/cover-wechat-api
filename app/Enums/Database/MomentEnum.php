<?php

namespace App\Enums\Database;

class MomentEnum
{
    const TEXT = 'text';
    const IMAGE = 'image';
    const VIDEO = 'video';
    const TYPE = [
        self::TEXT,
        self::IMAGE,
        self::VIDEO,
    ];
    const PUBLIC = 'public';
    const PRIVATE = 'private';
    const VISIBLE = 'visible';
    const INVISIBLE = 'invisible';
    const PERM = [
        self::PUBLIC,
        self::PRIVATE,
        self::VISIBLE,
        self::INVISIBLE,
    ];

    const LIKE = 'like';
    const COMMENT = 'comment';
}
