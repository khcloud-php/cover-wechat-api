<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MomentMessages extends Model
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
}
