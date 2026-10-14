<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'vouchers';

    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_discount',
        'quota',
        'used_count',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'float',
        'min_spend' => 'float',
        'max_discount' => 'float',
        'quota' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'valid_until' => 'date',
    ];

    /**
     * Check if voucher is valid for given subtotal
     */
    public function checkValidity(float $subtotal): array
    {
        if (!$this->is_active) {
            return [
                'valid' => false,
                'message' => 'Voucher sedang tidak aktif atau dinonaktifkan.',
            ];
        }

        if ($this->valid_until && now()->gt($this->valid_until->endOfDay())) {
            return [
                'valid' => false,
                'message' => 'Masa berlaku voucher sudah berakhir pada ' . $this->valid_until->format('d M Y') . '.',
            ];
        }

        if ($this->quota > 0 && $this->used_count >= $this->quota) {
            return [
                'valid' => false,
                'message' => 'Kuota pemakaian voucher ini sudah habis.',
            ];
        }

        if ($subtotal < $this->min_spend) {
            return [
                'valid' => false,
                'message' => 'Minimal belanja untuk voucher ini adalah Rp ' . number_format($this->min_spend, 0, ',', '.') . '.',
            ];
        }

        return [
            'valid' => true,
            'message' => 'Voucher berhasil diterapkan!',
        ];
    }

    /**
     * Calculate discount amount for given subtotal
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($subtotal * $this->discount_value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return min($subtotal, round($discount));
        }

        // Fixed nominal
        return min($subtotal, $this->discount_value);
    }
}
