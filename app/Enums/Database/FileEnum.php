<?php

namespace App\Enums\Database;

class FileEnum
{
    const FILE = 'file';
    const IMAGE = 'image';
    const VIDEO = 'video';

    const TYPE = [
        self::FILE,
        self::IMAGE,
        self::VIDEO,
    ];
}
