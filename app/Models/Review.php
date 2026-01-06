<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'student_number',
        'hygiene',
        'management',
        'education_quality',
        'overall_rating',
        'parent_communication',
        'comment',
        'status',
        'approved_at',
        'approved_by',
        'is_reported',
        'report_reason',
        'reported_at',
        'reported_by',
        'moderated_at',
        'moderated_by',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'approved_at'  => 'datetime',
        'is_reported'  => 'boolean',
        'reported_at'  => 'datetime',
        'moderated_at' => 'datetime',
        'verified_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Belongs to a school
    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }

    // Review author (parent user)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Alias for clarity: parent who wrote the review
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Approved by (admin user)
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ✅ Reported by (user who reported the review)
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
