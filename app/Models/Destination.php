<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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