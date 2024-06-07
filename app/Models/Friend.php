<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['nickname', 'type', 'status', 'unread', 'remark', 'setting'];

     use SoftDeletes;

    protected $casts = [
        'setting' => 'json'
    ];

    public function friend(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'friend', 'id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }
}
