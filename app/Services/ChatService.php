<?php

namespace App\Services;

use App\Enums\Database\MessageEnum;
use App\Models\Friend;
use App\Models\GroupUser;

class ChatService extends BaseService
{
    public function list(array $params): array
    {
        $userId = $params['user']->id;

        //私聊
        $privateChatList = Friend::query()
            ->with(['friend' => function ($query) {
                return $query->select(['id', 'avatar']);
            }])
            ->select(['content', 'time', 'unread', 'top', 'nickname', 'friend'])
            ->where('owner', $userId)
            ->where('display', 1)
            ->get()->toArray();

        foreach ($privateChatList as &$item) {
            $item['to'] = $item['friend'];
            $item['id'] = md5(MessageEnum::PRIVATE . $userId . $item['to']['id']);
            $item['to']['avatars'] = [$item['to']['avatar']];
            $item['from'] = $item['to'];
            $item['to_user'] = $item['friend'];
            $item['is_group'] = MessageEnum::PRIVATE;
            $item['muted'] = false;
            unset($item['to'], $item['from']['avatar'], $item['friend']);
        }
        unset($item);

        //群聊
        $groupChatList = GroupUser::query()
            ->with(['group' => function ($query) {
                return $query->with(['send' => function ($query) {
                    return $query->select(['id', 'nickname']);
                }, 'friend' => function ($query) {
                    return $query->select(['friend', 'nickname']);
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
                return $query->select(['id', 'avatar']);
            }])
            ->where('group_id', $groupIds)
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
            $item['from'] = [
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
                    return $query->select(['id', 'name', 'setting']);
                }])
                ->where('group_id', $toUser)
                ->where('user_id', $fromUser)
                ->first(['group_id', 'user_id', 'name', 'setting'])->toArray();
            $userCnt = GroupUser::query()->where('group_id', $toUser)->count();
            $chatInfo = [
                'nickname' => ($groupUser['group']['name'] ?: $groupUser['name']) . "({$userCnt})",
                'from_setting' => $groupUser['setting'],
                'to_setting' => $groupUser['group']['setting'],
            ];
        } else {
            $friend = Friend::query()
                ->with(['to' => function ($query) {
                    return $query->select(['id', 'nickname', 'setting']);
                }])
                ->where('owner', $fromUser)
                ->where('friend', $toUser)
                ->first(['owner', 'friend', 'nickname', 'setting'])->toArray();
            $chatInfo = [
                'nickname' => $friend['nickname'] ?: $friend['to']['nickname'],
                'from_setting' => $friend['setting'],
                'to_setting' => $friend['to']['setting']
            ];
        }
        return $chatInfo;
    }
}
