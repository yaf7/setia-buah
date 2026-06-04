<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QcReport extends Model
{
    protected $fillable = [
        'petani_product_id', 'admin_id', 'actual_weight_kg', 
        'final_grade', 'final_price_per_kg', 'status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'actual_weight_kg' => 'decimal:2',
            'final_price_per_kg' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(PetaniProduct::class, 'petani_product_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}