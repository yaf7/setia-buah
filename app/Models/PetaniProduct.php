<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetaniProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fruit_type', 'grade', 'estimated_weight_kg',
        'price_per_kg', 'harvest_date', 'image', 'status'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'estimated_weight_kg' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'harvest_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
