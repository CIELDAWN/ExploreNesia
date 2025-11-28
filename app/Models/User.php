<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'avatar', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships

    // Legacy relationships (akan dihapus nanti)
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
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

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMitra()
    {
        return $this->role === 'mitra';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Relationship untuk mitra
    public function mitra()
    {
        return $this->hasOne(Mitra::class);
    }

    // Helper untuk cek apakah user sudah favorit destinasi tertentu
    public function hasFavorited($destinationId): bool
    {
        return $this->favorites()
            ->where('destination_id', $destinationId)
            ->exists();
    }

    // Helper untuk cek apakah user sudah review destinasi tertentu
    public function hasReviewed($destinationId): bool
    {
        return $this->reviews()
            ->where('destination_id', $destinationId)
            ->exists();
    }

    // Helper untuk cek apakah user sudah mengunjungi destinasi tertentu
    public function hasVisited($destinationId): bool
    {
        return $this->visitHistories()
            ->where('destination_id', $destinationId)
            ->exists();
    }
}
