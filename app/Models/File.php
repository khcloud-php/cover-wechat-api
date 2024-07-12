<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Base
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'thumbnail_path',
        'size',
        'width',
        'height',
        'duration',
        'signature',
        'type',
        'format',
    ];
}
