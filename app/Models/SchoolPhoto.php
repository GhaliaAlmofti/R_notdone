<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolPhoto extends Model
{
    protected $fillable = [
        'school_id',
        'path',
        'caption',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }


}
