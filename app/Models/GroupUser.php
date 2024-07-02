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

    public static function checkIsGroupMember($userId, $groupId, $returnGroup = false): bool|array
    {
        $group = self::query()
            ->with(['group' => function ($query) {
                $query->select(['id', 'name']);
            }])
            ->where('group_id', $groupId)
            ->where('user_id', $userId)
            ->first(['group_id', 'user_id', 'name'])->toArray();
        return $returnGroup ? [(bool)$group, $group] : (bool)$group;
    }
}
