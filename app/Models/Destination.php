<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'city_id', 'category_id', 'name', 'slug', 'description',
        'address', 'entrance_fee', 'opening_time',
        'closing_time', 'contact_phone', 'contact_email', 'website',
        'facilities', 'thumbnail', 'status', 'rejection_reason', 'is_active', 'view_count'
    ];

    protected $casts = [
        'facilities' => 'array',
        'entrance_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function visitHistories(): HasMany
    {
        return $this->hasMany(VisitHistory::class);
    }

    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'promotionable');
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('is_approved', true);
    }

    // Helper untuk hitung total favorit
    public function favoritesCount(): int
    {
        return $this->favorites()->count();
    }

    // Helper untuk hitung total kunjungan
    public function visitsCount(): int
    {
        return $this->visitHistories()->count();
    }

    public function averageRating(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    // Helper untuk hitung total review yang approved
    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    // Scope untuk destinasi populer (berdasarkan favorit)
    public function scopePopular($query, $limit = 10)
    {
        return $query->withCount('favorites')
            ->orderBy('favorites_count', 'desc')
            ->limit($limit);
    }

    // Scope untuk destinasi dengan rating tertinggi
    public function scopeTopRated($query, $limit = 10)
    {
        return $query->withAvg('approvedReviews', 'rating')
            ->having('approved_reviews_avg_rating', '>=', 4)
            ->orderBy('approved_reviews_avg_rating', 'desc')
            ->limit($limit);
    }

    // Scope untuk destinasi yang banyak dikunjungi
    public function scopeMostVisited($query, $limit = 10)
    {
        return $query->withCount('visitHistories')
            ->orderBy('visit_histories_count', 'desc')
            ->limit($limit);
    }
}
