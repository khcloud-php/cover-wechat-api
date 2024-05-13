<?php

use Overtrue\Pinyin\Pinyin;

if (!function_exists('rand_avatar')) {
    //生成随机头像
    function rand_avatar($str)
    {
        $md5Str = md5($str);
        return "https://api.multiavatar.com/{$md5Str}.png";
    }
}

if (!function_exists('is_mobile')) {
    //手机号判断
    function is_mobile($str)
    {
        $pattern = "/^1[3-9]\d{9}$/";
        return preg_match($pattern, $str) > 0;
    }
}

if (!function_exists('group_by_first_char')) {
    //通过中英文的拼音英文首字母分组
    function group_by_first_char($array, $field)
    {
        $grouped = [];
        foreach ($array as $item) {
            $str = $item['friend'][$field];
            $firstChar = '';
            if (preg_match('/^[\x{4e00}-\x{9fa5}]/u', $str)) { // 匹配中文字符
                $str = (string)Pinyin::sentence($str);
            }

            $firstChar = strtoupper($str[0]);

            if (!isset($grouped[$firstChar])) {
                $grouped[$firstChar] = [];
            }
            $grouped[$firstChar][] = $item;
        }
        return $grouped;
    }
}
