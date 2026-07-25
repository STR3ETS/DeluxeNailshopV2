<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Zet de demo-producten uit config/products.php in de database,
     * met dezelfde roulerende foto's en tinten als voorheen op de site.
     */
    public function run(): void
    {
        $images = config('shop.temp_images');
        $tints = config('shop.card_tints');

        foreach (config('products') as $i => $p) {
            $bg = $p['bg'] ?? $tints[$i % count($tints)];

            Product::updateOrCreate(
                ['slug' => Str::slug($p['brand'].' '.$p['name'])],
                [
                    'brand'              => $p['brand'],
                    'name'               => $p['name'],
                    'category'           => $p['category'],
                    'subcategory'        => $p['subcategory'] ?: null,
                    'price'              => $p['price'],
                    'old_price'          => $p['old_price'],
                    // Badges zijn automatisch (Sale via oude prijs, Bestseller via verkoop)
                    'badge'              => null,
                    'badge_gold'         => false,
                    'bg_from'            => $bg[0],
                    'bg_to'              => $bg[1],
                    'image'              => $p['image'] ?? $images[$i % count($images)],
                    'description'        => $p['description'] ?? null,
                    'kenmerken'          => $p['kenmerken'] ?? null,
                    'gebruiksaanwijzing' => $p['gebruiksaanwijzing'] ?? null,
                    'inhoud'             => $p['inhoud'] ?? null,
                    'reviews'            => $p['reviews'],
                    'voorraad'           => $i % 9 === 4 ? 0 : (($i * 7) % 30) + 2,
                    'actief'             => true,
                ],
            );
        }
    }
}
