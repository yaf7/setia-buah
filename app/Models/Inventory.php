<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'qc_report_id', 'procurement_id', 'batch_number',
        'fruit_type', 'grade', 'stock_kg', 'expiry_date', 
        'price_per_kg', 'image', 'description', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'stock_kg' => 'decimal:2',
            'price_per_kg' => 'decimal:2',
            'expiry_date' => 'date',
            'is_active' => 'boolean',
        ];
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