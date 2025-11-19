<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotionable_type', 'promotionable_id', 'title', 'description',
        'discount_type', 'discount_value', 'start_date', 'end_date',
        'max_usage', 'current_usage', 'min_transaction', 'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'min_transaction' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function promotionable()
    {
        return $this->morphTo();
    }

    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->start_date, $this->end_date)
            && ($this->max_usage === null || $this->current_usage < $this->max_usage);
    }
}