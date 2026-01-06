<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class School extends Model
{
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
     * ✅ School admin (User)
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

    /**
     * Parents who reviewed this school (through reviews)
     */
    public function reviewers(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            Review::class,
            'school_id', // FK on reviews table
            'id',        // FK on users table
            'id',        // Local key on schools
            'user_id'    // Local key on reviews
        );
    }

    /**
     * Use slug for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    
    public function photos()
    {
        return $this->hasMany(\App\Models\SchoolPhoto::class);
    }
}
