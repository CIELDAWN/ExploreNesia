<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'user_id', 'bookable_type', 'bookable_id',
        'booking_date', 'visit_date', 'quantity', 'total_price',
        'discount_amount', 'final_price', 'notes', 'status',
        'confirmed_at', 'cancelled_at', 'cancellation_reason'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'visit_date' => 'date',
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            $booking->booking_code = 'BK-' . strtoupper(uniqid());
        });
    }
}