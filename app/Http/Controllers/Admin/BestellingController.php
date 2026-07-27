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

    public function show(Order $order): View
    {
        $order->load('items');

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

        return back()->with('status', 'Status van '.$order->nummer().' bijgewerkt naar "'.self::STATUSSEN[$nieuw].'".');
    }
}
