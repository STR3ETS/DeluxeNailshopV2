<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class BestellingController extends Controller
{
    /** Alle bestelstatussen met hun label in het paneel. */
    public const STATUSSEN = [
        'open'        => 'Open',
        'betaald'     => 'Betaald',
        'verzonden'   => 'Verzonden',
        'afgerond'    => 'Afgerond',
        'geannuleerd' => 'Geannuleerd',
        'intern'      => 'Intern',
    ];

    public function index(): View
    {
        $bestellingen = Order::withSum('items as artikelen', 'qty')
            ->latest()
            ->get();

        return view('admin.bestellingen.index', [
            'bestellingen' => $bestellingen,
            'statussen'    => self::STATUSSEN,
            'teVerzenden'  => $bestellingen->where('status', 'betaald')->count(),
            'omzet30'      => (float) Order::whereIn('status', ['betaald', 'verzonden', 'afgerond'])
                ->where('created_at', '>=', now()->subDays(30))
                ->sum('total'),
        ]);
    }

    /**
     * Formulier voor een handmatige (interne) bestelling, bijvoorbeeld als
     * een medewerker producten uit de voorraad meeneemt. De voorraad wordt
     * afgeboekt, maar de bestelling telt niet mee in de omzet.
     */
    public function create(): View
    {
        return view('admin.bestellingen.form', [
            'producten' => Product::orderBy('name')->get(['id', 'slug', 'name', 'brand', 'price', 'voorraad']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $gegevens = $request->validate([
            'naam'      => ['required', 'string', 'max:100'],
            'opmerking' => ['nullable', 'string', 'max:1000'],
            'regels'    => ['required', 'array', 'min:1'],
            'regels.*.slug' => ['required', 'string'],
            'regels.*.qty'  => ['required', 'integer', 'min:1'],
        ], [
            'naam.required'   => 'Vul in voor wie of wat dit is (bijv. de naam van de medewerker).',
            'regels.required' => 'Voeg minimaal één product toe.',
            'regels.min'      => 'Voeg minimaal één product toe.',
            'regels.*.slug.required' => 'Kies bij elke regel een product.',
            'regels.*.qty.min' => 'Het aantal moet minimaal 1 zijn.',
        ]);

        try {
            $order = DB::transaction(function () use ($gegevens, $request) {
                $subtotaal = 0;
                $orderRegels = [];

                foreach ($gegevens['regels'] as $regel) {
                    $product = Product::where('slug', $regel['slug'])->lockForUpdate()->first();

                    if (! $product) {
                        throw new \RuntimeException('Een gekozen product bestaat niet meer.');
                    }

                    if ($product->voorraad < $regel['qty']) {
                        throw new \RuntimeException('Van "'.$product->name.'" '.($product->voorraad === 1 ? 'is' : 'zijn').' er nog maar '.$product->voorraad.' op voorraad.');
                    }

                    $product->decrement('voorraad', $regel['qty']);

                    $subtotaal += (float) $product->price * $regel['qty'];
                    $orderRegels[] = [
                        'product_slug' => $product->slug,
                        'name'         => $product->name,
                        'price'        => $product->price,
                        'qty'          => (int) $regel['qty'],
                    ];
                }

                $order = Order::create([
                    'user_id'  => $request->user()->id,
                    'name'     => $gegevens['naam'],
                    'email'    => $request->user()->email,
                    'country'  => 'NL',
                    'levering' => 'afhalen',
                    'note'     => $gegevens['opmerking'] ?? null,
                    'shipping' => 0,
                    'total'    => $subtotaal,
                    'status'   => 'intern',
                ]);

                $order->items()->createMany($orderRegels);

                return $order;
            });
        } catch (\RuntimeException $fout) {
            return back()->withInput()->withErrors(['regels' => $fout->getMessage()]);
        }

        return redirect()->route('admin.bestellingen.detail', $order)
            ->with('status', 'Interne bestelling '.$order->nummer().' is aangemaakt en de voorraad is bijgewerkt.');
    }

    public function show(Order $order): View
    {
        $order->load(['items', 'factuur']);

        return view('admin.bestellingen.show', [
            'order'      => $order,
            'statussen'  => self::STATUSSEN,
            // Producten erbij zoeken voor foto's en een link naar het beheer
            'producten'  => Product::whereIn('slug', $order->items->pluck('product_slug'))
                ->get()
                ->keyBy('slug'),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(self::STATUSSEN))],
        ], [
            'status.required' => 'Kies een status.',
            'status.in'       => 'Kies een geldige status.',
        ]);

        $nieuw = $data['status'];
        $oud = $order->status;

        if ($nieuw === $oud) {
            return back();
        }

        // Annuleren geeft de gereserveerde voorraad terug; een geannuleerde
        // bestelling heractiveren reserveert de voorraad opnieuw (niet onder 0).
        if ($nieuw === 'geannuleerd') {
            foreach ($order->items as $regel) {
                Product::where('slug', $regel->product_slug)->increment('voorraad', $regel->qty);
            }
        } elseif ($oud === 'geannuleerd') {
            foreach ($order->items as $regel) {
                Product::where('slug', $regel->product_slug)
                    ->update(['voorraad' => DB::raw('GREATEST(voorraad - '.(int) $regel->qty.', 0)')]);
            }
        }

        $order->update(['status' => $nieuw]);

        // Zodra een bestelling (ook handmatig) op betaald of verder staat,
        // hoort er een factuur te bestaan.
        if (in_array($nieuw, ['betaald', 'verzonden', 'afgerond'])) {
            $order->maakFactuur();
        }

        return back()->with('status', 'Status van '.$order->nummer().' bijgewerkt naar "'.self::STATUSSEN[$nieuw].'".');
    }
}
