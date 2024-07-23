<?php

namespace App\Enums\Database;

class FileEnum
{
    const FILE = 'file';
    const IMAGE = 'image';
    const VIDEO = 'video';

    const AUDIO = 'audio';

    const TYPE = [
        self::FILE,
        self::IMAGE,
        self::VIDEO,
        self::AUDIO
    ];

    const CONTENT = [
        self::FILE => '[文件信息]',
        self::IMAGE => '[图片信息]',
        self::VIDEO => '[视频信息]',
        self::AUDIO => '[语音消息]'
    ];

    const FILE_LIMIT_SIZE = 5 * 1024 * 1024;
    const IMAGE_LIMIT_SIZE = 5 * 1024 * 1024;
    const VIDEO_LIMIT_SIZE = 30 * 1024 * 1024;

    const AUDIO_LIMIT_SIZE = 10 * 1024 * 1024;
}
