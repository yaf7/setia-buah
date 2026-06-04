<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProcurementTransaction extends Model
{
    protected $fillable = [
        'petani_product_id', 'admin_id', 'procurement_number',
        'agreed_price_per_kg', 'agreed_weight_kg', 'total_cost',
        'procurement_date', 'pickup_method', 'status', 'notes'
    ];

    protected function casts(): array
    {
        return [
            'agreed_price_per_kg' => 'decimal:2',
            'agreed_weight_kg' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'procurement_date' => 'date',
        ];
    }

    public function harvestEstimate(): BelongsTo
    {
        return $this->belongsTo(PetaniProduct::class, 'petani_product_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function qcReport(): HasOne
    {
        return $this->hasOne(QcReport::class, 'procurement_id');
    }

    /**
     * Generate a unique procurement number.
     */
    public static function generateNumber(): string
    {
        $prefix = 'PRC';
        $date = now()->format('Ymd');
        $last = static::where('procurement_number', 'like', "{$prefix}-{$date}-%")
            ->orderByDesc('id')
            ->first();

        if ($last) {
            $lastSeq = (int) substr($last->procurement_number, -4);
            $nextSeq = str_pad($lastSeq + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextSeq = '0001';
        }

        return "{$prefix}-{$date}-{$nextSeq}";
    }
}
