<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MomentFiles extends Base
{
    use SoftDeletes;

    public $timestamps = false;

    public function file(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
