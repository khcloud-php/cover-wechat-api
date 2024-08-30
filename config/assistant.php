<?php
/**
 * ai智能助手
 */

return [
    //key对应的用户表的id
    999 => [
        'platform' => 'cloudflare-ai',
        'nickname' => '小助手-莎菲娜',
        'avatar' => 'https://lf-flow-web-cdn.doubao.com/obj/flow-doubao/doubao/web/static/image/logo-icon-white-bg.f3acc228.png',
        'account_id' => '970edfbecb39ea3bae2cc34ac1636ed4',
        'api_uri' => 'https://api.cloudflare.com/client/v4/accounts/970edfbecb39ea3bae2cc34ac1636ed4/ai/run/@cf/meta/llama-3-8b-instruct',
        'token' => 'QGb4agQuPlOgNS6VtzryfUI4xMce3N2Am284OVX2',
        'token_type' => 'Bearer',
        'messages' => [['role' => 'system', 'content' => '我需要你扮演一个百科全书、富有幽默感、人情世故的智能小助手，懂得处于用户的角度去分析并回复用户！']],
        'desc' => '我是生活小助手，我叫萨菲娜，有什么问题可以问我喔！'
    ]
];
