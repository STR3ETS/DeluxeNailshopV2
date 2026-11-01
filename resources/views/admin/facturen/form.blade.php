@extends('layouts.admin')

@section('title', 'Handmatige factuur - Beheer ' . config('app.name'))

@php
    $inputKlassen = 'w-full rounded-full border border-primary/20 bg-offwhite px-5 py-3 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary';
    $labelKlassen = 'mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $foutKlassen  = 'mt-2 pl-4 text-[.8rem] font-medium text-red-600';
    $kopKlassen   = 'block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $optioneel    = '<span class="font-normal normal-case">(optioneel)</span>';
@endphp

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Facturen</span>
        <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Handmatige <em class="text-primary italic">factuur</em></h1>
        <p class="mt-2 max-w-[62ch] text-[.9rem] leading-[1.65] font-light text-dark-soft">Bijvoorbeeld voor een verkoop in de winkel of aan een salon. De factuur krijgt het volgende nummer in dezelfde reeks als de webshopbestellingen: <b class="font-semibold text-dark">{{ $volgendNummer }}</b>. De voorraad verandert hierdoor niet.</p>
    </div>
    <a href="{{ route('admin.facturen') }}" class="inline-flex items-center gap-2 text-[.88rem] font-semibold text-primary-deep transition-colors hover:text-primary">
        <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar facturen
    </a>
</div>

