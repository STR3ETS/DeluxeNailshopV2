<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'type', 'waarde', 'min_bedrag', 'verloopt_op', 'actief', 'max_gebruik'])]
class DiscountCode extends Model
{
    public function isOpgebruikt(): bool
    {
        return $this->max_gebruik !== null && $this->gebruikt >= $this->max_gebruik;
    }

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
     * Geen korting op korting: een kortingscode geldt alleen voor producten
     * die niet in de sale staan. Geeft het bedrag terug waarover de code
     * berekend wordt, op basis van regels met 'price', 'old_price' en 'qty'.
     */
    public static function kortingsbasis(iterable $regels): float
    {
        $basis = 0.0;

        foreach ($regels as $regel) {
            if (empty($regel['old_price'])) {
                $basis += (float) $regel['price'] * (int) $regel['qty'];
            }
        }

        return round($basis, 2);
    }

    /**
     * Controleert of de code bruikbaar is voor dit subtotaal (exclusief
     * sale-producten). Geeft null terug als alles klopt, anders een
     * Nederlandse foutmelding. $metSale: er liggen ook sale-producten in de
     * winkelwagen, die niet meetellen.
     */
    public function valideer(float $subtotaal, bool $metSale = false): ?string
    {
        if (! $this->actief) {
            return 'Deze kortingscode is niet meer actief.';
        }

        if ($this->verloopt_op && $this->verloopt_op->endOfDay()->isPast()) {
            return 'Deze kortingscode is verlopen.';
        }

        if ($this->isOpgebruikt()) {
            return 'Deze kortingscode is al gebruikt.';
        }

        if ($metSale && $subtotaal <= 0) {
            return 'Kortingscodes gelden niet voor producten in de sale.';
        }

        if ($this->min_bedrag && $subtotaal < (float) $this->min_bedrag) {
            return 'Deze code geldt vanaf €'.number_format((float) $this->min_bedrag, 2, ',', '.').' aan producten'
                .($metSale ? ' (sale-producten tellen niet mee).' : '.');
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
