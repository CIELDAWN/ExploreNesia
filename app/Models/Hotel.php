<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'city_id', 'name', 'slug', 'description', 'address',
        'latitude', 'longitude', 'star_rating', 'price_per_night_min',
        'price_per_night_max', 'contact_phone', 'contact_email', 'website',
        'facilities', 'total_rooms', 'thumbnail', 'status', 'rejection_reason',
        'is_active', 'view_count'
    ];

    protected $casts = [
        'facilities' => 'array',
        'price_per_night_min' => 'decimal:2',
        'price_per_night_max' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function bookings()
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function promotions()
    {
        return $this->morphMany(Promotion::class, 'promotionable');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
}
