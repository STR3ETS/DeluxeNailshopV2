<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id', 'name', 'email', 'phone', 'address', 'postcode', 'city',
    'country', 'note', 'total', 'shipping', 'status', 'mollie_payment_id',
])]
class Order extends Model
{
    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'shipping' => 'decimal:2',
        ];
    }

    /**
     * Leesbaar bestelnummer, bijv. DN-1024.
     */
    public function nummer(): string
    {
        return 'DN-'.str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function factuur(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Zorgt dat er een factuur bestaat voor deze bestelling.
     */
    public function maakFactuur(): Invoice
    {
        return Invoice::voorBestelling($this);
    }
}
