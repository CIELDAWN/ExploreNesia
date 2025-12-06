<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Review;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'city_id', 'name', 'slug', 'description', 'address',
        'cuisine_types', 'average_price_min', 'average_price_max', 
        'opening_time', 'closing_time', 'contact_phone', 'contact_email', 
        'website', 'facilities', 'capacity', 'thumbnail', 'status', 
        'rejection_reason', 'is_active', 'view_count'
    ];

    protected $casts = [
        'cuisine_types' => 'array',
        'facilities' => 'array',
        'average_price_min' => 'decimal:2',
        'average_price_max' => 'decimal:2',
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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function averageRating(): float
    {
        // Rating restoran dihitung dari tabel reviews berdasarkan business_type dan business_name
        return round(
            Review::where('business_type', 'restoran')
                ->where('business_name', $this->name)
                ->avg('rating') ?? 0,
            1
        );
    }

    public function primaryCategory(): ?Category
    {
        $primary = $this->categories()->wherePivot('is_primary', true)->first();

        return $primary ?: $this->categories()->first();
    }
}
