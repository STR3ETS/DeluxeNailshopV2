<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacturenEnKortingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function product(string $naam, float $prijs, ?float $oudePrijs = null): Product
    {
        return Product::create([
            'brand' => 'Touch', 'name' => $naam, 'slug' => \Illuminate\Support\Str::slug($naam),
            'category' => 'gellak', 'subcategory' => 'gellak-color', 'price' => $prijs,
            'old_price' => $oudePrijs, 'voorraad' => 5, 'actief' => true,
        ]);
    }

    private function betaaldeBestelling(): Order
    {
        $order = Order::create([
            'name' => 'Webshop Klant', 'email' => 'klant@example.nl', 'country' => 'NL',
            'levering' => 'afhalen', 'shipping' => 0, 'total' => 20, 'status' => 'betaald',
        ]);
        $order->items()->create(['product_slug' => 'x', 'name' => 'Gellak', 'price' => 10, 'qty' => 2]);
        $order->maakFactuur();

        return $order;
    }

    public function test_benodigdheden_heeft_bitjes_scharen_en_pushers(): void
    {
        $benodigdheden = collect(config('shop.categories'))->firstWhere('slug', 'benodigdheden');
        $subs = collect($benodigdheden['subcategories'])->pluck('name');

        $this->assertTrue($subs->contains('Bitjes'));
        $this->assertTrue($subs->contains('Scharen'));
        $this->assertTrue($subs->contains('Pushers'));

        $this->get('/producten')->assertOk()->assertSee('Bitjes')->assertSee('Scharen')->assertSee('Pushers');
    }

    public function test_handmatige_factuur_loopt_door_in_dezelfde_nummering(): void
    {
        $jaar = now()->format('Y');
        $this->betaaldeBestelling();

        $this->actingAs($this->admin())->get('/admin/facturen/nieuw')->assertOk()->assertSee('F'.$jaar.'-0002');

        $this->post('/admin/facturen', [
            'naam' => 'Lisa de Vries', 'bedrijf' => 'Salon Lisa', 'email' => 'lisa@salon.nl',
            'adres' => 'Dorpsstraat 1', 'postcode' => '1234 AB', 'plaats' => 'Zevenaar', 'land' => 'NL',
            'betaald' => '0', 'opmerking' => 'Afgehaald in de winkel',
            'regels' => [
                ['omschrijving' => 'Rubber base 15 ml', 'aantal' => 2, 'prijs' => '12.50'],
                ['omschrijving' => 'Pusher', 'aantal' => 1, 'prijs' => '7.95'],
            ],
        ])->assertRedirect('/admin/facturen');

        $factuur = Invoice::whereNull('order_id')->first();
        $this->assertSame('F'.$jaar.'-0002', $factuur->number);
        $this->assertSame('32.95', (string) $factuur->total);
        $this->assertFalse($factuur->paid);

        // De volgende webshopfactuur krijgt weer het nummer erna
        $this->assertSame('F'.$jaar.'-0003', $this->betaaldeBestelling()->factuur->number);

        $this->get('/admin/facturen')->assertOk()->assertSee('Salon Lisa')->assertSee('Nog te betalen');

        // PDF's van beide soorten facturen zijn te downloaden
        $this->get('/admin/facturen/'.$factuur->id.'/download')->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->get('/admin/facturen/'.Invoice::whereNotNull('order_id')->first()->id.'/download')->assertOk();

        $this->patch('/admin/facturen/'.$factuur->id.'/betaald')->assertRedirect();
        $this->assertTrue($factuur->fresh()->paid);
    }

    public function test_handmatige_factuur_vereist_naam_en_regels(): void
    {
        $this->actingAs($this->admin())
            ->from('/admin/facturen/nieuw')
            ->post('/admin/facturen', ['naam' => '', 'betaald' => '1', 'regels' => []])
            ->assertRedirect('/admin/facturen/nieuw')
            ->assertSessionHasErrors(['naam', 'regels']);

        $this->assertSame(0, Invoice::count());
    }

    public function test_kortingscode_geldt_niet_voor_sale_producten(): void
    {
        $normaal = $this->product('Gellak normaal', 20);
        $sale = $this->product('Gellak sale', 30, 40);
        DiscountCode::create(['code' => 'TIEN', 'type' => 'procent', 'waarde' => 10, 'actief' => true]);

        // Gemengde winkelwagen: 10% alleen over het normale product
        $this->postJson('/afrekenen/kortingscode', [
            'code' => 'tien',
            'winkelwagen' => json_encode([['id' => $normaal->slug, 'qty' => 1], ['id' => $sale->slug, 'qty' => 1]]),
        ])->assertJson(['geldig' => true, 'bedrag' => 2.0, 'metSale' => true]);

        // Alleen sale-producten: code wordt geweigerd
        $this->postJson('/afrekenen/kortingscode', [
            'code' => 'TIEN',
            'winkelwagen' => json_encode([['id' => $sale->slug, 'qty' => 2]]),
        ])->assertJson(['geldig' => false, 'melding' => 'Kortingscodes gelden niet voor producten in de sale.']);
    }

    public function test_bestelling_met_sale_product_krijgt_alleen_korting_op_normale_producten(): void
    {
        $normaal = $this->product('Gellak normaal', 20);
        $sale = $this->product('Gellak sale', 30, 40);
        DiscountCode::create(['code' => 'TIEN', 'type' => 'procent', 'waarde' => 10, 'actief' => true]);
        config(['services.mollie.key' => 'test_'.str_repeat('x', 30)]);

        $this->from('/afrekenen')->post('/afrekenen', [
            'voornaam' => 'Sophie', 'achternaam' => 'Jansen', 'email' => 'sophie@example.nl',
            'levering' => 'afhalen', 'voorwaarden' => '1', 'kortingscode' => 'TIEN',
            'winkelwagen' => json_encode([['id' => $normaal->slug, 'qty' => 1], ['id' => $sale->slug, 'qty' => 1]]),
        ]);

        $order = Order::latest('id')->first();
        $this->assertSame('2.00', (string) $order->discount);
        $this->assertSame('48.00', (string) $order->total);
    }
}
