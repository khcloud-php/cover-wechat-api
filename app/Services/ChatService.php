<?php

namespace App\Services;

use App\Enums\Database\MessageEnum;
use App\Models\Friend;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\User;

class ChatService extends BaseService
{
    public function list(array $params): array
    {
        $userId = $params['user']->id;
        $chatList = [];
        $lastMessages = Message::query()
            ->whereRaw("(from_user = {$userId} OR to_user = {$userId})")
            ->where('is_last', 1)
            ->get(['id', 'from_user', 'to_user', 'content', 'type', 'is_group', 'is_undo', 'at_users', 'pid', 'created_at']);
        if ($lastMessages->isEmpty()) return $chatList;
        $privateTo = $groupTo = [];
        foreach ($lastMessages as $message) {
            if ($message['is_group'] == MessageEnum::GROUP) {
                $groupTo[] = $message['to_user'];
            } else {
                $privateTo[] = $message['from_user'] == $userId ? $message['to_user'] : $message['from_user'];
            }
        }
        $privateTo = array_unique($privateTo);
        $groupTo = array_unique($groupTo);
        $userList = $groupList = [];
        //私聊处理
        if ($privateTo) {
            $unreadList = Message::query()
                ->selectRaw('count(is_read) as unread,from_user')
                ->whereIn('from_user', $privateTo)
                ->where('to_user', $userId)
                ->where('is_read', 0)
                ->groupBy('from_user')
                ->get()
                ->toArray();
            $unreadList = array_column($unreadList, null, 'from_user');
            $userList = User::query()->whereIn('id', $privateTo)->get(['id', 'nickname', 'avatar'])->toArray();
            $friendList = Friend::query()->where('owner', $userId)->whereIn('friend', $privateTo)->get(['friend', 'nickname'])->toArray();
            $friendList = array_column($friendList, null, 'friend');
            $userList = array_column($userList, null, 'id');
            foreach ($userList as $key => &$user) {
                $user['unread'] = 0;
                $user['avatars'] = [$user['avatar']];
                unset($user['avatar'], $user['id']);
                isset($friendList[$key]) && $user['nickname'] = $friendList[$key]['nickname'] ?: $user['nickname'];
                isset($unreadList[$key]) && $user['unread'] = $unreadList[$key]['unread'];

            }
            unset($user);
        }

        //群聊处理
        if ($groupTo) {
            $groupUserList = GroupUser::query()->whereIn('group_id', $groupTo)
                ->where('user_id', $userId)->get(['group_id', 'name', 'unread'])->toArray();
            $groupUserList = array_column($groupUserList, null, 'group_id');
            $groupList = Group::query()->with(['users' => function ($query) {
                $query->with(['user' => function ($query) {
                    $query->select(['avatar']);
                }])->orderBy('role')->orderBy('created_at', 'desc')
                    ->limit(4)->get(['group_id', 'user_id'])->toArray();
            }])->whereIn('id', $groupTo)->get(['id', 'name'])->toArray();
            $groupList = array_column($groupList, null, 'id');
            foreach ($groupList as $key => &$group) {
                $group['unread'] = 0;
                $group['nickname'] = $group['name'];
                if (isset($groupUserList[$key])) {
                    $groupUser = $groupUserList[$key];
                    $group['unread'] = $groupUser['unread'];
                    $group['nickname'] = $groupUser['name'] ?: $group['name'];
                }
                $group['avatars'] = [];
                foreach ($group['users'] as $user) {
                    $group['avatars'][] = $user['user']['avatar'];
                }
                unset($group['users'], $group['id']);
            }
            unset($group);
        }

        foreach ($lastMessages as $message) {
            $item = [
                'id' => $message['id'],
                'owner' => $message['from_user'] == $userId ? $message['from_user'] : $message['to_user'],
                'friend' => $message['from_user'] != $userId ? $message['from_user'] : $message['to_user'],
                'avatars' => [],
                'nickname' => '',
                'content' => $message['content'],
                'unread' => 0,
                'time' => strtotime($message['created_at']),
                'top' => 0,
                'is_group' => $message['is_group'],
                'muted' => false
            ];
            if ($message['is_group'] == MessageEnum::GROUP) {
                isset($groupList[$message['to_user']]) && $item = array_merge($item, $groupList[$message['to_user']]);
            } else {
                isset($userList[$message['to_user']]) && $item = array_merge($item, $userList[$message['to_user']]);
                isset($userList[$message['from_user']]) && $item = array_merge($item, $userList[$message['from_user']]);
            }
            $chatList[] = $item;
        }

        return $chatList;
    }
}
