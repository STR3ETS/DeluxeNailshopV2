@extends('layouts.admin')

@section('title', ($product ? 'Product bewerken' : 'Nieuw product') . ' - Beheer ' . config('app.name'))

@php
    $bewerken = $product !== null;
    $inputKlassen = 'w-full rounded-full border border-primary/20 bg-offwhite px-5 py-3 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary';
    $labelKlassen = 'mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $foutKlassen  = 'mt-2 pl-4 text-[.8rem] font-medium text-red-600';
@endphp

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Producten</span>
        <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">
            @if ($bewerken)
                <em class="text-primary italic">{{ $product->name }}</em> bewerken
            @else
                Nieuw <em class="text-primary italic">product</em>
            @endif
        </h1>
    </div>
    <a href="{{ route('admin.producten') }}" class="inline-flex items-center gap-2 text-[.88rem] font-semibold text-primary-deep transition-colors hover:text-primary">
        <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar producten
    </a>
</div>

<div class="load-reveal mt-8"
     x-data="{
        merk: @js(old('merk', $product->brand ?? 'DNKa')),
        naam: @js(old('naam', $product->name ?? '')),
        categorie: @js(old('categorie', $product->category ?? '')),
        subcategorie: @js(old('subcategorie', $product->subcategory ?? '')),
        prijs: @js((string) old('prijs', $product->price ?? '')),
        oudePrijs: @js((string) old('oude_prijs', $product->old_price ?? '')),
        tint: @js(old('tint', ($product->bg_from ?? '#f6e3de') . '|' . ($product->bg_to ?? '#ecc9bf'))),
        afbeelding: @js($product?->image ? asset($product->image) : null),
        kenmerken: @js((function () use ($product) {
            $rijen = old('kenmerken', collect($product?->kenmerken ?? [])->map(fn ($k) => ['titel' => $k[0] ?? '', 'tekst' => $k[1] ?? ''])->values()->all());
            return count($rijen) ? array_values($rijen) : [['titel' => '', 'tekst' => '']];
        })()),
        stappen: @js((function () use ($product) {
            $stappen = old('gebruiksaanwijzing', $product?->gebruiksaanwijzing ?? []);
            return count($stappen) ? array_values($stappen) : [''];
        })()),
        categorieen: @js(collect(config('shop.categories'))->map(fn ($c) => ['slug' => $c['slug'], 'name' => $c['name'], 'subs' => $c['subcategories']])->values()),
        get subs() {
            const cat = this.categorieen.find(c => c.slug === this.categorie);
            return cat ? cat.subs : [];
        },
        get isSale() {
            const n = parseFloat(String(this.oudePrijs).replace(',', '.'));
            return !isNaN(n) && n > 0;
        },
        get tintVan() { return this.tint.split('|')[0]; },
        get tintNaar() { return this.tint.split('|')[1]; },
        euro(waarde) {
            const n = parseFloat(String(waarde).replace(',', '.'));
            return isNaN(n) ? '€ 0,00' : n.toLocaleString('nl-NL', { style: 'currency', currency: 'EUR' });
        },
        kiesAfbeelding(e) {
            const bestand = e.target.files[0];
            if (!bestand) return;
            const lezer = new FileReader();
            lezer.onload = () => this.afbeelding = lezer.result;
            lezer.readAsDataURL(bestand);
        },
     }">

    <div class="grid items-start gap-10 lg:grid-cols-[1fr_320px]">

        {{-- Formulier --}}
        <form method="POST" action="{{ $bewerken ? route('admin.producten.bijwerken', $product) : route('admin.producten.opslaan') }}" enctype="multipart/form-data" class="flex flex-col gap-6 rounded-card border border-primary/15 bg-offwhite p-7 sm:p-8">
            @csrf
            @if ($bewerken) @method('PUT') @endif

            <div class="grid gap-6 sm:grid-cols-2">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Merk</span>
                    <select name="merk" x-model="merk" class="{{ $inputKlassen }} cursor-pointer">
                        @foreach (config('shop.brands') as $merk)
                            <option value="{{ $merk }}">{{ $merk }}</option>
                        @endforeach
                    </select>
                    @error('merk')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Productnaam</span>
                    <input type="text" name="naam" x-model="naam" required placeholder="Bijv. Rubber Base - Clear 15ml" class="{{ $inputKlassen }}">
                    @error('naam')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Categorie</span>
                    <select name="categorie" x-model="categorie" @change="subcategorie = ''" required class="{{ $inputKlassen }} cursor-pointer">
                        <option value="" disabled>Kies een categorie…</option>
                        @foreach (config('shop.categories') as $categorie)
                            <option value="{{ $categorie['slug'] }}">{{ $categorie['name'] }}</option>
                        @endforeach
                    </select>
                    @error('categorie')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Subcategorie</span>
                    <select name="subcategorie" x-model="subcategorie" class="{{ $inputKlassen }} cursor-pointer" :disabled="subs.length === 0">
                        <option value="">- Geen -</option>
                        <template x-for="sub in subs" :key="sub.slug">
                            <option :value="sub.slug" x-text="sub.name" :selected="sub.slug === subcategorie"></option>
                        </template>
                    </select>
                    @error('subcategorie')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid gap-6 sm:grid-cols-3">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Prijs (€)</span>
                    <input type="number" name="prijs" x-model="prijs" step="0.01" min="0" required placeholder="9.95" class="{{ $inputKlassen }}">
                    @error('prijs')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Oude prijs (€)</span>
                    <input type="number" name="oude_prijs" x-model="oudePrijs" step="0.01" min="0" placeholder="Optioneel" class="{{ $inputKlassen }}">
                    @error('oude_prijs')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Voorraad</span>
                    <input type="number" name="voorraad" value="{{ old('voorraad', $product->voorraad ?? 10) }}" min="0" required class="{{ $inputKlassen }}">
                    @error('voorraad')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>

            <p class="rounded-2xl bg-cream/70 px-5 py-3.5 text-[.82rem] leading-[1.6] font-light text-dark-soft">
                <i class="fa-light fa-wand-magic-sparkles mr-1.5 text-primary-deep"></i>Badges en reviews gaan automatisch: <b class="font-semibold text-dark">Sale</b> verschijnt zodra je een oude prijs invult, <b class="font-semibold text-dark">Bestseller</b> bepaalt het systeem op basis van de verkoop van de afgelopen 30 dagen, en <b class="font-semibold text-dark">reviews</b> komen straks binnen via klanten (mail een paar dagen na aankoop).
            </p>

            <div>
                <span class="{{ $labelKlassen }}">Achtergrondtint van de kaart</span>
                <div class="flex flex-wrap gap-3">
                    @foreach (config('shop.card_tints') as $tintOptie)
                        @php $tintWaarde = $tintOptie[0].'|'.$tintOptie[1]; @endphp
                        <label class="cursor-pointer">
                            <input type="radio" name="tint" value="{{ $tintWaarde }}" x-model="tint" class="peer sr-only">
                            <span class="block h-11 w-11 rounded-full border-[3px] border-transparent transition-all duration-200 peer-checked:scale-110 peer-checked:border-primary" style="background:linear-gradient(135deg,{{ $tintOptie[0] }},{{ $tintOptie[1] }})"></span>
                        </label>
                    @endforeach
                </div>
                @error('tint')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </div>

            <div>
                <span class="{{ $labelKlassen }}">Productfoto</span>
                <label class="flex cursor-pointer items-center gap-4 rounded-2xl border border-dashed border-primary/30 bg-cream/60 px-5 py-4 transition-colors hover:border-primary">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light fa-image text-[1rem]"></i></span>
                    <span class="min-w-0">
                        <span class="block text-[.9rem] font-medium">Kies een afbeelding</span>
                        <span class="block text-[.78rem] font-light text-dark-soft">Transparante PNG werkt het mooist · max 4 MB</span>
                    </span>
                    <input type="file" name="afbeelding" accept="image/*" @change="kiesAfbeelding" class="sr-only">
                </label>
                @error('afbeelding')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </div>

            {{-- Detailpagina: beschrijving --}}
            <div class="border-t border-primary/15 pt-6">
                <h2 class="font-serif text-[1.15rem] font-medium">Beschrijving</h2>
                <p class="mt-1 text-[.8rem] font-light text-dark-soft">De introtekst bovenaan de detailpagina. Leeg laten = standaardtekst.</p>
                <textarea name="beschrijving" rows="4" placeholder="Bijv. De DNKa Fiber Base is een transparante, flexibele en sterke base gel…" class="mt-4 w-full rounded-2xl border border-primary/20 bg-offwhite px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('beschrijving', $product->description ?? '') }}</textarea>
                @error('beschrijving')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </div>

            {{-- Detailpagina: belangrijkste kenmerken --}}
            <div class="border-t border-primary/15 pt-6">
                <h2 class="font-serif text-[1.15rem] font-medium">Belangrijkste kenmerken</h2>
                <p class="mt-1 text-[.8rem] font-light text-dark-soft">Per kenmerk een korte titel en toelichting. Leeg laten = standaardkenmerken.</p>
                <div class="mt-4 flex flex-col gap-3">
                    <template x-for="(rij, i) in kenmerken" :key="i">
                        <div class="grid gap-3 sm:grid-cols-[1fr_1.5fr_auto]">
                            <input type="text" :name="`kenmerken[${i}][titel]`" x-model="rij.titel" placeholder="Bijv. HEMA- en TPO-vrij" class="{{ $inputKlassen }}">
                            <input type="text" :name="`kenmerken[${i}][tekst]`" x-model="rij.tekst" placeholder="Korte toelichting" class="{{ $inputKlassen }}">
                            <button type="button" @click="kenmerken.splice(i, 1); if (!kenmerken.length) kenmerken.push({ titel: '', tekst: '' })" class="grid h-11 w-11 place-items-center self-center justify-self-end rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Kenmerk verwijderen" title="Verwijderen">
                                <i class="fa-light fa-xmark text-[.9rem]"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="kenmerken.push({ titel: '', tekst: '' })" class="inline-flex items-center gap-2 self-start rounded-full border border-dark/20 px-5 py-2.5 text-[.85rem] font-semibold transition-colors hover:border-dark">
                        <i class="fa-light fa-plus text-[.8rem]"></i> Kenmerk toevoegen
                    </button>
                </div>
            </div>

            {{-- Detailpagina: gebruiksaanwijzing --}}
            <div class="border-t border-primary/15 pt-6">
                <h2 class="font-serif text-[1.15rem] font-medium">Gebruiksaanwijzing</h2>
                <p class="mt-1 text-[.8rem] font-light text-dark-soft">Stap voor stap. Leeg laten = standaardstappen.</p>
                <div class="mt-4 flex flex-col gap-3">
                    <template x-for="(stap, i) in stappen" :key="i">
                        <div class="grid grid-cols-[auto_1fr_auto] items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-accent-soft text-[.75rem] font-bold text-primary-deep" x-text="i + 1"></span>
                            <input type="text" :name="`gebruiksaanwijzing[${i}]`" x-model="stappen[i]" placeholder="Bijv. Bereid de nagel voor: reinig en vijl de nagelplaat." class="{{ $inputKlassen }}">
                            <button type="button" @click="stappen.splice(i, 1); if (!stappen.length) stappen.push('')" class="grid h-11 w-11 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Stap verwijderen" title="Verwijderen">
                                <i class="fa-light fa-xmark text-[.9rem]"></i>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="stappen.push('')" class="inline-flex items-center gap-2 self-start rounded-full border border-dark/20 px-5 py-2.5 text-[.85rem] font-semibold transition-colors hover:border-dark">
                        <i class="fa-light fa-plus text-[.8rem]"></i> Stap toevoegen
                    </button>
                </div>
            </div>

            {{-- Detailpagina: specificaties & bewaren --}}
            <div class="border-t border-primary/15 pt-6">
                <h2 class="font-serif text-[1.15rem] font-medium">Specificaties &amp; bewaren</h2>
                <p class="mt-1 text-[.8rem] font-light text-dark-soft">Zonder inhoud proberen we die uit de productnaam te halen (bijv. "15ml"); de andere velden krijgen anders een standaardtekst.</p>
                <div class="mt-4 flex flex-col gap-5">
                    <label class="block sm:max-w-[240px]">
                        <span class="{{ $labelKlassen }}">Inhoud</span>
                        <input type="text" name="inhoud" value="{{ old('inhoud', $product->inhoud ?? '') }}" placeholder="Bijv. 12 ml" class="{{ $inputKlassen }}">
                        @error('inhoud')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Voorzorgsmaatregelen</span>
                        <textarea name="voorzorg" rows="2" placeholder="Bijv. Vermijd contact met huid en ogen. Buiten bereik van kinderen bewaren." class="w-full rounded-2xl border border-primary/20 bg-offwhite px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('voorzorg', $product->voorzorg ?? '') }}</textarea>
                        @error('voorzorg')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Bewaarcondities</span>
                        <textarea name="bewaren" rows="2" placeholder="Bijv. Bewaren tussen +15°C en +25°C, uit direct zonlicht." class="w-full rounded-2xl border border-primary/20 bg-offwhite px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('bewaren', $product->bewaren ?? '') }}</textarea>
                        @error('bewaren')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                </div>
            </div>

            <label class="flex cursor-pointer items-center gap-2.5 pl-1 text-[.9rem]">
                <input type="checkbox" name="actief" value="1" @checked(old('actief', $product->actief ?? true)) class="h-4 w-4 accent-primary">
                Product is zichtbaar in de shop
            </label>

            <div class="mt-2 flex flex-wrap items-center gap-4">
                <button type="submit" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-8 py-3.5 text-[.92rem] font-semibold text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep">
                    <i class="fa-light fa-floppy-disk"></i> {{ $bewerken ? 'Wijzigingen opslaan' : 'Product toevoegen' }}
                </button>
                <a href="{{ route('admin.producten') }}" class="text-[.88rem] font-medium text-dark-soft transition-colors hover:text-dark">Annuleren</a>
            </div>
        </form>

        {{-- Live voorbeeld: zo ziet de kaart eruit in de shop --}}
        <div class="lg:sticky lg:top-10">
            <p class="mb-3 flex items-center gap-2 text-[.72rem] font-semibold tracking-[.18em] text-dark-soft uppercase"><i class="fa-light fa-eye text-primary-deep"></i> Voorbeeld in de shop</p>

            <div class="pointer-events-none flex flex-col overflow-hidden rounded-card bg-offwhite shadow-[0_8px_26px_-16px_color-mix(in_srgb,var(--color-dark)_20%,transparent)]">
                <div class="relative grid h-[230px] place-items-center overflow-hidden" :style="`background:linear-gradient(160deg,${tintVan},${tintNaar})`">
                    <span x-cloak x-show="isSale" class="absolute top-4 left-4 z-[2] rounded-full bg-primary px-3 py-1.5 text-[.66rem] font-semibold tracking-[.14em] text-cream uppercase">Sale</span>
                    <span class="absolute top-3.5 right-3.5 z-[2] grid h-9 w-9 place-items-center rounded-full bg-white/85"><i class="fa-light fa-heart text-[.95rem] text-dark"></i></span>
                    <img x-cloak x-show="afbeelding" :src="afbeelding" alt="" class="relative z-[1] max-h-[158px] w-auto object-contain drop-shadow-[0_14px_18px_color-mix(in_srgb,var(--color-dark)_22%,transparent)]">
                    <span x-show="!afbeelding" class="text-[.78rem] font-light text-dark-soft/70">Nog geen foto</span>
                </div>
                <div class="flex flex-1 flex-col gap-2 p-5 pb-6">
                    <span class="text-[.7rem] font-bold tracking-[.2em] text-primary-deep uppercase" x-text="merk"></span>
                    <h3 class="font-serif text-[1.12rem] leading-[1.3] font-medium" x-text="naam.trim() !== '' ? naam : 'Productnaam'"></h3>
                    <div class="flex items-center gap-[3px] text-[.7rem] text-primary">
                        @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                        <small class="ml-1.5 text-[.74rem] text-dark-soft">({{ $product->reviews ?? 0 }})</small>
                    </div>
                    <div class="mt-auto flex items-center justify-between pt-3">
                        <span class="font-serif text-[1.25rem] font-semibold">
                            <s x-cloak x-show="oudePrijs !== '' && parseFloat(oudePrijs) > 0" class="mr-1.5 text-[.85rem] font-normal text-dark-soft" x-text="euro(oudePrijs)"></s><span x-text="euro(prijs)"></span>
                        </span>
                        <span class="grid h-11 w-11 place-items-center rounded-full bg-dark text-white"><i class="fa-light fa-bag-shopping-plus text-[1rem]"></i></span>
                    </div>
                </div>
            </div>

            <p class="mt-4 text-[.78rem] leading-[1.6] font-light text-dark-soft">Het voorbeeld beweegt live mee met wat je invult - zo zie je direct hoe de kaart tussen de andere producten staat.</p>
        </div>
    </div>
</div>

@endsection
