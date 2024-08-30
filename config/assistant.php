<?php
/**
 * ai智能助手
 */

return [
    //key对应的用户表的id
    998 => [
        'platform' => 'cloudflare-ai',
        'wechat' => 'Firstlover',
        'nickname' => '小助手-小初恋',
        'avatar' => 'https://img2.baidu.com/it/u=2922666324,3508300190&fm=253&fmt=auto&app=138&f=JPEG',
        'account_id' => '970edfbecb39ea3bae2cc34ac1636ed4',
        'api_uri' => 'https://api.cloudflare.com/client/v4/accounts/970edfbecb39ea3bae2cc34ac1636ed4/ai/run/@cf/meta/llama-3-8b-instruct-awq',
        'token' => 'QGb4agQuPlOgNS6VtzryfUI4xMce3N2Am284OVX2',
        'token_type' => 'Bearer',
        'messages' => [['role' => 'system', 'content' => '我需要你扮演一个有趣、浪漫的小家伙，能够给别人带来欢乐的气氛，最好能够给用户提供初恋的感觉！']],
        'desc' => '我是生活小助手，我叫小初恋，无聊可以找我解闷喔！',
        'history' => true, //是否需要重跑历史数据
    ],
    999 => [
        'platform' => 'cloudflare-ai',
        'wechat' => 'Shaforina',
        'nickname' => '小助手-莎菲娜',
        'avatar' => 'https://lf-flow-web-cdn.doubao.com/obj/flow-doubao/doubao/web/static/image/logo-icon-white-bg.f3acc228.png',
        'account_id' => '970edfbecb39ea3bae2cc34ac1636ed4',
        'api_uri' => 'https://api.cloudflare.com/client/v4/accounts/970edfbecb39ea3bae2cc34ac1636ed4/ai/run/@cf/meta/llama-3-8b-instruct',
        'token' => 'QGb4agQuPlOgNS6VtzryfUI4xMce3N2Am284OVX2',
        'token_type' => 'Bearer',
        'messages' => [['role' => 'system', 'content' => '我需要你扮演一个百科全书、富有幽默感、人情世故的智能小助手，懂得处于用户的角度去分析并回复用户！']],
        'desc' => '我是生活小助手，我叫萨菲娜，有什么问题可以问我喔！',
        'history' => false,
    ]
];
