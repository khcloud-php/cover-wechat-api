<?php

namespace App\Models;

class GroupUser extends Base
{
    protected $casts = [
        'setting' => 'json'
    ];

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }


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
