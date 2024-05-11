<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExceptionLog extends Model
{
    protected $fillable = ['env', 'project', 'url', 'request_ip', 'request_id', 'exception'];

    public $timestamps = false;
}
