<?php

namespace App\Services;

use App\Enums\ApiCodeEnum;
use App\Enums\Database\MessageEnum;
use App\Models\Friend;
use App\Models\GroupUser;
use App\Models\Message;
use Exception;
use Illuminate\Support\Facades\DB;

class ChatService extends BaseService
{
    public function list(array $params): array
    {
        $userId = $params['user']->id;

        //私聊
        $privateChatList = Friend::query()
            ->with(['friend' => function ($query) {
                $query->select(['id', 'avatar']);
            }])
            ->select(['content', 'time', 'unread', 'top', 'nickname', 'friend'])
            ->where('owner', $userId)
            ->where('display', 1)
            ->get()->toArray();

        foreach ($privateChatList as &$item) {
            $item['id'] = md5(MessageEnum::PRIVATE . $userId . $item['friend']['id']);
            $item['friend']['avatars'] = [$item['friend']['avatar']];
            $item['to'] = $item['friend'];
            $item['to_user'] = $item['to']['id'];
            $item['is_group'] = MessageEnum::PRIVATE;
            $item['muted'] = false;
            unset($item['from']['avatar'], $item['friend']);
        }
        unset($item);

        //群聊
        $groupChatList = GroupUser::query()
            ->with(['group' => function ($query) {
                $query->with(['send' => function ($query) {
                    $query->select(['id', 'nickname']);
                }, 'friend' => function ($query) {
                    $query->select(['friend', 'nickname']);
                }])->select(['id', 'content', 'time', 'send_user']);
            }])
            ->select(['unread', 'top', 'group_id', 'user_id', 'name', 'nickname'])
            ->where('user_id', $userId)
            ->where('display', 1)
            ->get()->toArray();

        //群聊头像
        $groupIds = array_column($groupChatList, 'group_id');
        $groupUserList = GroupUser::query()
            ->with(['user' => function ($query) {
                $query->select(['id', 'avatar']);
            }])
            ->whereIn('group_id', $groupIds)
            ->orderByDesc('group_id')
            ->orderByDesc('created_at')
            ->get(['group_id', 'user_id'])->toArray();
        $groupAvatars = [];
        foreach ($groupUserList as $groupUser) {
            //群聊最多四个头像
            if (isset($groupAvatars[$groupUser['group_id']]) && count($groupAvatars[$groupUser['group_id']]) > 3) {
                continue;
            }
            $groupAvatars[$groupUser['group_id']][] = $groupUser['user']['avatar'];
        }
        unset($groupUserList, $groupUser);

        foreach ($groupChatList as &$item) {
            $item['id'] = md5(MessageEnum::GROUP . $userId . $item['group']['id']);
            $nickname = $item['nickname'];
            if (empty($nickname)) {
                $nickname = $item['group']['friend']['nickname'] ?: $item['group']['send']['nickname'];
            }
            $item['nickname'] = $item['name'];
            $item['content'] = $nickname . '：' . $item['group']['content'];
            $item['time'] = $item['group']['time'];
            $item['to_user'] = $item['group_id'];
            $item['is_group'] = MessageEnum::GROUP;
            $item['muted'] = false;
            $item['to'] = [
                'id' => $item['group_id'],
                'avatars' => $groupAvatars[$item['group_id']] ?? []
            ];
            unset($item['send'], $item['group'], $item['group_id'], $item['from_user'], $item['user_id'], $item['name']);
        }

        return array_merge($privateChatList, $groupChatList);
    }

    public function info(array $params): array
    {
        $fromUser = $params['user']->id;
        $isGroup = $params['is_group'];
        $toUser = $params['to_user'];
        if ($isGroup == MessageEnum::GROUP) {
            $groupUser = GroupUser::query()
                ->with(['group' => function ($query) {
                    $query->select(['id', 'name', 'setting']);
                }])
                ->where('group_id', $toUser)
                ->where('user_id', $fromUser)
                ->first(['group_id', 'user_id', 'name', 'unread', 'setting'])->toArray();
            $userCnt = GroupUser::query()->where('group_id', $toUser)->count();
            $chatInfo = [
                'nickname' => ($groupUser['group']['name'] ?: $groupUser['name']) . "({$userCnt})",
                'from_setting' => $groupUser['setting'],
                'to_setting' => $groupUser['group']['setting'],
                'unread' => $groupUser['unread'],
            ];
        } else {
            $friend = Friend::query()
                ->with(['to' => function ($query) {
                    $query->select(['id', 'nickname', 'setting']);
                }])
                ->where('owner', $fromUser)
                ->where('friend', $toUser)
                ->first(['owner', 'friend', 'nickname', 'unread', 'setting'])->toArray();
            $chatInfo = [
                'nickname' => $friend['nickname'] ?: $friend['to']['nickname'],
                'from_setting' => $friend['setting'],
                'to_setting' => $friend['to']['setting'],
                'unread' => $friend['unread'],
            ];
        }
        return $chatInfo;
    }

    public function top(array $params): array
    {
        $isGroup = $params['is_group'];
        $toUser = $params['to_user'];
        $userId = $params['user']->id;
        $isTop = $params['is_top'];
        $time = $isTop > 0 ? time() : 0;
        if ($isGroup == MessageEnum::GROUP) {
            GroupUser::query()
                ->where('group_id', $toUser)
                ->where('user_id', $userId)
                ->update(['top' => $time]);
        } else {
            Friend::query()
                ->where('owner', $userId)
                ->where('friend', $toUser)
                ->update(['top' => $time]);
        }

        return [
            'is_group' => $isGroup,
            'to_user' => $toUser,
            'from_user' => $userId,
            'top' => $time
        ];
    }

    public function hide(array $params): array
    {
        $isGroup = $params['is_group'];
        $toUser = $params['to_user'];
        $userId = $params['user']->id;
        if ($isGroup == MessageEnum::GROUP) {
            GroupUser::query()
                ->where('group_id', $toUser)
                ->where('user_id', $userId)
                ->update(['display' => 0]);
        } else {
            Friend::query()
                ->where('owner', $userId)
                ->where('friend', $toUser)
                ->update(['display' => 0]);
        }

        return [
            'is_group' => $isGroup,
            'to_user' => $toUser,
            'from_user' => $userId
        ];
    }

    public function delete(array $params): array
    {
        $isGroup = $params['is_group'];

        $toUser = $params['to_user'];
        $userId = $params['user']->id;
        DB::beginTransaction();
        try {
            if ($isGroup == MessageEnum::GROUP) {
                DB::update("UPDATE cw_messages SET deleted_users=CONCAT(deleted_users, ',', {$userId}) WHERE (from_user={$userId} AND to_user={$toUser}) AND is_group={$isGroup} AND (FIND_IN_SET({$userId}, deleted_users) = '')");
                GroupUser::query()
                    ->where('group_id', $toUser)
                    ->where('user_id', $userId)
                    ->update(['display' => 0]);
            } else {
                DB::update("UPDATE cw_messages SET deleted_users=CONCAT(deleted_users, ',', {$userId}) WHERE ((from_user={$userId} AND to_user={$toUser}) OR (from_user={$toUser} AND to_user={$userId})) AND is_group={$isGroup} AND (FIND_IN_SET({$userId}, deleted_users) = '')");
                Friend::query()
                    ->where('owner', $userId)
                    ->where('friend', $toUser)
                    ->update(['display' => 0]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->throwBusinessException(ApiCodeEnum::SYSTEM_ERROR, $e->getMessage());
        }


        return [
            'is_group' => $isGroup,
            'to_user' => $toUser,
            'from_user' => $userId
        ];
    }
}
