<?php
return [
    'owner' => [
        'setting' => [
            'FriendPerm' => [
                'AddMyWay' => [
                    "Mobile" => '1',
                    "Wechat" => '1',
                    "GroupChat" => '1',
                    "QRCode" => '1'
                ]
            ]
        ],
        'unread' => [
            'apply' => 0,
            'moment' => [
                'num' => 0,
                'from' => 0
            ]
        ]
    ],
    'friend' => [
        'setting' => [
            'FriendPerm' => [
                'SettingFriendPerm' => 'ALLOW_ALL',
                'MomentAndStatus' => [
                    "DontLetHimSeeIt" => '0',
                    "DontSeeHim" => '0'
                ]
            ]

        ]
    ],
    'source' => [
        'mobile' => '手机号',
        'wechat' => '微信号',
        'qrcode' => '扫一扫'
    ]
];
