<?php

namespace App\Models;

use App\Enums\Database\MomentEnum;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Moment extends Base
{
    use SoftDeletes;

    private static array $columns = ['moments.id', 'user_id', 'type', 'content', 'visible', 'invisible', 'perm', 'moments.created_at'];

    private static string $condition = "((JSON_EXTRACT(cw_u.setting, '$.FriendPerm.Moment.FriendWatchRange') = 'ALLOW_ALL') OR (JSON_EXTRACT(cw_u.setting, '$.FriendPerm.Moment.FriendWatchRange') = 'HALF_YEAR' AND cw_moments.created_at >= UNIX_TIMESTAMP(NOW() - INTERVAL 6 MONTH)) OR (JSON_EXTRACT(cw_u.setting, '$.FriendPerm.Moment.FriendWatchRange') = 'ONE_MONTH' AND cw_moments.created_at >= UNIX_TIMESTAMP(NOW() - INTERVAL 1 MONTH)) OR (JSON_EXTRACT(cw_u.setting, '$.FriendPerm.Moment.FriendWatchRange') = 'THREE_DAYS' AND cw_moments.created_at >= UNIX_TIMESTAMP(NOW() - INTERVAL 3 DAY))) AND ((perm='" . MomentEnum::PUBLIC . "') OR (perm='" . MomentEnum::VISIBLE . "' AND FIND_IN_SET('__OWNER__', visible) != '') OR (perm='" . MomentEnum::INVISIBLE . "' AND FIND_IN_SET('__OWNER__', invisible) = ''))";
    public $timestamps = false;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MomentFiles::class, 'moment_id', 'id');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MomentMessages::class, 'moment_id', 'id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MomentMessages::class, 'moment_id', 'id');
    }

    /**
     * 获取需要通知消息的好友
     * @param int $id
     * @param int $owner
     * @param int $him
     * @param int $to
     * @return array
     */
    public static function getNoticePublicFriendIds(int $id, int $owner, int $him, int $to = 0): array
    {
        //获取共同好友
        $publicFriendIds = Friend::getPublicFriendIds($owner, $him);
        //非本人点赞评论通知朋友圈作者
        if ($owner != $him) $publicFriendIds[] = $him;

        //朋友圈下回复他人评论
        if ($to) {
            $ids = Friend::getPublicFriendIds($owner, $to);
            if ($owner != $him) $ids[] = $him;
            if ($ids) {
                $publicFriendIds = array_intersect($publicFriendIds, $ids);
            }
        }


        if (empty($publicFriendIds)) return [];

        //通知点赞或评论的人
        $userIds = MomentMessages::query()->where('moment_id', $id)->where('from_user', '<>', $owner)->groupBy('from_user')->pluck('from_user')->toArray();
        if ($owner != $him) $userIds[] = $him;

        return array_intersect($publicFriendIds, $userIds);
    }

    /**
     * 自己和好友们的朋友圈列表
     * @param array $userIds
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getMomentsPageByUserIds(array $userIds, int $page = 1, int $limit = 10): array
    {
        $friendIds = $userIds;
        $owner = array_pop($friendIds);
        $friendIdsStr = implode(',', $friendIds);
        $offset = ($page - 1) * $limit;
        $condition = str_replace('__OWNER__', $owner, self::$condition);
        $whereRaw = "(cw_u.id = {$owner} OR (cw_u.id IN($friendIdsStr) AND {$condition}))";
        $total = self::query()->leftJoin('users as u', 'u.id', '=', 'moments.user_id')->whereRaw($whereRaw)->count();
        $moments = self::query()
            ->join('users as u', 'u.id', '=', 'moments.user_id')
            ->with(['user' => function ($query) {
                return $query->select(['id', 'nickname', 'avatar', 'wechat']);
            }, 'files' => function ($query) {
                return $query->with(['file' => function ($query) {
                    return $query->select(['id', 'name', 'type', 'path', 'thumbnail_path']);
                }])->orderBy('created_at', 'asc');
            }, 'likes' => function ($query) use ($userIds) {
                return $query->with(['from' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat']);
                }])->where('type', MomentEnum::LIKE)->whereIn('from_user', $userIds)->orderBy('created_at', 'asc');
            }, 'comments' => function ($query) use ($userIds) {
                return $query->with(['from' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat']);
                }, 'to' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat']);
                }])->where('type', MomentEnum::COMMENT)->whereIn('from_user', $userIds)->orderBy('created_at', 'asc');
            }])
            ->whereRaw($whereRaw)
            ->orderBy('moments.created_at', 'desc')->offset($offset)->limit($limit)
            ->get(self::$columns)->toArray();
        foreach ($moments as &$moment) {
            if (!empty($moment['files'])) {
                foreach ($moment['files'] as &$file) {
                    $file['url'] = $file['file']['path'];
                }
            }
        }
        return [get_page_info($page, $limit, $total), $moments];
    }

    /**
     * 个人朋友圈列表
     * @param array $params
     * @return array
     */
    public static function getMomentsPageByUserId(array $params): array
    {
        $userId = $params['user_id'];
        $owner = $params['user']->id;
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $offset = ($page - 1) * $limit;
        $empty = [get_page_info(1, $limit, 0), []];
        //AI小助手没朋友圈
        if (in_array($userId, get_assistant_ids())) return $empty;

        $query = self::query()->join('users as u', 'u.id', '=', 'moments.user_id')
            ->with(['user' => function ($query) {
                return $query->select(['id', 'nickname', 'avatar', 'wechat']);
            }, 'files' => function ($query) {
                return $query->with(['file' => function ($query) {
                    return $query->select(['id', 'name', 'type', 'path', 'thumbnail_path']);
                }])->orderBy('created_at', 'asc');
            }])->where('u.id', $userId)->orderBy('moments.created_at', 'desc');
        $totalQuery = self::query()->join('users as u', 'u.id', '=', 'moments.user_id')->where('u.id', $userId);
        if ($userId == $owner) {
            //看自己的朋友圈
            $total = $totalQuery->count();
            $moments = $query->offset($offset)->limit($limit)->get(self::$columns)->toArray();
            return [get_page_info($page, $limit, $total), $moments];
        }

        $whereRaw = "(" . str_replace('__OWNER__', $owner, self::$condition) . ")";
        //看别人朋友圈
        if (!Friend::checkIsFriend($owner, $userId)) {
            //陌生人 查看是否开启允许陌生人看十条朋友圈
            if (User::checkExistsBySetting($userId, 'FriendPerm.Moment.AllowStrangerTen', 1)) {
                $moments = $query->whereRaw($whereRaw)->limit(10)->get(self::$columns)->toArray();
                return [get_page_info(1, 10, 10), $moments];
            }
            return $empty;
        }
        if (Friend::checkExistsBySetting($owner, $userId, 'FriendPerm.MomentAndStatus.DontSeeHim', 0) && Friend::checkExistsBySetting($userId, $owner, 'FriendPerm.MomentAndStatus.DontLetHimSeeIt', 0)) {
            $total = $totalQuery->whereRaw($whereRaw)->count();
            $moments = $query->whereRaw($whereRaw)->offset($offset)->limit($limit)->get(self::$columns)->toArray();
            return [get_page_info($page, $limit, $total), $moments];
        }
        return $empty;
    }

    /**
     * 朋友圈详情
     * @param int $id
     * @param array $userIds
     * @return array
     */
    public static function getMomentById(int $id, array $userIds): array
    {
        $friendIds = $userIds;
        $owner = array_pop($friendIds);
        $condition = str_replace('__OWNER__', $owner, self::$condition);
        $whereRaw = "((cw_u.id = {$owner} AND cw_moments.id = {$id}) OR (cw_moments.id = {$id} AND {$condition}))";
        $moment = self::query()
            ->join('users as u', 'u.id', '=', 'moments.user_id')
            ->with(['user' => function ($query) {
                return $query->select(['id', 'nickname', 'avatar', 'wechat']);
            }, 'files' => function ($query) {
                return $query->with(['file' => function ($query) {
                    return $query->select(['id', 'name', 'type', 'path', 'thumbnail_path']);
                }])->orderBy('created_at', 'asc');
            }, 'likes' => function ($query) use ($userIds) {
                return $query->with(['from' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat', 'avatar']);
                }])->where('type', MomentEnum::LIKE)->whereIn('from_user', $userIds)->orderBy('created_at', 'asc');
            }, 'comments' => function ($query) use ($userIds) {
                return $query->with(['from' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat', 'avatar']);
                }, 'to' => function ($query) {
                    return $query->select(['id', 'nickname', 'wechat', 'avatar']);
                }])->where('type', MomentEnum::COMMENT)->whereIn('from_user', $userIds)->orderBy('created_at', 'asc');
            }])
            ->whereRaw($whereRaw)
            ->first(self::$columns);
        $moment = $moment ? $moment->toArray() : [];
        if (!empty($moment['files'])) {
            foreach ($moment['files'] as &$file) {
                $file['url'] = $file['file']['path'];
            }
        }
        return $moment;
    }

    /**
     * 获取朋友圈点赞、评论消息列表
     * @param array $userIds
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getMomentsMessagePageByUserId(array $userIds, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $owner = array_pop($userIds);
        $userIdsStr = implode(',', $userIds);
        $empty = [get_page_info(1, $limit, 0), []];
        $friendMomentIds = [];
        //自己的朋友圈
        $ownerMomentIds = self::query()->where('user_id', $owner)->pluck('id')->toArray();

        if (!empty($userIds)) {
            //获取可见朋友圈
            $condition = str_replace('__OWNER__', $owner, self::$condition);
            $friendWhereRaw = "(cw_u.id IN($userIdsStr) AND {$condition})";
            $friendMomentIds = self::query()->leftJoin('users as u', 'u.id', '=', 'moments.user_id')->whereRaw($friendWhereRaw)->pluck('moments.id')->toArray();
        }
        //可见朋友圈中点赞、评论过的朋友圈
        if ($friendMomentIds) {
            $friendMomentIds = MomentMessages::query()->where('from_user', $owner)
                ->whereIn('moment_id', $friendMomentIds)
                ->groupBy('moment_id')
                ->pluck('moment_id')->toArray();
        }
        if (empty($friendMomentIds) && empty($ownerMomentIds)) {
            return $empty;
        }
        $whereRaw = "";
        if ($ownerMomentIds) {
            $ownerMomentIds = implode(',', $ownerMomentIds);
            $whereRaw = "(moment_id in($ownerMomentIds) and from_user != {$owner})";
        }
        if ($friendMomentIds) {
            $friendMomentIds = implode(',', $friendMomentIds);
            $or = $whereRaw ? ' OR ' : '';
            $whereRaw .= "{$or}(moment_id in($friendMomentIds) and from_user in({$userIdsStr}))";
        }
        $total = MomentMessages::query()->whereRaw($whereRaw)->count();
        $message = MomentMessages::query()->with(['moment' => function ($query) {
            return $query->with(['user' => function ($query) {
                return $query->select(['id', 'nickname', 'avatar', 'wechat']);
            }, 'files' => function ($query) {
                return $query->with(['file' => function ($query) {
                    return $query->select(['id', 'name', 'type', 'path', 'thumbnail_path']);
                }])->orderBy('created_at', 'asc')->limit(1);
            }])->select(['id', 'user_id', 'type', 'content']);
        }, 'from' => function ($query) {
            return $query->select(['id', 'nickname', 'avatar', 'wechat']);
        }, 'to' => function ($query) {
            return $query->select(['id', 'nickname', 'avatar', 'wechat']);
        }])
            ->whereRaw($whereRaw)
            ->orderBy('created_at', 'desc')
            ->offset($offset)->limit($limit)
            ->get()
            ->toArray();
        foreach ($message as &$item) {
            if (date('Y') > date('Y', $item['created_at']))
                $item['created_at'] = date('Y年n月j日 H:i:s', $item['created_at']);
            else
                $item['created_at'] = date('n月j日 H:i:s', $item['created_at']);
        }
        User::clearUnread([$owner], 'moment.num');
        return [get_page_info($page, $limit, $total), $message];
    }
}
