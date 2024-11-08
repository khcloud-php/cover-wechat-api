<?php

namespace App\Models;

use App\Enums\Database\MomentEnum;

class MomentLikes extends Base
{
    public $timestamps = false;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function moment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Moment::class, 'moment_id', 'id');
    }

    public static function getUnreadLikesByUserId(int $userId): array
    {
        $unreadLikes = self::query()->with(['moment' => function ($query) {
            return $query->with(['files' => function ($query) {
                return $query->orderBy('created_at', 'asc')->limit(1);
            }])->select(['id', 'user_id', 'type', 'content']);
        }, 'user' => function ($query) {
            return $query->select(['id', 'nickname', 'avatar', 'wechat']);
        }])
            ->whereRaw("moment_id IN (SELECT id FROM moments WHERE user_id = {$userId} AND deleted_at IS NULL AND unread > 0)")
            ->get()->toArray();
        foreach ($unreadLikes as &$unreadLike) {
            $unreadLike['to'] = [];
            $unreadLike['from'] = $unreadLike['user'];
            $unreadLike['content'] = '';
            $unreadLike['type'] = MomentEnum::LIKE;
            unset($unreadLike['user']);
        }
        return $unreadLikes;
    }
}
