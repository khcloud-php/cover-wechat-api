<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public $timestamps = true;
    protected $dateFormat = 'U';
}
