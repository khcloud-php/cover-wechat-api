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

    public function friend(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Friend::class, 'send_user', 'friend');
    }

    public function send(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'send_user', 'id');
    }
}
