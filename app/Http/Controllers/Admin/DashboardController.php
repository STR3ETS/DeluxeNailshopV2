<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Alleen betaalde bestellingen tellen mee; 'open' betekent sinds de
        // Mollie-koppeling dat er (nog) niet is afgerekend.
        $telbareStatussen = ['betaald', 'verzonden', 'afgerond'];

        $omzet30 = (float) Order::whereIn('status', $telbareStatussen)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('total');

        $omzet7 = (float) Order::whereIn('status', $telbareStatussen)
            ->where('created_at', '>=', now()->subDays(7))
            ->sum('total');

        $verkochtDezeMaand = (int) OrderItem::whereHas('order', fn ($q) => $q
                ->whereIn('status', $telbareStatussen)
                ->where('created_at', '>=', now()->startOfMonth()))
            ->sum('qty');

        // Betaald maar nog niet verzonden: deze wachten op actie
        $openBestellingen = Order::where('status', 'betaald')->count();

        // Bestellingen per dag, afgelopen 7 dagen (incl. dagen zonder bestellingen)
        $perDag = Order::whereIn('status', $telbareStatussen)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->countBy(fn ($order) => $order->created_at->format('Y-m-d'));

        $chart = collect(range(6, 0))->map(function ($daysAgo) use ($perDag) {
            $dag = now()->subDays($daysAgo);

            return [
                'label' => $dag->format('j') . ' ' . ['jan', 'feb', 'mrt', 'apr', 'mei', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec'][$dag->month - 1],
                'count' => $perDag[$dag->format('Y-m-d')] ?? 0,
            ];
        })->values();

        return view('admin.dashboard', compact(
            'omzet30',
            'omzet7',
            'verkochtDezeMaand',
            'openBestellingen',
            'chart',
        ));
    }
}
