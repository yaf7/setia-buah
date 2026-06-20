<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'qc_report_id', 'procurement_id', 'batch_number',
        'fruit_type', 'grade', 'stock_kg', 'expiry_date', 
        'price_per_kg', 'discount_percent', 'image', 'description', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'stock_kg' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'discount_percent' => 'integer',
            'expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getFinalPriceAttribute()
    {
        if ($this->discount_percent > 0) {
            return $this->price_per_kg * (1 - $this->discount_percent / 100);
        }
        return $this->price_per_kg;
    }

    public function qcReport(): BelongsTo
    {
        return $this->belongsTo(QcReport::class, 'qc_report_id');
    }

    public function procurement(): BelongsTo
    {
        return $this->belongsTo(ProcurementTransaction::class, 'procurement_id');
    }
}