<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'owner', 'notice_user', 'notice', 'noticed_at', 'setting'];

    use SoftDeletes;

    protected $casts = [
        'setting' => 'json'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GroupUser::class, 'user_id', 'id');
    }
}
