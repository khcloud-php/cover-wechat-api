<?php

namespace App\Models;

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
}
