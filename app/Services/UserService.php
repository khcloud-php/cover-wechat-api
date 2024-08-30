<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Database\UserEnum;
use App\Enums\WorkerManEnum;
use App\Exceptions\BusinessException;
use App\Models\Friend;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{

    /**
     * @throws BusinessException
     */
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

        // 把ai小助手添加为朋友
        (new AssistantService())->becomeFriendWhenRegister($user->toArray());

        // 自动登录
        return $this->login($params, true);
    }

    /**
     * @throws BusinessException
     */
    public function login(array $params, bool $auto = false): array
    {
        $user = User::query()->where('mobile', $params['mobile'])->first();
        if (empty($user))
            $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_NOT_FOUND);
        //单点登录，强制下线
        if (Gateway::isUidOnline($user->id)) {
            Gateway::sendToUid($user->id, json_encode([
                'who' => WorkerManEnum::WHO_USER,
                'action' => WorkerManEnum::ACTION_LOGOUT,
                'data' => [
                    'time' => date('Y-m-d H:i:s', time()),
                ]
            ]));
        }

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

    /**
     * @throws BusinessException
     */
    public function home(array $params): array
    {
        $isMobile = is_mobile($params['keywords']);
        $field = $isMobile ? 'mobile' : 'wechat';
        $user = User::query()->where($field, $params['keywords'])->first(['id', 'nickname', 'wechat', 'avatar', 'gender', 'sign']);;

        if (!$user) $this->throwBusinessException(ApiCodeEnum::CLIENT_DATA_NOT_FOUND);
        $userId = $params['user']->id;
        $self = $user->id == $userId;
        $relationship = 'owner';
        $source = $isMobile ? 'mobile' : 'wechat';
        $setting = config('user.friend.setting');
        $homeInfo = ['moment' => [], 'relationship' => 'owner', 'source' => $source, 'source_text' => '', 'remark' => '', 'keywords' => $params['keywords'], 'display_nickname' => $user->nickname];
        $homeInfo = array_merge($homeInfo, $user->toArray());
        $sourceConfig = config('user.source');
        $assistantIds = get_assistant_ids();
        if (!$self) {
            $relationship = FriendEnum::TYPE_APPLY;
            $owner = Friend::query()->where('owner', $userId)->where('friend', $user->id)->first();
            $friend = Friend::query()->where('owner', $user->id)->where('friend', $userId)->first();
            $homeInfo['source_text'] = '通过' . $sourceConfig[$source] . '搜索';
            $homeInfo['check_msg'] = '';
            if ($friend && $friend->status == FriendEnum::STATUS_CHECK) {
                $relationship = FriendEnum::RELATIONSHIP_GO_CHECK;
                $homeInfo['source_text'] = '对方通过' . $sourceConfig[$friend->source] . '搜索';
                $homeInfo['remark'] = $friend->remark;
                $homeInfo['check_msg'] = "{$friend->nickname}：{$friend->remark}";
            }
            if ($owner && $owner->status == FriendEnum::STATUS_PASS) {
                $relationship = FriendEnum::RELATIONSHIP_FRIEND;
                if (in_array($owner->friend, $assistantIds)) {
                    $homeInfo['source_text'] = '对方是你的AI小助手';
                } else {
                    $prefix = $friend->created_at > $owner->created_at ? '' : '对方';
                    $homeInfo['source_text'] = $prefix . '通过搜索' . $sourceConfig[$owner->source] . '添加';
                }
            }
            if ($owner && $owner->status == FriendEnum::STATUS_CHECK) {
                $relationship = FriendEnum::RELATIONSHIP_WAIT_CHECK;
                $homeInfo['remark'] = $owner->remark;
                $homeInfo['source_text'] = '通过' . $sourceConfig[$owner->source] . '搜索';
                $homeInfo['check_msg'] = "我：{$owner->remark}";
            }
            if ($owner) {
                $homeInfo['display_nickname'] = $owner->nickname ?: $user->nickname;
                $homeInfo['source'] = $owner->source;
                $setting = $owner->setting;
            }
        }
        $homeInfo['relationship'] = $relationship;
        if ($relationship != FriendEnum::RELATIONSHIP_FRIEND) {
            Friend::query()->where('owner', $user->id)->where('friend', $userId)->where('is_read', 0)->update(['is_read' => 1]);
        }
        $homeInfo['setting'] = $setting;
        return $homeInfo;
    }

    public function info(array $params): array
    {
        $id = $params['id'];
        $userId = $params['user']->id;
        $self = $id == $userId;
        $user = User::query()->find($params['id'], ['id', 'nickname', 'avatar', 'wechat', 'mobile', 'gender', 'sign', 'setting'])->toArray();
        $user['keywords'] = $user['wechat'];
        if (!$self) {
            $owner = Friend::query()->where('owner', $userId)->where('friend', $id)->first(['nickname', 'setting']);
            if ($owner) {
                $user['setting'] = $owner->setting;
                $user['nickname'] = $owner->nickname ?: $user['nickname'];
                $user['keywords'] = $user[$owner->source];
                $user['desc'] = $owner->desc;
            }
        }
        unset($user['mobile']);
        return $user;
    }

    public function update(array $params): array
    {
        $userId = $params['user']->id;
        $updateAllowFields = ['avatar', 'gender', 'sign', 'nickname', 'setting'];
        $updateData = [];
        foreach ($params as $key => $value) {
            if (in_array($key, $updateAllowFields)) {
                $updateData[$key] = $value;
            }
        }
        if ($updateData)
            User::query()->where('id', $userId)->update($updateData);
        return $updateData;
    }
}
