<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Mollie\Api\Exceptions\MollieException;
use Mollie\Api\MollieApiClient;

class CheckoutController extends Controller
{
    public function show(): View
    {
        return view('afrekenen');
    }

    /**
     * Plaatst de bestelling en stuurt de klant door naar Mollie om te betalen.
     * Prijzen en voorraad komen altijd server-side uit de database.
     */
    public function store(Request $request): RedirectResponse
    {
        $gegevens = $request->validate([
            'voornaam'   => ['required', 'string', 'max:100'],
            'achternaam' => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255'],
            'telefoon'   => ['nullable', 'string', 'max:30'],
            'levering'   => ['required', 'in:bezorgen,afhalen'],
            'straat'     => ['required_if:levering,bezorgen', 'nullable', 'string', 'max:255'],
            'postcode'   => ['required_if:levering,bezorgen', 'nullable', 'string', 'max:10'],
            'plaats'     => ['required_if:levering,bezorgen', 'nullable', 'string', 'max:100'],
            'land'       => ['required_if:levering,bezorgen', 'nullable', 'in:NL,BE'],
            'kortingscode' => ['nullable', 'string', 'max:50'],
            'opmerking'  => ['nullable', 'string', 'max:1000'],
            'winkelwagen' => ['required', 'json'],
        ], [
            'voornaam.required'   => 'Vul je voornaam in.',
            'achternaam.required' => 'Vul je achternaam in.',
            'email.required'      => 'Vul je e-mailadres in.',
            'email.email'         => 'Dit is geen geldig e-mailadres.',
            'levering.required'   => 'Kies bezorgen of afhalen.',
            'levering.in'         => 'Kies bezorgen of afhalen.',
            'straat.required_if'  => 'Vul je straat en huisnummer in.',
            'postcode.required_if' => 'Vul je postcode in.',
            'plaats.required_if'  => 'Vul je woonplaats in.',
            'land.required_if'    => 'Kies een land.',
            'land.in'             => 'We bezorgen momenteel in Nederland en België.',
            'winkelwagen.required' => 'Je winkelwagen is leeg.',
        ]);

        $regels = collect(json_decode($gegevens['winkelwagen'], true))
            ->map(fn ($regel) => [
                'slug' => (string) ($regel['id'] ?? ''),
                'qty'  => max(1, (int) ($regel['qty'] ?? 1)),
            ])
            ->filter(fn ($regel) => $regel['slug'] !== '');

        if ($regels->isEmpty()) {
            return back()->withInput()->withErrors(['winkelwagen' => 'Je winkelwagen is leeg.']);
        }

        try {
            $order = DB::transaction(function () use ($gegevens, $regels, $request) {
                $subtotaal = 0;
                $orderRegels = [];

                foreach ($regels as $regel) {
                    $product = Product::where('slug', $regel['slug'])
                        ->where('actief', true)
                        ->lockForUpdate()
                        ->first();

                    if (! $product) {
                        throw new \RuntimeException('Een product uit je winkelwagen is niet meer beschikbaar. Haal het uit je winkelwagen en probeer opnieuw.');
                    }

                    if ($product->voorraad < $regel['qty']) {
                        throw new \RuntimeException(
                            $product->voorraad === 0
                                ? '"'.$product->name.'" is helaas net uitverkocht. Haal het uit je winkelwagen en probeer opnieuw.'
                                : 'Van "'.$product->name.'" '.($product->voorraad === 1 ? 'is' : 'zijn').' er nog maar '.$product->voorraad.' op voorraad. Pas het aantal aan en probeer opnieuw.',
                        );
                    }

                    $product->decrement('voorraad', $regel['qty']);

                    $subtotaal += (float) $product->price * $regel['qty'];
                    $orderRegels[] = [
                        'product_slug' => $product->slug,
                        'name'         => $product->name,
                        'price'        => $product->price,
                        'qty'          => $regel['qty'],
                    ];
                }

                // Kortingscode server-side controleren en verzilveren
                $korting = 0.0;
                $kortingCode = null;

                if (! empty($gegevens['kortingscode'])) {
                    $code = DiscountCode::whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($gegevens['kortingscode']))])
                        ->lockForUpdate()
                        ->first();

                    $fout = $code ? $code->valideer($subtotaal) : 'Deze kortingscode bestaat niet.';
                    if ($fout) {
                        throw new \RuntimeException($fout);
                    }

                    $korting = $code->kortingVoor($subtotaal);
                    $kortingCode = $code->code;
                    $code->increment('gebruikt');
                }

                $afhalen = $gegevens['levering'] === 'afhalen';
                if ($afhalen) {
                    $verzendkosten = 0;
                } else {
                    $tarief = config('shop.verzending.'.$gegevens['land']);
                    $verzendkosten = $subtotaal >= $tarief['gratis_vanaf'] ? 0 : $tarief['kosten'];
                }

                $order = Order::create([
                    'user_id'  => $request->user()?->id,
                    'name'     => trim($gegevens['voornaam'].' '.$gegevens['achternaam']),
                    'email'    => $gegevens['email'],
                    'phone'    => $gegevens['telefoon'] ?? null,
                    'address'  => $afhalen ? null : $gegevens['straat'],
                    'postcode' => $afhalen ? null : $gegevens['postcode'],
                    'city'     => $afhalen ? null : $gegevens['plaats'],
                    'country'  => $afhalen ? 'NL' : $gegevens['land'],
                    'levering' => $gegevens['levering'],
                    'note'     => $gegevens['opmerking'] ?? null,
                    'shipping' => $verzendkosten,
                    'discount_code' => $kortingCode,
                    'discount' => $korting,
                    'total'    => max(0, $subtotaal - $korting) + $verzendkosten,
                    'status'   => 'open',
                ]);

                $order->items()->createMany($orderRegels);

                return $order;
            });
        } catch (\RuntimeException $fout) {
            return back()->withInput()->withErrors(['winkelwagen' => $fout->getMessage()]);
        }

        // Volledig gratis (bijv. 100% korting): geen betaling nodig
        if ((float) $order->total <= 0) {
            $order->update(['status' => 'betaald']);
            $order->maakFactuur();
            $request->session()->put('laatste_bestelling', $order->id);

            return redirect()->route('bedankt', $order);
        }

        // Betaling aanmaken bij Mollie en de klant doorsturen
        try {
            $betaalGegevens = [
                'amount' => [
                    'currency' => 'EUR',
                    'value'    => number_format($order->total, 2, '.', ''),
                ],
                'description' => 'Bestelling '.$order->nummer().' - '.config('app.name'),
                'redirectUrl' => route('afrekenen.retour', $order),
                'metadata'    => ['order_id' => $order->id],
            ];

            // Webhook alleen meesturen als de site publiek bereikbaar is
            if (! preg_match('/(localhost|127\.0\.0\.1|\.test)/', config('app.url'))) {
                $betaalGegevens['webhookUrl'] = route('mollie.webhook');
            }

            $betaling = $this->mollie()->payments->create($betaalGegevens);

            $order->update(['mollie_payment_id' => $betaling->id]);

            return redirect()->away($betaling->getCheckoutUrl(), 303);
        } catch (MollieException $fout) {
            $this->annuleer($order);

            return back()->withInput()->withErrors([
                'winkelwagen' => 'De betaling kon niet worden gestart: '.$fout->getMessage(),
            ]);
        }
    }

    /**
     * Controleert een kortingscode voor de checkout (live-voorbeeld).
     */
    public function kortingscode(Request $request): JsonResponse
    {
        $gegevens = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'winkelwagen' => ['required', 'json'],
        ]);

        $subtotaal = collect(json_decode($gegevens['winkelwagen'], true))
            ->map(fn ($regel) => [
                'slug' => (string) ($regel['id'] ?? ''),
                'qty'  => max(1, (int) ($regel['qty'] ?? 1)),
            ])
            ->filter(fn ($regel) => $regel['slug'] !== '')
            ->reduce(function ($som, $regel) {
                $product = Product::where('slug', $regel['slug'])->where('actief', true)->first();

                return $som + ($product ? (float) $product->price * $regel['qty'] : 0);
            }, 0.0);

        $code = DiscountCode::whereRaw('UPPER(code) = ?', [mb_strtoupper(trim($gegevens['code']))])->first();
        $fout = $code ? $code->valideer($subtotaal) : 'Deze kortingscode bestaat niet.';

        if ($fout) {
            return response()->json(['geldig' => false, 'melding' => $fout]);
        }

        return response()->json([
            'geldig' => true,
            'code'   => $code->code,
            'bedrag' => $code->kortingVoor($subtotaal),
            'label'  => $code->omschrijving(),
        ]);
    }

    /**
     * De klant komt terug van Mollie: status controleren en afhandelen.
     * (Werkt ook zonder webhook, bijvoorbeeld op een lokale omgeving.)
     */
    public function retour(Request $request, Order $order): RedirectResponse
    {
        if (! $order->mollie_payment_id) {
            return redirect()->route('afrekenen');
        }

        try {
            $betaling = $this->mollie()->payments->get($order->mollie_payment_id);
        } catch (MollieException) {
            return redirect()->route('afrekenen')->withErrors([
                'winkelwagen' => 'We konden je betaalstatus niet ophalen. Neem contact op als er is afgeschreven.',
            ]);
        }

        if ($betaling->isPaid()) {
            $order->update(['status' => 'betaald']);
            $order->maakFactuur();
            $request->session()->put('laatste_bestelling', $order->id);

            return redirect()->route('bedankt', $order);
        }

        if ($betaling->isPending() || $betaling->isAuthorized()) {
            $request->session()->put('laatste_bestelling', $order->id);

            return redirect()->route('bedankt', $order);
        }

        // Open (afgebroken), geannuleerd, mislukt of verlopen: voorraad terugzetten
        $this->annuleer($order);

        return redirect()->route('afrekenen')->withErrors([
            'winkelwagen' => 'Je betaling is niet afgerond. Je winkelwagen staat nog voor je klaar - probeer het gerust opnieuw.',
        ]);
    }

    /**
     * Mollie meldt statuswijzigingen op dit endpoint (productie).
     */
    public function webhook(Request $request): Response
    {
        $order = Order::where('mollie_payment_id', $request->input('id'))->first();

        if ($order) {
            try {
                $betaling = $this->mollie()->payments->get($order->mollie_payment_id);

                if ($betaling->isPaid() && $order->status === 'open') {
                    $order->update(['status' => 'betaald']);
                    $order->maakFactuur();
                } elseif (($betaling->isCanceled() || $betaling->isExpired() || $betaling->isFailed()) && $order->status === 'open') {
                    $this->annuleer($order);
                }
            } catch (MollieException) {
                // Mollie probeert het later opnieuw
            }
        }

        return response('OK');
    }

    public function bedankt(Request $request, Order $order): View|RedirectResponse
    {
        // Alleen de plaatser van de bestelling mag de bevestiging zien
        if ($request->session()->get('laatste_bestelling') !== $order->id) {
            return redirect('/');
        }

        return view('bedankt', ['order' => $order->load('items')]);
    }

    private function mollie(): MollieApiClient
    {
        $client = new MollieApiClient();
        $client->setApiKey(config('services.mollie.key'));

        return $client;
    }

    /**
     * Zet de voorraad terug en markeert de bestelling als geannuleerd.
     */
    private function annuleer(Order $order): void
    {
        if ($order->status === 'geannuleerd') {
            return;
        }

        foreach ($order->items as $regel) {
            Product::where('slug', $regel->product_slug)->increment('voorraad', $regel->qty);
        }

        // Gebruikte kortingscode weer vrijgeven
        if ($order->discount_code) {
            DiscountCode::where('code', $order->discount_code)
                ->where('gebruikt', '>', 0)
                ->decrement('gebruikt');
        }

        $order->update(['status' => 'geannuleerd']);
    }
}
