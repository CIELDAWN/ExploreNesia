<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'rating',
        'comment',
        'images',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke User (reviewer)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Destination
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * Relasi ke User yang approve (mitra)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk filter review yang sudah diapprove
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope untuk filter review yang belum diapprove
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan destination
     */
    public function scopeForDestination($query, $destinationId)
    {
        return $query->where('destination_id', $destinationId);
    }

    /**
     * Scope untuk filter berdasarkan rating
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
