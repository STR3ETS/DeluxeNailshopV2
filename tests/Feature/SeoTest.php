<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function product(string $naam, array $extra = []): Product
    {
        return Product::create(array_merge([
            'brand' => 'Touch', 'name' => $naam, 'slug' => Str::slug('Touch '.$naam),
            'category' => 'gellak', 'subcategory' => 'gellak-color', 'price' => 12.5, 'voorraad' => 5,
            'actief' => true, 'image' => 'temp-producten/foto.png',
        ], $extra));
    }

    /**
     * Haalt alle JSON-LD-blokken uit de HTML en geeft ze gedecodeerd terug.
     */
    private function structuredData(string $html): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $blokken);

        return array_map(fn ($json) => json_decode($json, true, flags: JSON_THROW_ON_ERROR), $blokken[1]);
    }

    public function test_sitemap_bevat_openbare_paginas_en_actieve_producten(): void
    {
        $this->product('Rood', ['old_price' => 15]);
        $this->product('Verborgen', ['actief' => false]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee(route('faq'))
            ->assertSee(url('/producten').'?categorie=gellak')
            ->assertSee(url('/producten').'?merk=Touch')
            ->assertSee(url('/producten').'?sale=1')
            ->assertSee(route('product.show', 'touch-rood'))
            ->assertSee(asset('temp-producten/foto.png'))
            ->assertDontSee('touch-verborgen')
            // Lege categorieën en merken horen er niet in
            ->assertDontSee('?categorie=liquids')
            ->assertDontSee('?merk=Valeri')
            ->assertDontSee('/admin')
            ->assertDontSee('/afrekenen');

        $this->assertNotFalse(simplexml_load_string($this->get('/sitemap.xml')->getContent()));
    }

    public function test_robots_txt_verwijst_naar_sitemap_en_blokkeert_beheer(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin')
            ->assertSee('Disallow: /afrekenen')
            ->assertSee('Sitemap: '.url('sitemap.xml'));
    }

    public function test_productpagina_heeft_product_en_kruimelpad_schema(): void
    {
        $this->product('Rood', ['voorraad' => 0, 'description' => 'Een rode gellak.']);

        $html = $this->get('/producten/touch-rood')->assertOk()->getContent();
        $schemas = collect($this->structuredData($html))->keyBy('@type');

        $product = $schemas['Product'];
        $this->assertSame('Touch Rood', $product['name']);
        $this->assertSame('12.50', $product['offers']['price']);
        $this->assertSame('EUR', $product['offers']['priceCurrency']);
        $this->assertSame('https://schema.org/OutOfStock', $product['offers']['availability']);
        $this->assertSame([asset('temp-producten/foto.png')], $product['image']);
        $this->assertArrayNotHasKey('aggregateRating', $product);

        $kruimels = collect($schemas['BreadcrumbList']['itemListElement'])->pluck('name')->all();
        $this->assertSame(['Home', 'Producten', 'Gellak', 'Touch Rood'], $kruimels);

        $this->assertStringContainsString('<link rel="canonical" href="'.route('product.show', 'touch-rood').'">', $html);
    }

    public function test_categorie_en_merkpagina_hebben_eigen_titel_en_canonical(): void
    {
        $this->get('/producten?categorie=gellak')
            ->assertOk()
            ->assertSee('<title>Gellak kopen - '.config('app.name').'</title>', false)
            ->assertSee('<link rel="canonical" href="'.url('/producten').'?categorie=gellak">', false);

        $this->get('/producten?merk=Touch')
            ->assertSee('<title>Touch nagelproducten kopen - '.config('app.name').'</title>', false)
            ->assertSee('<link rel="canonical" href="'.url('/producten').'?merk=Touch">', false);

        // Onbekende filters vallen terug op de algemene productenpagina
        $this->get('/producten?categorie=bestaat-niet&kleur=rood')
            ->assertSee('<link rel="canonical" href="'.url('/producten').'">', false);
    }

    public function test_homepage_en_faq_hebben_structured_data(): void
    {
        $home = collect($this->structuredData($this->get('/')->assertOk()->getContent()));
        $graph = collect($home->first()['@graph'])->keyBy('@type');
        $this->assertSame('Thorbeckestraat 3', $graph['Store']['address']['streetAddress']);
        $this->assertSame(url('/'), $graph['WebSite']['url']);

        $faq = collect($this->structuredData($this->get('/faq')->assertOk()->getContent()))->keyBy('@type');
        $this->assertNotEmpty($faq['FAQPage']['mainEntity']);
        $this->assertSame('Hoe plaats ik een bestelling?', $faq['FAQPage']['mainEntity'][0]['name']);
    }

    public function test_afgeschermde_paginas_zijn_noindex_zonder_canonical(): void
    {
        $this->get('/login')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertDontSee('rel="canonical"', false);

        $admin = User::create(['name' => 'Beheerder', 'email' => 'admin@example.com', 'password' => 'geheim123', 'role' => 'admin']);
        $this->actingAs($admin)->get('/admin')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_eigen_404_pagina_met_navigatie_en_noindex(): void
    {
        $this->get('/deze-pagina-bestaat-niet')
            ->assertNotFound()
            ->assertSee('zoekgeraakt')
            ->assertSee('<meta name="robots" content="noindex, follow">', false)
            ->assertSee(url('/producten').'?categorie=gellak')
            ->assertDontSee('rel="canonical"', false);

        $this->get('/producten/bestaat-niet')->assertNotFound()->assertSee('zoekgeraakt');
    }

    public function test_overige_foutpaginas_renderen_zonder_database(): void
    {
        foreach ([403, 419, 429, 500, 503] as $code) {
            $html = view('errors.'.$code, ['exception' => new HttpException($code)])->render();
            $this->assertStringContainsString('Foutcode '.$code, $html);
            $this->assertStringContainsString('noindex, nofollow', $html);
        }

        $this->assertStringContainsString('Foutcode 418', view('errors.4xx', ['exception' => new HttpException(418)])->render());
        $this->assertStringContainsString('Foutcode 502', view('errors.5xx', ['exception' => new HttpException(502)])->render());
    }
}
