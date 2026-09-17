<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['email', 'discount_code_id'])]
class NewsletterSubscriber extends Model
{
    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    /**
     * Schrijft een e-mailadres in en geeft het een persoonlijke, eenmalig
     * bruikbare kortingscode. Opnieuw inschrijven levert geen nieuwe code op.
     */
    public static function inschrijven(string $email): self
    {
        $inschrijving = static::firstOrCreate(['email' => mb_strtolower(trim($email))]);

        if (! $inschrijving->discount_code_id) {
            $code = DiscountCode::create([
                'code'        => static::nieuweCode(),
                'type'        => 'procent',
                'waarde'      => config('shop.nieuwsbrief.korting_procent'),
                'max_gebruik' => 1,
                'actief'      => true,
            ]);

            $inschrijving->update(['discount_code_id' => $code->id]);
        }

        return $inschrijving;
    }

    public static function voorEmail(string $email): ?self
    {
        return static::with('discountCode')->where('email', mb_strtolower(trim($email)))->first();
    }

    /**
     * Unieke code zoals WELKOM-7K3QXP (zonder verwarrende tekens als 0/O en 1/I).
     */
    private static function nieuweCode(): string
    {
        do {
            $code = 'WELKOM-'.collect(range(1, 6))
                ->map(fn () => Str::substr('ABCDEFGHJKLMNPQRSTUVWXYZ23456789', random_int(0, 31), 1))
                ->implode('');
        } while (DiscountCode::where('code', $code)->exists());

        return $code;
    }
}
