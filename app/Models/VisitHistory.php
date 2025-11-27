<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destination_id',
        'visit_date',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    /**
     * Relasi ke User
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
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk urutkan berdasarkan tanggal terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('visit_date', 'desc');
    }

    /**
     * Scope untuk filter berdasarkan tahun
     */
    public function scopeInYear($query, $year)
    {
        return $query->whereYear('visit_date', $year);
    }
}
