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
    protected $fillable = ['status','unread','setting'];

    use SoftDeletes;
}
