<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Database\UserEnum;
use App\Exceptions\BusinessException;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{

    public function register(array $params): array
    {
        $user = User::query()->where('mobile', $params['mobile'])->orWhere('wechat', $params['wechat'])->first(['mobile', 'wechat']);
        if ($user && $user->mobile == $params['mobile']) $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_ALREADY_EXISTS);
        if ($user && $user->wechat == $params['wechat']) $this->throwBusinessException(ApiCodeEnum::SERVICE_WECHAT_ALREADY_EXISTS);
        $params['salt'] = Str::password(10);
        $params['setting'] = config('user.owner.setting');
        $params['password'] = Hash::make($params['salt'] . $params['password']);
        empty($params['avatar']) && $params['avatar'] = rand_avatar($params['mobile']);
        $user = new User($params);
        $user->wechat = $params['wechat'];
        $user->mobile = $params['mobile'];
        $user->password = $params['password'];
        $user->salt = $params['salt'];
        $user->save();
        // 自动登录
        return $this->login($params, true);
    }

    public function login(array $params, bool $auto = false): array
    {
        $user = User::query()->where('mobile', $params['mobile'])->first();
        if (empty($user))
            $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_NOT_FOUND);

        if ($user->status != UserEnum::STATUS_NORMAL)
            $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_DISABLED);

        if (!$auto) {
            // 密码验证
            if (!Hash::check($user->salt . $params['password'], $user->password))
                $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_OR_PASSWORD_ERROR);
        }
        $time = time();
        $user->token_expire_in = $time + config('auth.token_expire_time');
        $user->token = Crypt::encryptString($user->id . '|' . $time);
        $user->save();
        $user = $user->toArray();
        unset($user['password'], $user['salt']);
        return $user;
    }

    public function logout(int $userId): int
    {
        return User::query()->where('id', $userId)->update(['token' => '', 'token_expire_in' => 0]);
    }

    public function home(array $params): array
    {
        $isMobile = is_mobile($params['keywords']);
        if ($isMobile) {
            $user = User::query()->where('mobile', $params['keywords'])->first(['id', 'nickname', 'wechat', 'avatar', 'gender', 'sign']);
        } else {
            $user = User::query()->where('wechat', $params['keywords'])->first(['id', 'nickname', 'wechat', 'avatar', 'gender', 'sign']);
        }

        if (!$user) $this->throwBusinessException(ApiCodeEnum::CLIENT_DATA_NOT_FOUND);
        $userId = $params['user']->id;
        $self = $user->id == $userId;
        $relationship = 'owner';
        $source = $isMobile ? 'mobile' : 'wechat';
        $homeInfo = ['moment' => [], 'relationship' => 'owner', 'source' => $source, 'source_text' => '', 'remark' => '', 'setting' => [], 'keywords' => $params['keywords']];
        $homeInfo = array_merge($homeInfo, $user->toArray());
        $sourceConfig = config('user.source');
        if (!$self) {
            $relationship = FriendEnum::TYPE_APPLY;
            $friend = Friend::query()->where('owner', $userId)->where('friend', $user->id)->first();
            $owner = Friend::query()->where('owner', $user->id)->where('friend', $userId)->first();
            $homeInfo['source_text'] = '通过' . $sourceConfig[$source] . '搜索';
            if ($owner && $owner->status == FriendEnum::STATUS_CHECK) {
                $relationship = 'go_check';
                $homeInfo['source_text'] = '对方通过' . $sourceConfig[$owner->source] . '搜索';
                $homeInfo['remark'] = $owner->remark;
            }
            if ($friend && $friend->status == FriendEnum::STATUS_PASS) {
                $relationship = 'friend';
                $prefix = $owner->created_at > $friend->created_at ? '' : '对方';
                $homeInfo['source_text'] = $prefix . '通过搜索' . $sourceConfig[$friend->source] . '添加';
                $homeInfo['setting'] = $friend->setting;
            }
            if ($friend && $friend->status == FriendEnum::STATUS_CHECK) {
                $relationship = 'wait_check';
                $homeInfo['remark'] = $friend->remark;
                $homeInfo['source_text'] = '通过' . $sourceConfig[$owner->source] . '搜索';
            }
            if ($friend && $friend->nickname) $homeInfo['nickname'] = $friend->nickname;
            if ($friend) {
                $homeInfo['source'] = $friend->source;
            }
        } else {
            $homeInfo['setting'] = $user->setting;
        }
        $homeInfo['relationship'] = $relationship;
        return $homeInfo;
    }
}
