<?php

namespace App\Models;

use App\Enums\Database\MomentEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class MomentComments extends Base
{
    use SoftDeletes;

    public $timestamps = false;

    public function from(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }

    public function to(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }

    public function moment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Moment::class, 'moment_id', 'id');
    }

    public static function getUnreadCommentsByUserId(int $userId): array
    {
        $unreadComments = self::query()->with(['moment' => function ($query) {
            return $query->with(['files' => function ($query) {
                return $query->orderBy('created_at', 'asc')->limit(1);
            }])->select(['id', 'user_id', 'type', 'content']);
        }, 'from' => function ($query) {
            return $query->select(['id', 'nickname', 'avatar', 'wechat']);
        }, 'to' => function ($query) {
            return $query->select(['id', 'nickname', 'avatar', 'wechat']);
        }])
            ->where('is_read', 'eq', 0)
            ->whereRaw("moment_id IN (SELECT id FROM moments WHERE user_id = {$userId} AND deleted_at IS NULL AND unread > 0)")
            ->get()->toArray();
        foreach ($unreadComments as &$unreadComment) {
            $unreadComment['type'] = MomentEnum::COMMENT;
            unset($unreadComment['deleted_at']);
        }
        return $unreadComments;
    }
}
