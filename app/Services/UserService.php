<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\UserEnum;
use App\Exceptions\BusinessException;
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
        $params['setting'] = json_encode([]);
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

    public function home(array $params)
    {
        if ($params['user_id'] == $params['user']->id) {
            //查看自己主页
            var_dump(1111);
        } else {
            //查看朋友主页
        }
        return [];
    }
}
