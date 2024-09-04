<?php

namespace App\Models;

use App\Enums\Database\MomentEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class Moment extends Base
{
    use SoftDeletes;

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
        return $this->hasMany(MomentLikes::class, 'moment_id', 'id');
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MomentComments::class, 'moment_id', 'id');
    }

    public static function getMomentsByUserIds(array $friendIds, string| int $owner): array
    {
        $friendIdsStr = implode(',', $friendIds);
        $moments = self::query()
            ->with(['user' => function ($query) {
                return $query->select(['id', 'nickname', 'avatar']);
            }, 'files' => function ($query) {
                return $query->with(['file' => function ($query) {
                    return $query->select(['id', 'name', 'type', 'path', 'thumbnail_path']);
                }])->orderBy('created_at', 'asc');
            }, 'likes' => function ($query) {
                return $query->with(['user' => function ($query) {
                    return $query->select(['id', 'nickname']);
                }])->orderBy('created_at', 'asc');
            }, 'comments' => function ($query) {
                return $query->with(['from' => function ($query) {
                    return $query->select(['id', 'nickname']);
                }, 'to' => function ($query) {
                    return $query->select(['id', 'nickname']);
                }])->orderBy('created_at', 'asc');
            }])
            ->whereRaw("(user_id = {$owner} OR (user_id IN($friendIdsStr) AND ((perm='" . MomentEnum::PUBLIC . "') OR (perm='" . MomentEnum::VISIBLE . "' AND FIND_IN_SET('{$owner}', visible) != '') OR (perm='" . MomentEnum::INVISIBLE . "' AND FIND_IN_SET('{$owner}', invisible) = ''))))")
            ->get()->toArray();
        foreach ($moments as &$moment) {
            if(!empty($moment['files'])) {
                foreach ($moment['files'] as &$file) {
                    $file['url'] = $file['file']['path'];
                }
            }
        }
        return $moments;
    }
}
