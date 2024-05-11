<?php
if (!function_exists('rand_avatar')) {
    //生成随机头像
    function rand_avatar($str)
    {
        $md5_str = md5($str);
        return "https://api.multiavatar.com/{$md5_str}.png";
    }
}
