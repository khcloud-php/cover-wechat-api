<?php

use Overtrue\Pinyin\Pinyin;

if (!function_exists('rand_avatar')) {
    //生成随机头像
    function rand_avatar(string|int $str): string
    {
        $md5Str = md5($str);
        return "https://api.multiavatar.com/{$md5Str}.png";
    }
}

if (!function_exists('is_mobile')) {
    //手机号判断
    function is_mobile(string|int $str): bool
    {
        $pattern = "/^1[3-9]\d{9}$/";
        return preg_match($pattern, $str) > 0;
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return app()->basePath('public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('group_by_first_char')) {
    //通过中英文的拼音英文首字母分组
    function group_by_first_char(array $array, string $field): array
    {
        $grouped = [];

        //小助手排前面
        $assistantIds = get_assistant_ids();
        foreach ($array as $k => $v) {
            if (in_array($v['friend'], $assistantIds)) {
                unset($array[$k]);
                $firstChar = 'A';
                if (!isset($grouped[$firstChar])) {
                    $grouped[$firstChar] = [];
                }
                $grouped[$firstChar][] = $v;
            }
        }
        foreach ($array as $item) {
            $str = $item[$field];
            if (preg_match('/^[\x{4e00}-\x{9fa5}]/u', $str)) { // 匹配中文字符
                $str = (string)Pinyin::sentence($str);
            }

            $firstChar = strtoupper($str[0]);
            if (!ctype_upper($firstChar)) $firstChar = 'A';

            if (!isset($grouped[$firstChar])) {
                $grouped[$firstChar] = [];
            }
            $grouped[$firstChar][] = $item;
        }
        return $grouped;
    }
}

if (!function_exists('is_linux')) {
    /**
     * 判断当前操作系统是否为 Linux
     *
     * @return bool
     */
    function is_linux(): bool
    {
        return stripos(PHP_OS, 'Linux') !== false || stripos(php_uname('s'), 'Linux') !== false;
    }
}

if (!function_exists('get_assistant_ids')) {
    function get_assistant_ids(): array
    {
        return array_keys(config('assistant'));
    }
}

if (!function_exists('get_page_info')) {
    function get_page_info(int $page, int $limit, int $total): array
    {
        return [
            'total' => $total,
            'total_page' => ceil($total / $limit),
            'current_page' => $page,
        ];
    }
}
