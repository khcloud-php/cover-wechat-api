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

    const CONTENT = [
        self::FILE => '[文件信息]',
        self::IMAGE => '[图片信息]',
        self::VIDEO => '[视频信息]'
    ];
}
