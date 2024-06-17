<?php

namespace App\Http\Middleware;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\MessageEnum;
use App\Models\Friend;
use App\Models\GroupUser;
use App\Support\Traits\ServiceException;
use Closure;

class Message
{
    use ServiceException;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $fromUser = $request->user()->id;
        $toUser = $request->input('to_user');
        $isGroup = $request->input('is_group');
        if (!$toUser) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        if ($isGroup == MessageEnum::GROUP) {
            //校验是否群成员
            if (!GroupUser::isGroupMember($fromUser, $toUser)) {
                $this->throwBusinessException(ApiCodeEnum::SERVICE_GROUP_MEMBER_NOT_EXISTS);
            }
        } else {
            //好友校验
            list($isFriend, $friend) = Friend::checkIsFriend($fromUser, $toUser, true);
            if (!$isFriend) $this->throwBusinessException(ApiCodeEnum::SERVICE_FRIEND_NOT_EXISTS);
            $request->offsetSet('friend', $friend);
        }
        return $next($request);
    }
}
