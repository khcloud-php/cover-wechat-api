<?php

namespace App\Models;

class GroupUser extends Base
{
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function isGroupMember($userId, $groupId): bool
    {
        $group = self::query()->where('group_id', $groupId)->where('user_id', $userId)->first();
        return (bool)$group;
    }
}
