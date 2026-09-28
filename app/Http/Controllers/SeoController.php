<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * XML-sitemap met alle openbare pagina's: vaste pagina's, categorie-,
     * merk- en salepagina's (alleen als er producten in staan) en elke
     * actieve productpagina met foto en laatste wijzigingsdatum.
     */
    public function sitemap(): Response
    {
        $producten = Product::where('actief', true)->orderBy('name')->get();
        $laatsteWijziging = $producten->max('updated_at');

        $categorieen = collect(config('shop.categories'))
            ->filter(fn ($c) => $producten->contains('category', $c['slug']))
            ->map(fn ($c) => [
                'loc' => url('/producten').'?categorie='.$c['slug'],
                'lastmod' => $producten->where('category', $c['slug'])->max('updated_at'),
            ]);

        $merken = collect(config('shop.brands'))
            ->filter(fn ($merk) => $producten->contains('brand', $merk))
            ->map(fn ($merk) => [
                'loc' => url('/producten').'?merk='.urlencode($merk),
                'lastmod' => $producten->where('brand', $merk)->max('updated_at'),
            ]);

        $sale = $producten->whereNotNull('old_price')->isNotEmpty()
            ? [['loc' => url('/producten').'?sale=1', 'lastmod' => $producten->whereNotNull('old_price')->max('updated_at')]]
            : [];

        $paginas = collect([
            ['loc' => url('/'), 'lastmod' => $laatsteWijziging],
            ['loc' => route('producten'), 'lastmod' => $laatsteWijziging],
        ])
            ->concat($categorieen)
            ->concat($merken)
            ->concat($sale)
            ->concat([
                ['loc' => route('over-ons')],
                ['loc' => route('faq')],
                ['loc' => route('privacybeleid')],
                ['loc' => route('algemene-voorwaarden')],
                ['loc' => route('cookies')],
            ]);

        return response()
            ->view('seo.sitemap', ['paginas' => $paginas, 'producten' => $producten])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * robots.txt: houdt beheer, account en de bestelflow buiten de index
     * en wijst zoekmachines de weg naar de sitemap.
     */
    public function robots(): Response
    {
        $regels = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /account',
            'Disallow: /afrekenen',
            'Disallow: /bedankt/',
            'Disallow: /webhooks/',
            'Disallow: /test/',
            '',
            'Sitemap: '.url('sitemap.xml'),
        ];

        return response(implode("\n", $regels)."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
