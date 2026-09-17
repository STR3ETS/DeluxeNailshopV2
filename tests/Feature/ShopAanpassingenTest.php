<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopAanpassingenTest extends TestCase
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
            'brand' => 'Touch', 'name' => $naam, 'slug' => \Illuminate\Support\Str::slug($naam),
            'category' => 'gellak', 'subcategory' => 'gellak-color', 'price' => 10, 'voorraad' => 5, 'actief' => true,
        ], $extra));
    }

    public function test_paginas_laden_met_nieuwe_teksten(): void
    {
        $this->get('/')->assertOk()
            ->assertSee('Vanaf €75 NL / Vanaf €100 BE')
            ->assertSee('Kleurrijk en eindeloos creatief.')
            ->assertSee('geen genoegen nemen met &quot;goed genoeg&quot;', false)
            ->assertSee('From master to master')
            ->assertSee('Ontdek Touch')
            ->assertDontSee('16:00')
            ->assertDontSee('Staleks')
            ->assertDontSee('Cadeaubonnen')
            ->assertDontSee('TikTok')
            ->assertSee('+31 6 42939291')
            ->assertSee(route('over-ons'));

        $this->get('/faq')->assertOk()
            ->assertSee('Maandag t/m vrijdag: 09:00 - 18:00')
            ->assertSee('id="betaalmethoden"', false)
            ->assertSee('wa.me/31642939291');

        $this->get('/over-ons')->assertOk()
            ->assertSee('Ons verhaal')
            ->assertSee('Wij zorgen voor de tools.')
            ->assertSee('<strong class="font-semibold text-dark">Touch en Staleks</strong>', false);
    }

    public function test_producten_staan_in_natuurlijke_alfabetische_volgorde(): void
    {
        $this->product('Gellak #0010');
        $this->product('Gellak #0002');
        $this->product('Gellak #0001');
        $this->product('gellak #2 los');

        $this->get('/producten')->assertOk()
            ->assertSeeInOrder(['Gellak #0001', 'Gellak #0002', 'Gellak #0010', 'gellak #2 los']);
    }

    public function test_merkfilter_via_url(): void
    {
        $this->get('/producten?merk=Touch')->assertOk()->assertSee('x-text="title">Touch</h1>', false);
        $this->get('/producten?merk=Onbekend')->assertOk()->assertSee('x-text="title">Alle producten</h1>', false);
    }

    public function test_nieuwsbrief_geeft_eenmalige_persoonlijke_code_die_in_account_staat(): void
    {
        $this->postJson('/nieuwsbrief', ['email' => 'Sophie@Example.nl'])
            ->assertOk()
            ->assertJson(['ingeschreven' => true, 'eigen_account' => false])
            ->assertJsonMissingPath('code');

        // Opnieuw inschrijven levert geen tweede code op
        $this->postJson('/nieuwsbrief', ['email' => 'sophie@example.nl'])->assertOk();
        $this->assertSame(1, NewsletterSubscriber::count());
        $this->assertSame(1, DiscountCode::count());

        $code = NewsletterSubscriber::voorEmail('sophie@example.nl')->discountCode;
        $this->assertStringStartsWith('WELKOM-', $code->code);
        $this->assertSame(1, $code->max_gebruik);

        $user = User::factory()->create(['email' => 'sophie@example.nl', 'role' => 'klant']);
        $this->actingAs($user)->get('/account')->assertOk()->assertSee($code->code)->assertSee('Kopieer code');

        // Andere klant ziet de code niet, wel de inschrijfknop
        $ander = User::factory()->create(['role' => 'klant']);
        $this->actingAs($ander)->get('/account')->assertOk()->assertDontSee($code->code)->assertSee('Inschrijven en code ontvangen');

        // Na één keer gebruiken is de code niet meer geldig
        $this->assertNull($code->valideer(50));
        $code->increment('gebruikt');
        $this->assertSame('Deze kortingscode is al gebruikt.', $code->fresh()->valideer(50));
        $this->actingAs($user)->get('/account')->assertSee('Gebruikt');
    }

    public function test_nieuwsbrief_vanuit_account_zonder_javascript(): void
    {
        $user = User::factory()->create(['role' => 'klant']);

        $this->actingAs($user)->from('/account')
            ->post('/nieuwsbrief', ['email' => $user->email])
            ->assertRedirect('/account')
            ->assertSessionHas('nieuwsbrief', 'account');

        $this->actingAs($user)->get('/account')->assertSee(NewsletterSubscriber::first()->discountCode->code);
    }

    public function test_nieuwsbriefcodes_staan_niet_tussen_handmatige_kortingscodes(): void
    {
        NewsletterSubscriber::inschrijven('a@example.nl');
        DiscountCode::create(['code' => 'ZOMER10', 'type' => 'procent', 'waarde' => 10, 'actief' => true]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/admin/instellingen')->assertOk()
            ->assertSee('ZOMER10')
            ->assertDontSee(NewsletterSubscriber::first()->discountCode->code)
            ->assertSee('1 persoonlijke nieuwsbriefcode uitgegeven');
    }

    public function test_afrekenen_vereist_akkoord_met_voorwaarden(): void
    {
        $product = $this->product('Gellak #0001');

        $gegevens = [
            'voornaam' => 'Sophie', 'achternaam' => 'Jansen', 'email' => 'sophie@example.nl',
            'levering' => 'afhalen',
            'winkelwagen' => json_encode([['id' => $product->slug, 'qty' => 1]]),
        ];

        $this->from('/afrekenen')->post('/afrekenen', $gegevens)
            ->assertRedirect('/afrekenen')
            ->assertSessionHasErrors('voorwaarden');

        $this->get('/afrekenen')->assertSee('algemene voorwaarden')->assertSee('name="voorwaarden"', false);
    }
}
