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

    public function getPathAttribute($value): string
    {
        if (empty($value)) return '';
        return env('STATIC_FILE_URL') . '/' . $value;
    }

    public function getThumbnailPathAttribute($value): string
    {
        if (empty($value)) return '';
        return env('STATIC_FILE_URL') . '/' . $value;
    }
}
