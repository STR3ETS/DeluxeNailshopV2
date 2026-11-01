<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZoekenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $maak = fn (array $p) => Product::create($p + ['price' => 10, 'voorraad' => 5, 'actief' => true]);

        $maak(['brand' => 'Touch', 'name' => 'Gellak #014 (Deep Burgundy)', 'slug' => 'gellak-014', 'category' => 'gellak', 'subcategory' => 'gellak-color']);
        $maak(['brand' => 'Valeri', 'name' => 'Rubber Base Milky', 'slug' => 'rubber-base-milky', 'category' => 'base-top', 'subcategory' => 'rubber-base']);
        $maak(['brand' => 'DNKa\'', 'name' => 'Cuticle pusher', 'slug' => 'cuticle-pusher', 'category' => 'benodigdheden', 'subcategory' => 'pushers']);
        $maak(['brand' => 'Touch', 'name' => 'Verborgen gellak', 'slug' => 'verborgen', 'category' => 'gellak', 'subcategory' => 'gellak-color', 'actief' => false]);
    }

    public function test_zoekknop_opent_een_zoekformulier_in_de_header(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('id="zoekpaneel"', false)
            ->assertSee('name="zoek"', false)
            ->assertSee('@click="openZoeken()"', false);
    }

    public function test_live_zoeken_op_naam_merk_en_categorie(): void
    {
        $this->getJson('/zoeken?q=burgundy')->assertOk()
            ->assertJsonPath('totaal', 1)
            ->assertJsonPath('resultaten.0.naam', 'Gellak #014 (Deep Burgundy)');

        // Op merk + woord uit de naam, hoofdletterongevoelig
        $this->getJson('/zoeken?q=VALERI rubber')->assertJsonPath('totaal', 1);

        // Op subcategorie: "Pushers" vindt ook producten zonder dat woord in de naam
        $this->getJson('/zoeken?q=pushers')->assertJsonPath('resultaten.0.naam', 'Cuticle pusher');

        // Inactieve producten en te korte zoektermen geven niets
        $this->getJson('/zoeken?q=verborgen')->assertJsonPath('totaal', 0);
        $this->getJson('/zoeken?q=g')->assertJsonPath('totaal', 0);
    }

    public function test_productenpagina_filtert_op_zoekterm_uit_de_link(): void
    {
        $this->get('/producten?zoek=burgundy')->assertOk()
            ->assertSee('Zoekresultaten')
            ->assertSee('zoek: \'burgundy\'', false)
            ->assertSee('data-zoek="touch gellak #014 (deep burgundy) gellak gellak color"', false);
    }
}
