<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SchoolPhoto extends Model
{
    protected $fillable = ['school_id', 'file_path'];

    // ✅ Always include the 'url' in the JSON response for React
    protected $appends = ['url'];

    /**
     * Convert the stored path into a full URL React can use
     * Looks for: $photo->url
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
