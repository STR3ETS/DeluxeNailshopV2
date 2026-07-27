<?php

use App\Http\Controllers\Admin\BestellingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FactuurController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\VoorraadController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $bestsellerIds = Product::bestsellerIds();

    return view('welcome', [
        'bestsellers' => Product::whereIn('id', $bestsellerIds)->get()
            ->sortBy(fn ($p) => array_search($p->id, $bestsellerIds))
            ->map(fn ($p) => $p->toCardArray(true))
            ->values()
            ->all(),
    ]);
});

Route::get('/producten', function () {
    $bestsellerIds = Product::bestsellerIds();

    return view('producten', [
        'products' => Product::where('actief', true)->orderBy('id')->get()
            ->map(fn ($p) => $p->toCardArray(in_array($p->id, $bestsellerIds)))
            ->all(),
    ]);
})->name('producten');

Route::view('/faq', 'faq')->name('faq');

Route::get('/afrekenen', [App\Http\Controllers\CheckoutController::class, 'show'])->name('afrekenen');
Route::post('/afrekenen', [App\Http\Controllers\CheckoutController::class, 'store'])->name('afrekenen.plaatsen');
Route::get('/afrekenen/retour/{order}', [App\Http\Controllers\CheckoutController::class, 'retour'])->name('afrekenen.retour');
Route::post('/webhooks/mollie', [App\Http\Controllers\CheckoutController::class, 'webhook'])->name('mollie.webhook');
Route::get('/bedankt/{order}', [App\Http\Controllers\CheckoutController::class, 'bedankt'])->name('bedankt');

// Alleen lokaal: bekijk de bedankt-pagina van een bestelling zonder echt te
// betalen (zet de sessie die de pagina normaal via de betaalflow krijgt).
if (app()->environment('local')) {
    Route::get('/test/bedankt/{order}', function (App\Models\Order $order) {
        session(['laatste_bestelling' => $order->id]);

        return redirect()->route('bedankt', $order);
    });
}

/*
|--------------------------------------------------------------------------
| Klantenportaal - gasten
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::view('/registreren', 'registreren')->name('registreren');
    Route::post('/registreren', [AuthController::class, 'register'])->name('registreren.attempt');

    Route::view('/wachtwoord-vergeten', 'wachtwoord-vergeten')->name('wachtwoord-vergeten');
});

/*
|--------------------------------------------------------------------------
| Ingelogd - klant & admin
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/account', 'account')->name('account');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/producten', [AdminProductController::class, 'index'])->name('producten');
    Route::get('/producten/nieuw', [AdminProductController::class, 'create'])->name('producten.nieuw');
    Route::post('/producten', [AdminProductController::class, 'store'])->name('producten.opslaan');
    Route::get('/producten/{product}/bewerken', [AdminProductController::class, 'edit'])->name('producten.bewerken');
    Route::post('/producten/{product}/dupliceren', [AdminProductController::class, 'duplicate'])->name('producten.dupliceren');
    Route::put('/producten/{product}', [AdminProductController::class, 'update'])->name('producten.bijwerken');
    Route::delete('/producten/{product}', [AdminProductController::class, 'destroy'])->name('producten.verwijderen');

    Route::get('/voorraad', [VoorraadController::class, 'index'])->name('voorraad');
    Route::patch('/voorraad/{product}', [VoorraadController::class, 'update'])->name('voorraad.bijwerken');

    Route::get('/bestellingen', [BestellingController::class, 'index'])->name('bestellingen');
    Route::get('/bestellingen/{order}', [BestellingController::class, 'show'])->name('bestellingen.detail');
    Route::patch('/bestellingen/{order}/status', [BestellingController::class, 'updateStatus'])->name('bestellingen.status');
    Route::get('/facturen', [FactuurController::class, 'index'])->name('facturen');
    Route::get('/facturen/{invoice}/download', [FactuurController::class, 'download'])->name('facturen.download');
    Route::view('/instellingen', 'admin.placeholder', ['titel' => 'Instellingen'])->name('instellingen');
});

Route::view('/privacybeleid', 'privacybeleid')->name('privacybeleid');

Route::view('/cookies', 'cookies')->name('cookies');

Route::view('/algemene-voorwaarden', 'algemene-voorwaarden')->name('algemene-voorwaarden');

Route::get('/producten/{slug}', function (string $slug) {
    $product = Product::where('slug', $slug)->where('actief', true)->firstOrFail();
    $bestsellerIds = Product::bestsellerIds();

    return view('product-detail', [
        'product' => $product->toCardArray(in_array($product->id, $bestsellerIds)),
        'related' => Product::where('actief', true)
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get()
            ->map(fn ($p) => $p->toCardArray(in_array($p->id, $bestsellerIds)))
            ->all(),
    ]);
})->name('product.show');
