<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class FactuurController extends Controller
{
    public function index(): View
    {
        $facturen = Invoice::with(['order' => fn ($q) => $q->withSum('items as artikelen', 'qty')])
            ->latest('id')
            ->get()
            ->sortByDesc('number')
            ->values();

        return view('admin.facturen.index', [
            'facturen'      => $facturen,
            'totaalBedrag'  => (float) $facturen->sum(fn ($f) => $f->totaalBedrag()),
            'dezeMaand'     => $facturen->filter(fn ($f) => $f->created_at->isSameMonth(now()))->count(),
            'openstaand'    => $facturen->filter(fn ($f) => ! $f->paid)->count(),
        ]);
    }

    /**
     * Formulier voor een handmatige factuur, bijvoorbeeld voor een verkoop in
     * de winkel of aan een salon. Krijgt het volgende nummer in dezelfde reeks
     * als de facturen van webshopbestellingen.
     */
    public function create(): View
    {
        return view('admin.facturen.form', [
            'volgendNummer' => Invoice::volgendNummer(vergrendel: false),
            'producten'     => Product::orderBy('brand')->orderBy('name')->get(['name', 'brand', 'price']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $gegevens = $request->validate([
            'naam'       => ['required', 'string', 'max:150'],
            'bedrijf'    => ['nullable', 'string', 'max:150'],
            'email'      => ['nullable', 'email', 'max:255'],
            'adres'      => ['nullable', 'string', 'max:255'],
            'postcode'   => ['nullable', 'string', 'max:10'],
            'plaats'     => ['nullable', 'string', 'max:100'],
            'land'       => ['nullable', 'in:NL,BE'],
            'btw_nummer' => ['nullable', 'string', 'max:30'],
            'betaald'    => ['required', 'boolean'],
            'opmerking'  => ['nullable', 'string', 'max:1000'],
            'regels'     => ['required', 'array', 'min:1'],
            'regels.*.omschrijving' => ['required', 'string', 'max:255'],
            'regels.*.aantal'       => ['required', 'integer', 'min:1', 'max:9999'],
            'regels.*.prijs'        => ['required', 'numeric', 'between:-99999,99999'],
        ], [
            'naam.required'   => 'Vul de naam van de klant in.',
            'email.email'     => 'Dit is geen geldig e-mailadres.',
            'regels.required' => 'Voeg minimaal één regel toe.',
            'regels.min'      => 'Voeg minimaal één regel toe.',
            'regels.*.omschrijving.required' => 'Vul bij elke regel een omschrijving in.',
            'regels.*.aantal.required' => 'Vul bij elke regel een aantal in.',
            'regels.*.aantal.min'      => 'Het aantal moet minimaal 1 zijn.',
            'regels.*.prijs.required'  => 'Vul bij elke regel een prijs in.',
            'regels.*.prijs.numeric'   => 'De prijs moet een bedrag zijn, bijv. 12.50.',
        ]);

        $regels = collect($gegevens['regels'])->map(fn ($r) => [
            'description' => trim($r['omschrijving']),
            'qty'         => (int) $r['aantal'],
            'price'       => round((float) $r['prijs'], 2),
        ])->values();

        $totaal = round($regels->sum(fn ($r) => $r['price'] * $r['qty']), 2);

        if ($totaal < 0) {
            return back()->withInput()->withErrors(['regels' => 'Het totaal van de factuur kan niet negatief zijn.']);
        }

        $factuur = Invoice::handmatig([
            'name'       => trim($gegevens['naam']),
            'company'    => $gegevens['bedrijf'] ?? null,
            'email'      => $gegevens['email'] ?? null,
            'address'    => $gegevens['adres'] ?? null,
            'postcode'   => $gegevens['postcode'] ?? null,
            'city'       => $gegevens['plaats'] ?? null,
            'country'    => $gegevens['land'] ?? null,
            'vat_number' => $gegevens['btw_nummer'] ?? null,
            'lines'      => $regels->all(),
            'total'      => $totaal,
            'paid'       => (bool) $gegevens['betaald'],
            'note'       => $gegevens['opmerking'] ?? null,
        ]);

        return redirect()->route('admin.facturen')
            ->with('status', 'Factuur '.$factuur->number.' voor '.$factuur->klantNaam().' is aangemaakt.')
            ->with('nieuweFactuur', $factuur->id);
    }

    /**
     * Zet een openstaande handmatige factuur op betaald (of terug).
     */
    public function toggleBetaald(Invoice $invoice): RedirectResponse
    {
        abort_unless($invoice->isHandmatig(), 404);

        $invoice->update(['paid' => ! $invoice->paid]);

        return back()->with('status', 'Factuur '.$invoice->number.' staat nu op "'.($invoice->paid ? 'Betaald' : 'Nog te betalen').'".');
    }

    public function download(Invoice $invoice): Response
    {
        $invoice->loadMissing('order.items');

        $pdf = Pdf::loadView('admin.facturen.pdf', [
            'factuur' => $invoice,
            'g'       => $invoice->pdfGegevens(),
            'bedrijf' => config('shop.bedrijf'),
        ])->setPaper('a4');

        return $pdf->download('Factuur-'.$invoice->number.'.pdf');
    }
}
