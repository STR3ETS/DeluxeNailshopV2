<?php

namespace App\Http\Controllers\Admin;

use App\Models\DiscountCode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InstellingController extends Controller
{
    public function index(): View
    {
        return view('admin.instellingen.index', [
            'codes' => DiscountCode::orderByDesc('id')->get(),
        ]);
    }

    public function storeKortingscode(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'        => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9\-]+$/'],
            'type'        => ['required', 'in:procent,bedrag'],
            'waarde'      => ['required', 'numeric', 'gt:0'],
            'min_bedrag'  => ['nullable', 'numeric', 'gt:0'],
            'verloopt_op' => ['nullable', 'date'],
        ], [
            'code.required'   => 'Vul een code in.',
            'code.regex'      => 'Gebruik alleen letters, cijfers en streepjes.',
            'type.required'   => 'Kies procent of bedrag.',
            'waarde.required' => 'Vul de kortingswaarde in.',
            'waarde.numeric'  => 'De waarde moet een getal zijn.',
            'waarde.gt'       => 'De waarde moet groter zijn dan 0.',
            'min_bedrag.numeric' => 'Het minimumbedrag moet een getal zijn.',
            'min_bedrag.gt'   => 'Het minimumbedrag moet groter zijn dan 0.',
            'verloopt_op.date' => 'Dit is geen geldige datum.',
        ]);

        $code = mb_strtoupper(trim($data['code']));

        if ($data['type'] === 'procent' && (float) $data['waarde'] > 100) {
            return back()->withInput()->withErrors(['waarde' => 'Een percentage kan niet hoger zijn dan 100.']);
        }

        if (DiscountCode::whereRaw('UPPER(code) = ?', [$code])->exists()) {
            return back()->withInput()->withErrors(['code' => 'Deze code bestaat al.']);
        }

        DiscountCode::create([
            'code'        => $code,
            'type'        => $data['type'],
            'waarde'      => $data['waarde'],
            'min_bedrag'  => $data['min_bedrag'] ?? null,
            'verloopt_op' => $data['verloopt_op'] ?? null,
            'actief'      => true,
        ]);

        return back()->with('status', 'Kortingscode "'.$code.'" is aangemaakt.');
    }

    public function toggleKortingscode(DiscountCode $discountCode): RedirectResponse
    {
        $discountCode->update(['actief' => ! $discountCode->actief]);

        return back()->with('status', 'Kortingscode "'.$discountCode->code.'" is '.($discountCode->actief ? 'geactiveerd' : 'gedeactiveerd').'.');
    }

    public function destroyKortingscode(DiscountCode $discountCode): RedirectResponse
    {
        $discountCode->delete();

        return back()->with('status', 'Kortingscode "'.$discountCode->code.'" is verwijderd.');
    }
}
