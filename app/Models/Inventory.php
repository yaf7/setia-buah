<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['fruit_type', 'grade', 'stock_kg', 'expiry_date', 'price_per_kg', 'image', 'description'];

    protected function casts(): array
    {
        return [
            'stock_kg' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'expiry_date' => 'date',
        ];
    }
}