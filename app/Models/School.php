<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage; // Added for URL generation

class School extends Model
{
    // ✅ 1. Tell Laravel to always include the 'logo' and 'avg_rating' in JSON
    protected $appends = ['logo'];

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'email',
        'phone',
        'address',
        'area',
        'category',
        'level',
        'president_name',
        'fees_range',
        'gender_type',
        'curriculum',
        'admin_user_id',
    ];

    /**
     * ✅ 2. The "Logo Bridge" for React
     * This creates a $school->logo property that returns a full URL.
     */
    public function getLogoAttribute(): string
    {
        if ($this->logo_path) {
            return asset('storage/' . $this->logo_path);
        }

        // Return a placeholder if no logo exists
        return asset('images/default-school-logo.png');
    }

    /**
     * Use slug for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * School admin (User)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    /**
     * All reviews for this school
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Approved reviews only
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)
            ->where('status', 'approved');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SchoolPhoto::class);
    }

    /**
     * Parents who reviewed this school
     */
    public function reviewers(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Review::class,
            'school_id',
            'id',
            'id',
            'user_id'
        );
    }
}
