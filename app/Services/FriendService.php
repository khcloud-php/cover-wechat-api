<?php

namespace App\Services;

use App\Enums\Database\FriendEnum;
use App\Models\Friend;
use App\Models\User;
use App\Enums\ApiCodeEnum;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;

class FriendService extends BaseService
{
    public function list(array $params): array
    {
        $friendList = Friend::query()->with(['friend' => function ($query) {
            $query->select('id', 'nickname', 'avatar');
        }])->where('owner', $params['user']->id)->where('type', FriendEnum::TYPE_VERIFY)->where('status', FriendEnum::STATUS_PASS)->get();
        $friendList = $friendList ? $friendList->toArray() : [];
        foreach ($friendList as &$friend) {
            $friend['friend']['nickname'] = $friend['nickname'] ?: $friend['friend']['nickname'];
        }
        return group_by_first_char($friendList, 'nickname');
    }

    public function applyList(array $params): array
    {
        $applyList = Friend::query()->with(['friend' => function ($query) {
            $query->select('id', 'nickname', 'avatar');
        }])->where('owner', $params['user']->id)->where('display', 1)->get();
        $applyList = $applyList ? $applyList->toArray() : [];
        $day = 86400;
        $threeDay = $overThreeDay = [];
        foreach ($applyList as &$apply) {
            $apply['friend']['nickname'] = $apply['nickname'] ?: $apply['friend']['nickname'];
            $field = $apply['updated_at'] ? 'updated_at' : 'created_at';
            $days = (time() - strtotime($apply[$field])) / $day;
            if ($days > 3) {
                $overThreeDay[] = $apply;
            } else {
                $threeDay[] = $apply;
            }
        }
        return ['three_day' => $threeDay, 'over_three_day' => $overThreeDay];
    }

    public function deleteApply($id): int
    {
        return Friend::query()->where('id', $id)->update(['display' => 0]);
    }

    public function search(array $params): array
    {
        $keywords = $params['keywords'];
        $userId = $params['user']->id;
        //黑名单

        $isMobile = is_mobile($keywords);
        if ($isMobile) {
            $friend = User::query()->where('mobile', $keywords)->whereJsonContains('setting', ["FriendPerm" => ["AddMyWay" => ['Mobile' => 1]]])->get();
        } else {
            $friend = User::query()->where('wechat', $keywords)->whereJsonContains('setting', ["FriendPerm" => ["AddMyWay" => ['Wechat' => 1]]])->get();
        }
        if ($friend) {
            $friend = $friend->toArray();
            foreach ($friend as &$v) {
                $v['source'] = $isMobile ? 'mobile' : 'wechat';
                unset($v['token'], $v['token_expire_in']);
            }
        }
        return $friend ?: [];
    }

    public function apply(array $params): array
    {
        //黑名单

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

        return $friend->toArray();
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
                $friend->remark = $params['remark'];
                $friend->setting = $params['setting'];
                $friend->deleted_at = null;
                $friend->save();
            } else {
                $friend = new Friend($params);
                $friend->owner = $params['user']->id;
                $friend->friend = $params['friend'];
                $friend->save();
            }
            Friend::query()->where('owner', $params['friend'])->where('friend', $params['user']->id)->update([
                'type' => FriendEnum::TYPE_VERIFY,
                'status' => FriendEnum::STATUS_PASS
            ]);
            DB::commit();
            return $friend->toArray();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }
    }
}
