<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'icon'];

    public function destinations()
    {
        return $this->morphedByMany(Destination::class, 'categorizable');
    }

    public function hotels()
    {
        return $this->morphedByMany(Hotel::class, 'categorizable');
    }

    public function restaurants()
    {
        return $this->morphedByMany(Restaurant::class, 'categorizable');
    }
}