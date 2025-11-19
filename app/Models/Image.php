<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['imageable_type', 'imageable_id', 'path', 'caption', 'order', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}