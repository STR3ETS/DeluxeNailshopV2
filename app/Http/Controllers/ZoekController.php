<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ZoekController extends Controller
{
    /** Aantal producten dat de zoekbalk in de header direct toont. */
    private const MAX_RESULTATEN = 6;

    /**
     * Live zoekresultaten voor de zoekbalk in de header. Zoekt op merk,
     * naam, categorie en subcategorie; alle termen moeten voorkomen.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $termen = Product::zoektermen(mb_substr((string) $request->query('q', ''), 0, 100));

        if ($termen === [] || mb_strlen(implode('', $termen)) < 2) {
            return response()->json(['totaal' => 0, 'resultaten' => []]);
        }

        $gevonden = Product::where('actief', true)->get()
            ->filter(function (Product $product) use ($termen) {
                $tekst = Product::zoektekst($product->toCardArray());

                foreach ($termen as $term) {
                    if (! str_contains($tekst, $term)) {
                        return false;
                    }
                }

                return true;
            })
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return response()->json([
            'totaal'     => $gevonden->count(),
            'resultaten' => $gevonden->take(self::MAX_RESULTATEN)->map(fn (Product $p) => [
                'naam'      => $p->name,
                'merk'      => $p->brand,
                'prijs'     => (float) $p->price,
                'oudePrijs' => $p->old_price !== null ? (float) $p->old_price : null,
                'foto'      => $p->image ? asset($p->image) : null,
                'tint'      => [$p->bg_from ?? '#f6e3de', $p->bg_to ?? '#ecc9bf'],
                'url'       => url('/producten/'.$p->slug),
            ])->all(),
        ]);
    }
}