<form method="POST" action="{{ route('admin.facturen.opslaan') }}"
      class="load-reveal mt-7 flex flex-col gap-6"
      x-data="{
        producten: @js($producten->map(fn ($p) => ['label' => $p->brand.' '.$p->name, 'prijs' => (float) $p->price])->values()),
        rijen: @js(old('regels', [['omschrijving' => '', 'aantal' => 1, 'prijs' => '']])),
        betaald: @js((string) old('betaald', '1')),
        vulPrijs(rij) {
            const p = this.producten.find(p => p.label === rij.omschrijving);
            if (p) rij.prijs = p.prijs;
        },
        get totaal() { return this.rijen.reduce((som, r) => som + (parseFloat(r.prijs) || 0) * Math.max(1, parseInt(r.aantal) || 1), 0); },
        format(bedrag) { return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(bedrag); },
      }">
    @csrf

    <datalist id="product-suggesties">
        @foreach ($producten as $p)
            <option value="{{ $p->brand }} {{ $p->name }}"></option>
        @endforeach
    </datalist>

    {{-- Klant --}}
    <div class="rounded-card border border-primary/15 bg-offwhite p-5 sm:p-8">
        <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-user text-[.95rem] text-primary-deep"></i> Klant</h2>

        <div class="mt-5 grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="{{ $labelKlassen }}">Naam</span>
                <input type="text" name="naam" value="{{ old('naam') }}" required placeholder="Voor- en achternaam" class="{{ $inputKlassen }}">
                @error('naam')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>
            <label class="block">
                <span class="{{ $labelKlassen }}">Bedrijfsnaam {!! $optioneel !!}</span>
                <input type="text" name="bedrijf" value="{{ old('bedrijf') }}" placeholder="Bijv. naam van de salon" class="{{ $inputKlassen }}">
                @error('bedrijf')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>
            <label class="block">
                <span class="{{ $labelKlassen }}">E-mail {!! $optioneel !!}</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="klant@voorbeeld.nl" class="{{ $inputKlassen }}">
                @error('email')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>
            <label class="block">
                <span class="{{ $labelKlassen }}">BTW-nummer klant {!! $optioneel !!}</span>
                <input type="text" name="btw_nummer" value="{{ old('btw_nummer') }}" placeholder="NL000000000B00" class="{{ $inputKlassen }} uppercase placeholder:normal-case">
                @error('btw_nummer')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>
            <label class="block sm:col-span-2">
                <span class="{{ $labelKlassen }}">Straat en huisnummer {!! $optioneel !!}</span>
                <input type="text" name="adres" value="{{ old('adres') }}" class="{{ $inputKlassen }}">
                @error('adres')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>
            <div class="grid grid-cols-[minmax(0,1fr)_minmax(0,1.6fr)] gap-5 sm:col-span-2 sm:grid-cols-[160px_minmax(0,1fr)_200px]">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Postcode</span>
                    <input type="text" name="postcode" value="{{ old('postcode') }}" class="{{ $inputKlassen }} uppercase">
                    @error('postcode')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Plaats</span>
                    <input type="text" name="plaats" value="{{ old('plaats') }}" class="{{ $inputKlassen }}">
                    @error('plaats')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="col-span-2 block sm:col-span-1">
                    <span class="{{ $labelKlassen }}">Land</span>
                    <select name="land" class="{{ $inputKlassen }} cursor-pointer">
                        <option value="">Niet vermelden</option>
                        <option value="NL" @selected(old('land', 'NL') === 'NL')>Nederland</option>
                        <option value="BE" @selected(old('land') === 'BE')>België</option>
                    </select>
                    @error('land')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>
        </div>
    </div>

    {{-- Regels --}}
    <div class="rounded-card border border-primary/15 bg-offwhite p-5 sm:p-8">
        <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-list text-[.95rem] text-primary-deep"></i> Regels</h2>
        <p class="mt-1.5 text-[.82rem] leading-[1.6] font-light text-dark-soft">Typ een omschrijving of kies een product uit de suggesties; de prijs wordt dan vanzelf ingevuld. Prijzen zijn inclusief 21% BTW.</p>

        @error('regels')
            <p class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 text-[.88rem] leading-[1.6] text-red-700">
                <i class="fa-light fa-circle-exclamation mr-1.5"></i>{{ $message }}
            </p>
        @enderror
        @if ($errors->has('regels.*'))
            <p class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 text-[.88rem] leading-[1.6] text-red-700">
                <i class="fa-light fa-circle-exclamation mr-1.5"></i>{{ collect($errors->get('regels.*'))->flatten()->first() }}
            </p>
        @endif

        <div class="mt-5 hidden grid-cols-[minmax(0,1fr)_90px_140px_44px] gap-3 px-1 sm:grid">
            <span class="{{ $kopKlassen }}">Omschrijving</span>
            <span class="{{ $kopKlassen }} text-center">Aantal</span>
            <span class="{{ $kopKlassen }}">Prijs p/st</span>
        </div>

        <div class="mt-3 flex flex-col gap-3">
            <template x-for="(rij, i) in rijen" :key="i">
                <div class="grid grid-cols-[90px_minmax(0,1fr)_44px] items-center gap-3 border-b border-primary/10 pb-3 last:border-b-0 last:pb-0 sm:grid-cols-[minmax(0,1fr)_90px_140px_44px] sm:border-b-0 sm:pb-0">
                    <input type="text" :name="`regels[${i}][omschrijving]`" x-model="rij.omschrijving" @change="vulPrijs(rij)" list="product-suggesties" required placeholder="Omschrijving" aria-label="Omschrijving"
                           class="{{ $inputKlassen }} col-span-3 sm:col-span-1">
                    <input type="number" :name="`regels[${i}][aantal]`" x-model.number="rij.aantal" min="1" max="9999" required aria-label="Aantal"
                           class="{{ $inputKlassen }} text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                    <span class="relative block">
                        <span class="pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft">€</span>
                        <input type="number" :name="`regels[${i}][prijs]`" x-model="rij.prijs" step="0.01" inputmode="decimal" required placeholder="0,00" aria-label="Prijs per stuk"
                               class="{{ $inputKlassen }} pl-10 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
                    </span>
                    <button type="button" @click="rijen.splice(i, 1)" :disabled="rijen.length === 1"
                            class="grid h-11 w-11 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark disabled:opacity-30" aria-label="Regel verwijderen">
                        <i class="fa-light fa-trash-can text-[.9rem]"></i>
                    </button>
                </div>
            </template>
        </div>
        <button type="button" @click="rijen.push({ omschrijving: '', aantal: 1, prijs: '' })" class="mt-4 inline-flex items-center gap-2 text-[.85rem] font-semibold text-primary-deep transition-colors hover:text-primary">
            <i class="fa-light fa-plus text-[.75rem]"></i> Regel toevoegen
        </button>
    </div>

    {{-- Betaling + opmerking --}}
    <div class="rounded-card border border-primary/15 bg-offwhite p-5 sm:p-8">
        <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-credit-card text-[.95rem] text-primary-deep"></i> Betaling</h2>

        <div class="mt-5 flex flex-wrap gap-3">
            <label class="cursor-pointer">
                <input type="radio" name="betaald" value="1" x-model="betaald" class="peer sr-only">
                <span class="inline-flex items-center gap-2 rounded-full border px-5 py-2.5 text-[.88rem] font-medium transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-primary/40"
                      :class="betaald === '1' ? 'border-primary bg-primary text-white' : 'border-dark/15 text-dark-soft hover:border-dark/40'">
                    <i class="fa-light fa-circle-check"></i> Al betaald
                </span>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="betaald" value="0" x-model="betaald" class="peer sr-only">
                <span class="inline-flex items-center gap-2 rounded-full border px-5 py-2.5 text-[.88rem] font-medium transition-colors peer-focus-visible:ring-2 peer-focus-visible:ring-primary/40"
                      :class="betaald === '0' ? 'border-primary bg-primary text-white' : 'border-dark/15 text-dark-soft hover:border-dark/40'">
                    <i class="fa-light fa-hourglass-half"></i> Nog te betalen
                </span>
            </label>
        </div>
        <p class="mt-3 text-[.8rem] leading-[1.6] font-light text-dark-soft"
           x-text="betaald === '1' ? 'De factuur krijgt een Betaald-stempel.' : 'Op de factuur staat het verzoek om binnen 14 dagen te betalen onder vermelding van het factuurnummer. Zet hem later op betaald in het facturenoverzicht.'"></p>

        <label class="mt-6 block">
            <span class="{{ $labelKlassen }}">Opmerking op de factuur {!! $optioneel !!}</span>
            <textarea name="opmerking" rows="2" placeholder="Bijv. afgehaald in de winkel op 1 november" class="w-full rounded-2xl border border-primary/20 bg-offwhite px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('opmerking') }}</textarea>
            @error('opmerking')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
        </label>

        <div class="mt-7 flex flex-wrap items-center justify-between gap-4 border-t border-primary/15 pt-6">
            <p class="text-[.9rem] text-dark-soft">Totaal incl. BTW: <b class="font-serif text-[1.15rem] font-semibold text-dark" x-text="format(totaal)"></b></p>
            <button type="submit" class="inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep max-sm:w-full">
                <i class="fa-light fa-file-invoice"></i> Factuur aanmaken
            </button>
        </div>
    </div>
</form>

@endsection
