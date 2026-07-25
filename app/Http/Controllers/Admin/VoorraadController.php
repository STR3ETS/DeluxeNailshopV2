<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VoorraadController extends Controller
{
    public function index(): View
    {
        $producten = Product::orderBy('name')->get();

        return view('admin.voorraad.index', [
            'producten'   => $producten,
            'uitverkocht' => $producten->where('voorraad', 0)->count(),
            'bijnaOp'     => $producten->filter(fn ($p) => $p->voorraad > 0 && $p->voorraad < 10)->count(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'voorraad' => ['required', 'integer', 'min:0'],
        ], [
            'voorraad.required' => 'Vul een voorraad in.',
            'voorraad.integer'  => 'De voorraad moet een heel getal zijn.',
            'voorraad.min'      => 'De voorraad kan niet negatief zijn.',
        ]);

        $product->update(['voorraad' => $data['voorraad']]);

        return back()->with('status', 'Voorraad van "'.$product->name.'" staat nu op '.$product->voorraad.'.');
    }
}
