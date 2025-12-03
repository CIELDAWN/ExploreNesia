<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
    ];

    /**
     * Boot method untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relasi polymorphic many-to-many ke destinations, hotels, restaurants
     */
    public function destinations(): MorphToMany
    {
        return $this->morphedByMany(Destination::class, 'taggable');
    }

    public function hotels(): MorphToMany
    {
        return $this->morphedByMany(Hotel::class, 'taggable');
    }

    public function restaurants(): MorphToMany
    {
        return $this->morphedByMany(Restaurant::class, 'taggable');
    }

    /**
     * Scope untuk mencari tag berdasarkan nama
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
}

