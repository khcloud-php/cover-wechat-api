<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\FriendEnum;
use App\Enums\Redis\FriendEnum as RedisFriendEnum;
use App\Exceptions\BusinessException;
use App\Models\Friend;
use App\Models\User;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FriendService extends BaseService
{
    public function list(array $params): array
    {
        $userId = $params['user']->id;
        $friendList = Friend::query()->with(['friend' => function ($query) {
                $query->select(['id', 'nickname', 'avatar', 'wechat', 'mobile']);
            }])->where('owner', $userId)
                ->where('status', FriendEnum::STATUS_PASS)
                ->get(['id', 'owner', 'friend', 'nickname', 'source'])->toArray();

        foreach ($friendList as &$friend) {
            $friend['nickname'] = $friend['nickname'] ?: $friend['friend']['nickname'];
            $friend['avatar'] = $friend['friend']['avatar'];
            $friend['keywords'] = $friend['friend'][$friend['source']];
            $friend['checked'] = false;
            $friend['friend'] = $friend['friend']['id'];
        }
        return group_by_first_char($friendList, 'nickname');
    }

    public function applyList(array $params): array
    {
        $userId = $params['user']->id;
        $applyList = Friend::query()->with(['friend' => function ($query) {
                $query->select(['id', 'nickname', 'avatar', 'mobile', 'wechat']);
            }, 'owner' => function ($query) {
                $query->select(['id', 'nickname', 'avatar', 'mobile', 'wechat']);
            }])->where('display', 1)
                ->whereRaw("owner = {$userId} OR (friend = {$userId} and type = '" . FriendEnum::TYPE_APPLY . "')")
                ->get()->toArray();

        $day = 86400;
        $threeDay = $overThreeDay = [];
        foreach ($applyList as &$apply) {
            if ($apply['friend']['id'] == $userId) {
                $owner = $apply['owner'];
                $friend = $apply['friend'];
                $apply['owner'] = $friend;
                $apply['friend'] = $owner;
                $apply['status'] = 'go_check';
            } elseif ($apply['status'] == FriendEnum::STATUS_CHECK) {
                $apply['status'] = 'wait_check';
            }
            $apply['keywords'] = $apply['friend'][$apply['source']];
            unset($apply['friend']['mobile'], $apply['friend']['wechat'], $apply['owner']['mobile'], $apply['owner']['wechat']);
            $field = $apply['updated_at'] ? 'updated_at' : 'created_at';
            $days = (time() - strtotime($apply[$field])) / $day;
            if ($days > 3) {
                $overThreeDay[] = $apply;
            } else {
                $threeDay[] = $apply;
            }
        }
        Friend::query()->where('friend', $userId)->where('is_read', 0)->update(['is_read' => 1]);
        return ['three_day' => $threeDay, 'over_three_day' => $overThreeDay];
    }

    public function deleteApply($id, $userId): int
    {
        try {
            $delKeys = [
                sprintf(RedisFriendEnum::APPLY_LIST, $userId)
            ];
            $this->forgetRememberCache(RedisFriendEnum::STORE, ...$delKeys);
            return Friend::query()->where('id', $id)->update(['display' => 0]);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function search(array $params): array
    {
        $keywords = $params['keywords'];
        //黑名单
        $isMobile = is_mobile($keywords);
        $source = $isMobile ? FriendEnum::SOURCE_MOBILE : FriendEnum::SOURCE_WECHAT;
        $friend = User::query()->where($source, $keywords)->whereJsonContains('setting', ["FriendPerm" => ["AddMyWay" => [ucfirst($source) => 1]]])->get(['id', 'nickname', 'avatar']);
        if ($friend) {
            $friend = $friend->toArray();
            foreach ($friend as &$v) {
                $v['keywords'] = $keywords;
            }
        }
        return $friend ?: [];
    }

    /**
     * @throws BusinessException
     */
    public function showConfirm(array $params): array
    {
        $confirm = [];
        $source = $params['source'];
        $relationship = $params['relationship'];
        if ($relationship == 'friend') {
            $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        }
        $user = User::query()->where($source, $params['keywords'])->whereJsonContains('setting', ["FriendPerm" => ["AddMyWay" => [ucfirst($source) => 1]]])->first(['id', 'nickname']);
        if (empty($user)) $this->throwBusinessException(ApiCodeEnum::SERVICE_ACCOUNT_NOT_FOUND);
        $confirm['nickname'] = $user->nickname;
        $confirm['setting'] = config('user.friend.setting');
        if ($relationship !== 'go_check') {
            $confirm['type'] = FriendEnum::TYPE_APPLY;
            $confirm['remark'] = "我是{$params['user']['nickname']}";
        } else {
            $confirm['type'] = FriendEnum::TYPE_VERIFY;
            $confirm['remark'] = '';
        }
        return $confirm;
    }

    public function apply(array $params): array
    {
        //黑名单
        $keywords = $params['keywords'];
        $isMobile = is_mobile($keywords);
        $source = $isMobile ? FriendEnum::SOURCE_MOBILE : FriendEnum::SOURCE_WECHAT;
        $params['friend'] = User::query()->where($source, $keywords)->whereJsonContains('setting', ["FriendPerm" => ["AddMyWay" => [ucfirst($source) => 1]]])->value('id');
        if (!$params['friend']) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);
        $friend = Friend::query()->where('owner', $params['user']->id)->where('friend', $params['friend'])->first();
        $owner = Friend::query()->where('owner', $params['friend'])->where('friend', $params['user']->id)->first();

        if ($friend) {
            //已经申请过了
            //双方已是好友
            if (!$friend->deleted_at && ($owner && !$owner->deleted_at)) {
                throw new BusinessException(ApiCodeEnum::SERVICE_FRIEND_ALREADY_EXISTS);
            }
            $friend->type = FriendEnum::TYPE_APPLY;
            $friend->status = FriendEnum::STATUS_CHECK;
            $friend->deleted_at = null;
            $friend->display = 1;
            $friend->nickname = $params['nickname'];
            $friend->remark = $params['remark'];
            $friend->setting = $params['setting'];
            $friend->is_read = 0;
            //对方有你好友
            if ($owner && !$owner->deleted_at) {
                $friend->type = FriendEnum::TYPE_VERIFY;
                $friend->status = FriendEnum::STATUS_PASS;
            }

            $friend->save();
        } else {
            //没申请过
            $friend = new Friend($params);
            $friend->owner = $params['user']->id;
            $friend->friend = $params['friend'];
            $friend->save();
        }

        $delKeys = [
            sprintf(RedisFriendEnum::LIST, $params['friend']),
            sprintf(RedisFriendEnum::LIST, $params['user']->id),
            sprintf(RedisFriendEnum::APPLY_LIST, $params['friend']),
            sprintf(RedisFriendEnum::APPLY_LIST, $params['user']->id),
        ];
        $this->forgetRememberCache(RedisFriendEnum::STORE, ...$delKeys);
        $apply = $friend->toArray();
        Gateway::sendToUid($params['friend'], json_encode([
            'who' => 'friend',
            'action' => 'apply',
            'data' => $apply
        ], JSON_UNESCAPED_UNICODE));
        return $apply;
    }

    public function verify(array $params)
    {
        DB::beginTransaction();
        try {
            $friend = Friend::query()->where('owner', $params['user']->id)->where('friend', $params['friend'])->first();
            if ($friend) {
                $friend->type = FriendEnum::TYPE_VERIFY;
                $friend->status = FriendEnum::STATUS_PASS;
                $friend->nickname = $params['nickname'];
                $friend->setting = $params['setting'];
                $friend->deleted_at = null;
                $friend->save();
            } else {
                $friend = new Friend($params);
                $friend->type = FriendEnum::TYPE_VERIFY;
                $friend->status = FriendEnum::STATUS_PASS;
                $friend->owner = $params['user']->id;
                $friend->friend = $params['friend'];
                $friend->save();
            }
            Friend::query()->where('owner', $params['friend'])->where('friend', $params['user']->id)->update([
                'type' => FriendEnum::TYPE_VERIFY,
                'status' => FriendEnum::STATUS_PASS
            ]);
            DB::commit();
            $this->delCache($params);
            return $friend->toArray();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
    }

    /**
     * @throws BusinessException
     */
    public function update(array $params): array
    {
        $allowField = ['nickname', 'desc', 'setting', 'unread'];
        $friend = $params['friend'];
        $owner = $params['user']->id;
        $friend = Friend::query()->where('owner', $owner)->where('friend', $friend)->first();
        if (!$friend) $this->throwBusinessException(ApiCodeEnum::CLIENT_PARAMETER_ERROR);

        foreach ($allowField as $field) {
            if (!empty($params[$field])) {
                $friend->$field = $params[$field];
            }
        }
        $friend->save();
        $this->delCache($params);
        return $friend->toArray();
    }

    private function delCache(array $params): void
    {
        $delKeys = [
            sprintf(RedisFriendEnum::LIST, $params['friend']),
            sprintf(RedisFriendEnum::LIST, $params['user']->id),
            sprintf(RedisFriendEnum::APPLY_LIST, $params['friend']),
            sprintf(RedisFriendEnum::APPLY_LIST, $params['user']->id),
        ];
        $this->forgetRememberCache(RedisFriendEnum::STORE, ...$delKeys);
    }
}
