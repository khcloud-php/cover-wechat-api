<?php

namespace App\Models;

class MomentFiles extends Base
{
    public $timestamps = false;

    public function file(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
