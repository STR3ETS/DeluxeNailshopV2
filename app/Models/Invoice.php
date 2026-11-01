<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Een factuur hoort bij een webshopbestelling (order_id) of is handmatig
 * aangemaakt in het beheer (order_id = null, met eigen klantgegevens en
 * regels). Beide soorten delen dezelfde doorlopende nummering.
 */
#[Fillable([
    'order_id', 'number', 'name', 'company', 'email', 'address', 'postcode',
    'city', 'country', 'vat_number', 'lines', 'total', 'paid', 'note',
])]
class Invoice extends Model
{
    protected function casts(): array
    {
        return [
            'lines' => 'array',
            'total' => 'decimal:2',
            'paid'  => 'boolean',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isHandmatig(): bool
    {
        return $this->order_id === null;
    }

    /**
     * Volgend factuurnummer, doorlopend per jaar (bijv. F2026-0007).
     * Gebaseerd op het hoogste nummer, zodat er nooit een dubbel nummer ontstaat.
     */
    public static function volgendNummer(bool $vergrendel = true): string
    {
        $jaar = now()->format('Y');

        $query = static::where('number', 'like', 'F'.$jaar.'-%');
        if ($vergrendel) {
            $query->lockForUpdate();
        }

        $hoogste = $query->pluck('number')
            ->map(fn ($nummer) => (int) substr($nummer, strlen('F'.$jaar.'-')))
            ->max() ?? 0;

        return 'F'.$jaar.'-'.str_pad((string) ($hoogste + 1), 4, '0', STR_PAD_LEFT);
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

    /**
     * Maakt een handmatige factuur met het volgende vrije nummer.
     */
    public static function handmatig(array $gegevens): self
    {
        return DB::transaction(function () use ($gegevens) {
            return static::create($gegevens + ['number' => static::volgendNummer()]);
        });
    }

    public function klantNaam(): string
    {
        if ($this->isHandmatig()) {
            return $this->company ?: (string) $this->name;
        }

        return (string) $this->order?->name;
    }

    public function totaalBedrag(): float
    {
        return (float) ($this->isHandmatig() ? $this->total : $this->order?->total);
    }

    public function aantalArtikelen(): int
    {
        if ($this->isHandmatig()) {
            return (int) collect($this->lines)->sum('qty');
        }

        return (int) ($this->order?->artikelen ?? $this->order?->items->sum('qty') ?? 0);
    }

    /**
     * Alles wat de PDF-factuur nodig heeft, voor beide soorten facturen.
     * Prijzen zijn inclusief 21% BTW; op de factuur splitsen we dat uit.
     */
    public function pdfGegevens(): array
    {
        $landen = ['NL' => 'Nederland', 'BE' => 'België'];

        if ($this->isHandmatig()) {
            $regels = collect($this->lines)->map(fn ($r) => [
                'naam'   => $r['description'],
                'aantal' => (int) $r['qty'],
                'prijs'  => (float) $r['price'],
            ]);
            $totaal = (float) $this->total;

            return [
                'klant' => array_values(array_filter([
                    $this->company ? '<b>'.e($this->company).'</b>' : null,
                    $this->company ? ($this->name ? e($this->name) : null) : '<b>'.e($this->name).'</b>',
                    $this->address ? e($this->address) : null,
                    trim($this->postcode.' '.$this->city) !== '' ? e(trim($this->postcode.' '.$this->city)) : null,
                    $this->country ? e($landen[$this->country] ?? $this->country) : null,
                    $this->email ? e($this->email) : null,
                    $this->vat_number ? 'BTW: '.e($this->vat_number) : null,
                ])),
                'details' => [
                    'label' => 'Factuurgegevens',
                    'regels' => [
                        'Factuurnummer: <b>'.e($this->number).'</b>',
                        'Status: '.($this->paid ? 'Betaald' : 'Nog te betalen'),
                    ],
                ],
                'regels'      => $regels,
                'subtotaal'   => $totaal,
                'korting'     => null,
                'verzending'  => null,
                'totaal'      => $totaal,
                'btw'         => $totaal * 21 / 121,
                'opmerking'   => $this->note,
                'betaald'     => $this->paid,
            ];
        }

        $order = $this->order;
        $order->loadMissing('items');

        return [
            'klant' => array_values(array_filter([
                '<b>'.e($order->name).'</b>',
                $order->address ? e($order->address) : null,
                $order->address ? e($order->postcode.' '.$order->city) : null,
                $order->address ? e($landen[$order->country] ?? $order->country) : null,
                e($order->email),
            ])),
            'details' => [
                'label' => 'Bestelling',
                'regels' => [
                    'Bestelnummer: <b>'.e($order->nummer()).'</b>',
                    'Besteldatum: '.e($order->created_at->translatedFormat('j F Y')),
                    'Levering: '.($order->levering === 'afhalen' ? 'Afhalen' : 'Bezorgen'),
                    'Betaalwijze: '.($order->mollie_payment_id ? 'Mollie (online)' : 'Handmatig'),
                ],
            ],
            'regels' => $order->items->map(fn ($r) => [
                'naam'   => $r->name,
                'aantal' => (int) $r->qty,
                'prijs'  => (float) $r->price,
            ]),
            'subtotaal'  => (float) $order->total - (float) $order->shipping + (float) $order->discount,
            'korting'    => (float) $order->discount > 0 ? ['code' => $order->discount_code, 'bedrag' => (float) $order->discount] : null,
            'verzending' => ['label' => $order->levering === 'afhalen' ? 'Afhalen' : 'Verzendkosten', 'bedrag' => (float) $order->shipping],
            'totaal'     => (float) $order->total,
            'btw'        => (float) $order->total * 21 / 121,
            'opmerking'  => null,
            'betaald'    => true,
        ];
    }
}
