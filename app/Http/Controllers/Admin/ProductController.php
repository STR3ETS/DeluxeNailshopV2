<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.producten.index', [
            'producten' => Product::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.producten.form', ['product' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->valideer($request);

        $product = new Product($this->naarKolommen($data, $request));
        $product->slug = $this->uniekeSlug($data['merk'], $data['naam']);

        if ($request->hasFile('afbeelding')) {
            $product->image = 'storage/'.$request->file('afbeelding')->store('producten', 'public');
        }

        $product->save();

        return redirect()->route('admin.producten')->with('status', '"'.$product->name.'" is toegevoegd.');
    }

    public function edit(Product $product): View
    {
        return view('admin.producten.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->valideer($request);

        $product->fill($this->naarKolommen($data, $request));
        $product->slug = $this->uniekeSlug($data['merk'], $data['naam'], $product->id);

        if ($request->hasFile('afbeelding')) {
            $product->image = 'storage/'.$request->file('afbeelding')->store('producten', 'public');
        }

        $product->save();

        return redirect()->route('admin.producten')->with('status', '"'.$product->name.'" is bijgewerkt.');
    }

    /**
     * Maakt een kopie van het product. De kopie start inactief zodat er
     * niet direct een dubbel product in de shop staat.
     */
    public function duplicate(Product $product): RedirectResponse
    {
        $kopie = $product->replicate();
        $kopie->name = $product->name.' (kopie)';
        $kopie->slug = $this->uniekeSlug($kopie->brand, $kopie->name);
        $kopie->actief = false;
        $kopie->save();

        return redirect()->route('admin.producten.bewerken', $kopie)
            ->with('status', '"'.$product->name.'" is gedupliceerd. Pas de kopie aan en vink "Actief" aan zodra hij klaar is.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.producten')->with('status', '"'.$product->name.'" is verwijderd.');
    }

    private function valideer(Request $request): array
    {
        $categorieSlugs = collect(config('shop.categories'))->pluck('slug')->implode(',');

        return $request->validate([
            'merk'         => ['required', 'string', 'max:50'],
            'naam'         => ['required', 'string', 'max:150'],
            'categorie'    => ['required', 'in:'.$categorieSlugs],
            'subcategorie' => ['nullable', 'string', 'max:50'],
            'prijs'        => ['required', 'numeric', 'min:0'],
            'oude_prijs'   => ['nullable', 'numeric', 'gt:prijs'],
            'tint'         => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}\|#[0-9a-fA-F]{6}$/'],
            'afbeelding'   => ['nullable', 'image', 'max:4096'],
            'voorraad'     => ['required', 'integer', 'min:0'],
            'beschrijving' => ['nullable', 'string', 'max:5000'],
            'kenmerken'    => ['nullable', 'array'],
            'kenmerken.*.titel' => ['nullable', 'string', 'max:120'],
            'kenmerken.*.tekst' => ['nullable', 'string', 'max:500'],
            'gebruiksaanwijzing'   => ['nullable', 'array'],
            'gebruiksaanwijzing.*' => ['nullable', 'string', 'max:500'],
            'inhoud'       => ['nullable', 'string', 'max:50'],
            'voorzorg'     => ['nullable', 'string', 'max:1000'],
            'bewaren'      => ['nullable', 'string', 'max:1000'],
        ], [
            'merk.required'      => 'Kies een merk.',
            'naam.required'      => 'Vul een productnaam in.',
            'categorie.required' => 'Kies een categorie.',
            'categorie.in'       => 'Kies een geldige categorie.',
            'prijs.required'     => 'Vul een prijs in.',
            'prijs.numeric'      => 'De prijs moet een getal zijn (bijv. 9.95).',
            'oude_prijs.numeric' => 'De oude prijs moet een getal zijn.',
            'oude_prijs.gt'      => 'De oude prijs moet hoger zijn dan de huidige prijs.',
            'tint.required'      => 'Kies een achtergrondtint.',
            'tint.regex'         => 'Kies een geldige achtergrondtint.',
            'afbeelding.image'   => 'De afbeelding moet een geldig afbeeldingsbestand zijn.',
            'afbeelding.max'     => 'De afbeelding mag maximaal 4 MB zijn.',
            'voorraad.required'  => 'Vul de voorraad in.',
            'voorraad.integer'   => 'De voorraad moet een heel getal zijn.',
            'voorraad.min'       => 'De voorraad kan niet negatief zijn.',
        ]);
    }

    private function naarKolommen(array $data, Request $request): array
    {
        [$van, $naar] = explode('|', $data['tint']);

        // Kenmerken als [titel, tekst]-paren; lege rijen vervallen
        $kenmerken = collect($data['kenmerken'] ?? [])
            ->map(fn ($rij) => [trim($rij['titel'] ?? ''), trim($rij['tekst'] ?? '')])
            ->filter(fn ($rij) => $rij[0] !== '' || $rij[1] !== '')
            ->values();

        $stappen = collect($data['gebruiksaanwijzing'] ?? [])
            ->map(fn ($stap) => trim($stap ?? ''))
            ->filter()
            ->values();

        return [
            'kenmerken'          => $kenmerken->isEmpty() ? null : $kenmerken->all(),
            'gebruiksaanwijzing' => $stappen->isEmpty() ? null : $stappen->all(),
            'inhoud'             => trim($data['inhoud'] ?? '') !== '' ? trim($data['inhoud']) : null,
            'voorzorg'           => trim($data['voorzorg'] ?? '') !== '' ? trim($data['voorzorg']) : null,
            'bewaren'            => trim($data['bewaren'] ?? '') !== '' ? trim($data['bewaren']) : null,
            'brand'       => $data['merk'],
            'name'        => $data['naam'],
            'category'    => $data['categorie'],
            'subcategory' => ($data['subcategorie'] ?? '') !== '' ? $data['subcategorie'] : null,
            'price'       => $data['prijs'],
            'old_price'   => $data['oude_prijs'] ?? null,
            'bg_from'     => $van,
            'bg_to'       => $naar,
            'voorraad'    => $data['voorraad'],
            'description' => ($data['beschrijving'] ?? '') !== '' ? $data['beschrijving'] : null,
            'actief'      => $request->boolean('actief'),
        ];
    }

    private function uniekeSlug(string $merk, string $naam, ?int $negeerId = null): string
    {
        $basis = Str::slug($merk.' '.$naam);
        $slug = $basis;
        $volgnummer = 2;

        while (Product::where('slug', $slug)
            ->when($negeerId, fn ($q) => $q->where('id', '!=', $negeerId))
            ->exists()) {
            $slug = $basis.'-'.$volgnummer++;
        }

        return $slug;
    }
}
