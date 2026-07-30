<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'type', 'waarde', 'min_bedrag', 'verloopt_op', 'actief'])]
class DiscountCode extends Model
{
    protected function casts(): array
    {
        return [
            'waarde' => 'decimal:2',
            'min_bedrag' => 'decimal:2',
            'verloopt_op' => 'date',
            'actief' => 'boolean',
        ];
    }

    /**
     * Controleert of de code bruikbaar is voor dit subtotaal.
     * Geeft null terug als alles klopt, anders een Nederlandse foutmelding.
     */
    public function valideer(float $subtotaal): ?string
    {
        if (! $this->actief) {
            return 'Deze kortingscode is niet meer actief.';
        }

        if ($this->verloopt_op && $this->verloopt_op->endOfDay()->isPast()) {
            return 'Deze kortingscode is verlopen.';
        }

        if ($this->min_bedrag && $subtotaal < (float) $this->min_bedrag) {
            return 'Deze code geldt vanaf €'.number_format((float) $this->min_bedrag, 2, ',', '.').' aan producten.';
        }

        return null;
    }

    /**
     * Het kortingsbedrag voor dit subtotaal (nooit meer dan het subtotaal).
     */
    public function kortingVoor(float $subtotaal): float
    {
        $korting = $this->type === 'procent'
            ? round($subtotaal * (float) $this->waarde / 100, 2)
            : (float) $this->waarde;

        return min($korting, $subtotaal);
    }

    /**
     * Korte omschrijving voor in het beheer en de checkout, bijv. "10% korting".
     */
    public function omschrijving(): string
    {
        return $this->type === 'procent'
            ? rtrim(rtrim(number_format((float) $this->waarde, 2, ',', '.'), '0'), ',').'% korting'
            : '€'.number_format((float) $this->waarde, 2, ',', '.').' korting';
    }
}
