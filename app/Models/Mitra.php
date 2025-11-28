<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mitra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_description',
        'business_address',
        'province_id',
        'city_id',
        'contact_phone',
        'contact_email',
        'website',
        'thumbnail',
        'status',
        'rejection_reason',
        'average_rating',
        'total_reviews'
    ];

    protected $casts = [
        'average_rating' => 'decimal:1',
        'total_reviews' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Helper methods
    public function getBusinessTypeIcon()
    {
        return match($this->business_type) {
            'hotel' => 'fas fa-hotel',
            'restoran' => 'fas fa-utensils',
            'wisata' => 'fas fa-map-marked-alt',
            default => 'fas fa-building'
        };
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getBusinessTypeLabel()
    {
        return match($this->business_type) {
            'hotel' => 'Hotel',
            'restoran' => 'Restoran',
            'wisata' => 'Wisata',
            default => 'Bisnis'
        };
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('business_type', $type);
    }
}