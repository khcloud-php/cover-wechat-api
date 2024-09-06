<?php

namespace App\Http\Middleware;

use App\Enums\ApiCodeEnum;
use App\Enums\RedisEnum;
use App\Exceptions\BusinessException;
use App\Support\Traits\ServiceException;
use Closure;
use Illuminate\Support\Facades\RateLimiter;

class AccessLog
{
    use ServiceException;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws BusinessException
     */
    public function handle($request, Closure $next)
    {
        //接口限流
        $api = strtolower($request->path());
        $limit = 60;
        if ($api == 'message/send') {
            $aiUsers = array_keys(config('assistant'));
            $toUser = $request->post('to_user', 0);
            $atUsers = $request->post('at_users', '');
            $atUsers = explode(',', $atUsers);
            if ($atUsers) {
                $atUsers = array_map('intval', $atUsers);
            }
            if (in_array($toUser, $aiUsers) || array_intersect($atUsers, $aiUsers)) {
                $limit = 5;
            }
        }
        if (!empty($request->user()->id)) {
            $limitKey = sprintf(RedisEnum::USER_REQUEST_API_LIMIT, md5($api), $request->user()->id);
        } else {
            $limitKey = sprintf(RedisEnum::USER_REQUEST_API_LIMIT, md5($api), $request->ip());
        }
        $isLimit = RateLimiter::tooManyAttempts($limitKey, $limit);
        if ($isLimit) {
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, '服务器繁忙~');
        }

        $request->offsetSet('request_id', md5(uniqid(time(), true)));
        return $next($request);
    }
}
