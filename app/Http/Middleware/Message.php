<?php

namespace App\Http\Middleware;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\MessageEnum;
use App\Exceptions\BusinessException;
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
     * @throws BusinessException
     */
    public function handle($request, Closure $next)
    {
        $fromUser = $request->user()->id;
        $toUser = $request->input('to_user');
        $isGroup = $request->input('is_group');
        if (!in_array($isGroup, MessageEnum::IS_GROUP)) {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        if (!$toUser) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        if ($isGroup == MessageEnum::GROUP) {
            //校验是否群成员
            list($isGroupMember, $group) = GroupUser::checkIsGroupMember($fromUser, $toUser, true);
            if (!$isGroupMember) $this->throwBusinessException(ApiCodeEnum::SERVICE_GROUP_USER_NOT_EXISTS);
            $from = [
                'id' => $fromUser,
                'nickname' => $group['nickname'] ?: $request->user()->nickname,
                'avatar' => $request->user()->avatar,
                'wechat' => $request->user()->wechat
            ];
        } else {
            //好友校验
            list($isFriend, $friend) = Friend::checkIsFriend($fromUser, $toUser, true);
            if (!$isFriend) $this->throwBusinessException(ApiCodeEnum::SERVICE_FRIEND_NOT_EXISTS);
            $from = [
                'id' => $fromUser,
                'nickname' => $friend['nickname'] ?: $request->user()->nickname,
                'avatar' => $request->user()->avatar,
                'wechat' => $request->user()->wechat
            ];
        }
        $request->offsetSet('from', $from);
        return $next($request);
    }
}
