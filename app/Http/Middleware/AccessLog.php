<?php

namespace App\Http\Middleware;

use Closure;

class AccessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->offsetSet('request_id', md5(uniqid(time(), true)));
        return $next($request);
    }
}
