<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

#[Fillable(['order_id', 'number'])]
class Invoice extends Model
{
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Volgend factuurnummer, doorlopend per jaar (bijv. F2026-0007).
     */
    public static function volgendNummer(): string
    {
        $jaar = now()->format('Y');
        $aantal = static::where('number', 'like', 'F'.$jaar.'-%')->lockForUpdate()->count();

        return 'F'.$jaar.'-'.str_pad((string) ($aantal + 1), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Maakt een factuur voor de bestelling als die er nog niet is.
     */
    public static function voorBestelling(Order $order): self
    {
        return DB::transaction(function () use ($order) {
            return static::firstOrCreate(
                ['order_id' => $order->id],
                ['number' => static::volgendNummer()],
            );
        });
    }
}
