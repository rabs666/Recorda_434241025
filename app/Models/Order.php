<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'code', 'user_id', 'customer_name', 'total', 'status',
    ];

    protected $casts = [
        'total' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'selesai' => 'is-success',
            'diproses' => 'is-warning',
            'dikirim' => 'is-info',
            'dibatalkan' => 'is-danger',
            default => '',
        };
    }

    /**
     * Ringkasan item: "Abbey Road (1), Rumours (2)".
     */
    public function itemsSummary(): string
    {
        return $this->items
            ->map(fn ($item) => $item->product_name . ' (' . $item->qty . ')')
            ->implode(', ');
    }
}
