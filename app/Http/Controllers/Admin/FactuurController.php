<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FactuurController extends Controller
{
    public function index(): View
    {
        $facturen = Invoice::with(['order' => fn ($q) => $q->withSum('items as artikelen', 'qty')])
            ->latest('number')
            ->get();

        return view('admin.facturen.index', [
            'facturen'      => $facturen,
            'totaalBedrag'  => (float) $facturen->sum(fn ($f) => (float) $f->order->total),
            'dezeMaand'     => $facturen->filter(fn ($f) => $f->created_at->isSameMonth(now()))->count(),
        ]);
    }

    public function download(Invoice $invoice): Response
    {
        $invoice->load('order.items');

        $pdf = Pdf::loadView('admin.facturen.pdf', [
            'factuur'   => $invoice,
            'order'     => $invoice->order,
            'subtotaal' => (float) $invoice->order->total - (float) $invoice->order->shipping,
            // Prijzen zijn inclusief 21% BTW; op de factuur splitsen we dat uit
            'btw'       => (float) $invoice->order->total * 21 / 121,
            'bedrijf'   => config('shop.bedrijf'),
        ])->setPaper('a4');

        return $pdf->download('Factuur-'.$invoice->number.'.pdf');
    }
}
