<?php

namespace App\Models;

class Message extends Base
{

    protected $casts = [
        'extends' => 'json'
    ];

    public function from(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }
}
