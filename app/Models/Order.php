<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'delivery_type',
        'payment_method',
        'snap_token',
        'payment_channel',
        'subtotal',
        'delivery_fee',
        'platform_fee',
        'voucher_code',
        'discount_amount',
        'total',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'delivery_fee' => 'float',
        'platform_fee' => 'float',
        'discount_amount' => 'float',
        'total' => 'float',
        'paid_at' => 'datetime',
    ];

    protected $appends = [
        'status_label',
        'status_badge_class',
        'payment_label',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Indonesian Status Labels:
     * Dipesan (pending), Diproses (processing), Diantar (on_delivery), Selesai (completed), Dibatalkan (cancelled)
     */
    public function getStatusLabelAttribute(): string
    {
        return match (strtolower($this->status)) {
            'pending', 'dipesan' => 'Dipesan',
            'processing', 'diproses' => 'Diproses',
            'on_delivery', 'diantar' => 'Diantar',
            'completed', 'selesai' => 'Selesai',
            'cancelled', 'batal' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentLabelAttribute(): string
    {
        if (in_array(strtolower($this->payment_method), ['cash', 'cod', 'tunai'])) {
            return 'Tunai (COD)';
        }
        return !empty($this->payment_channel) ? 'Transfer (' . $this->payment_channel . ')' : 'Transfer / Digital';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match (strtolower($this->status)) {
            'pending', 'dipesan' => 'bg-amber-100 text-amber-800 border-amber-200',
            'processing', 'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
            'on_delivery', 'diantar' => 'bg-purple-100 text-purple-800 border-purple-200',
            'completed', 'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'cancelled', 'batal' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
