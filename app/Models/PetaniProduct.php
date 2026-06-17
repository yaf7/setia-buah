<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PetaniProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'fruit_type', 'grade', 'estimated_weight_kg',
        'price_per_kg', 'harvest_date', 'image', 'description', 'status'
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

    public function procurement(): HasOne
    {
        return $this->hasOne(ProcurementTransaction::class, 'petani_product_id');
    }

    public function qcReport(): HasOne
    {
        return $this->hasOne(QcReport::class, 'petani_product_id');
    }
    
    public function qcReports()
    {
        return $this->hasMany(QcReport::class, 'petani_product_id');
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui Admin',
            'procurement' => 'Transaksi Pengadaan',
            'shipping' => 'Dalam Pengiriman',
            'received' => 'Diterima Gudang',
            'qc_passed' => 'Lolos QC',
            'cataloged' => 'Masuk Katalog',
            'rejected' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get status color for UI badges.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'amber',
            'approved' => 'blue',
            'procurement' => 'indigo',
            'shipping' => 'purple',
            'received' => 'cyan',
            'qc_passed' => 'emerald',
            'cataloged' => 'green',
            'rejected' => 'rose',
            default => 'gray',
        };
    }
}
