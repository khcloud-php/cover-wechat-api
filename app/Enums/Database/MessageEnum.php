<?php

namespace App\Enums\Database;

class MessageEnum
{
    const TEXT = 'text';
    const VIDEO_CALL = 'video_call';
    const AUDIO_CALL = 'audio_call';
    const RED_PACKET = 'red_packet';
    const GROUP = 1;
    const PRIVATE = 0;

    const TYPE = [
        self::TEXT,
        FileEnum::FILE,
        FileEnum::IMAGE,
        FileEnum::VIDEO,
        FileEnum::AUDIO,
        self::VIDEO_CALL,
        self::AUDIO_CALL,
        self::RED_PACKET,
    ];

    const SIMPLE_CONTENT = [
        FileEnum::FILE => '[文件信息]',
        FileEnum::IMAGE => '[图片信息]',
        FileEnum::VIDEO => '[视频信息]',
        FileEnum::AUDIO => '[语音消息]',
        self::VIDEO_CALL => '[视频通话]',
        self::AUDIO_CALL => '[语音通话]',
        self::RED_PACKET => '[红包消息]'
    ];

    const IS_GROUP = [self::PRIVATE, self::GROUP];
}
