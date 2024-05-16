<?php
return [
    'owner' => [
        'setting' => [
            'FriendPerm' => [
                'AddMyWay' => [
                    "Mobile" => 1,
                    "Wechat" => 1,
                    "GroupChat" => 1,
                    "QRCode" => 1
                ]
            ]
        ]
    ],
    'friend' => [
        'setting' => [
            'SettingFriendPerm' => 'ALL',
            'MomentAndStatus' => [
                "DontLetHimSeeIt" => 0,
                "DontSeeHim" => 0
            ]
        ]
    ],
    'source' => [
        'mobile' => '手机号',
        'wechat' => '微信号',
        'qrcode' => '扫一扫'
    ]
];
