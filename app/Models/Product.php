<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'brand', 'name', 'slug', 'category', 'subcategory', 'price', 'old_price',
    'badge', 'badge_gold', 'bg_from', 'bg_to', 'image', 'image_2', 'description',
    'kenmerken', 'gebruiksaanwijzing', 'inhoud', 'voorzorg', 'bewaren',
    'reviews', 'voorraad', 'actief',
])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'badge_gold' => 'boolean',
            'actief' => 'boolean',
            'kenmerken' => 'array',
            'gebruiksaanwijzing' => 'array',
        ];
    }

    /**
     * De vier bestsellers: meest verkocht in de afgelopen 30 dagen.
     * Zolang er nog geen verkopen zijn, vallen we terug op de vier
     * best beoordeelde actieve producten.
     */
    public static function bestsellerIds(): array
    {
        $meestVerkocht = OrderItem::whereHas('order', fn ($q) => $q
                ->whereIn('status', ['betaald', 'verzonden', 'afgerond'])
                ->where('created_at', '>=', now()->subDays(30)))
            ->selectRaw('product_slug, SUM(qty) as totaal')
            ->groupBy('product_slug')
            ->orderByDesc('totaal')
            ->take(4)
            ->pluck('product_slug');

        $ids = static::whereIn('slug', $meestVerkocht)
            ->get()
            ->sortBy(fn ($p) => array_search($p->slug, $meestVerkocht->all()))
            ->pluck('id')
            ->values()
            ->all();

        // Minder dan vier verkocht? Vul aan met de best beoordeelde producten
        if (count($ids) < 4) {
            $aanvulling = static::where('actief', true)
                ->whereNotIn('id', $ids)
                ->orderByDesc('reviews')
                ->take(4 - count($ids))
                ->pluck('id')
                ->all();

            $ids = array_merge($ids, $aanvulling);
        }

        return $ids;
    }

    /**
     * De array-vorm die de shop-views (productkaart, detailpagina) verwachten.
     * Badges zijn automatisch: 'Bestseller' voor de top-verkopers, en de
     * productkaart hangt zelf 'Sale' aan producten met een oude prijs.
     */
    public function toCardArray(bool $isBestseller = false): array
    {
        return [
            'id'                 => $this->id,
            'slug'               => $this->slug,
            'brand'              => $this->brand,
            'name'               => $this->name,
            'reviews'            => (int) $this->reviews,
            'price'              => (float) $this->price,
            'old_price'          => $this->old_price !== null ? (float) $this->old_price : null,
            'badge'              => $isBestseller ? 'Bestseller' : null,
            'badge_gold'         => $isBestseller,
            'category'           => $this->category,
            'subcategory'        => $this->subcategory ?? '',
            'bg'                 => [$this->bg_from ?? '#f6e3de', $this->bg_to ?? '#ecc9bf'],
            'image'              => $this->image,
            'image_2'            => $this->image_2,
            'voorraad'           => (int) $this->voorraad,
            'description'        => $this->description,
            'kenmerken'          => $this->kenmerken,
            'gebruiksaanwijzing' => $this->gebruiksaanwijzing,
            'inhoud'             => $this->inhoud,
            'voorzorg'           => $this->voorzorg,
            'bewaren'            => $this->bewaren,
        ];
    }
}
