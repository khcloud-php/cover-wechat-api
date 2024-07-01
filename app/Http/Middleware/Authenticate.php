<?php

namespace App\Http\Middleware;

use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use App\Support\Traits\ServiceException;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Crypt;

class Authenticate
{
    use ServiceException;
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     * @throws BusinessException
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if ($this->auth->guard($guard)->guest()) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_HTTP_UNAUTHORIZED_EXPIRED);
        }

        $token = str_replace('Bearer ', '', $request->header('Authorization'));

        if (!$token) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_TOKEN_UNAVAILABLE);
        }

        $tokenInfo = Crypt::decryptString($token);
        if (!$tokenInfo) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_TOKEN_UNAVAILABLE);
        }
        $tokenInfo = explode('|', $tokenInfo);
        $user = $request->user();
        if ($tokenInfo[0] != $user->id) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_HTTP_UNAUTHORIZED_EXPIRED);
        }
        if ($user->token_expire_in < time()) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_HTTP_UNAUTHORIZED_EXPIRED);
        }
        return $next($request);
    }
}
