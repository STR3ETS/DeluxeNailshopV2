@extends('layouts.admin')

@section('title', 'Handmatige bestelling - Beheer ' . config('app.name'))

@php
    $inputKlassen = 'w-full rounded-full border border-primary/20 bg-offwhite px-5 py-3 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary';
    $labelKlassen = 'mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $foutKlassen  = 'mt-2 pl-4 text-[.8rem] font-medium text-red-600';
@endphp

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Bestellingen</span>
        <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Handmatige <em class="text-primary italic">bestelling</em></h1>
        <p class="mt-2 max-w-[60ch] text-[.9rem] leading-[1.65] font-light text-dark-soft">Bijvoorbeeld als een medewerker iets uit de voorraad meeneemt. De voorraad wordt direct afgeboekt; de bestelling krijgt de status "Intern" en telt <b class="font-medium text-dark">niet</b> mee in de omzet.</p>
    </div>
    <a href="{{ route('admin.bestellingen') }}" class="inline-flex items-center gap-2 text-[.88rem] font-semibold text-primary-deep transition-colors hover:text-primary">
        <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar bestellingen
    </a>
</div>

<form method="POST" action="{{ route('admin.bestellingen.opslaan') }}"
      class="load-reveal mt-7 max-w-[760px] rounded-card border border-primary/15 bg-offwhite p-6 sm:p-8"
      x-data="{
        producten: @js($producten->map(fn ($p) => ['slug' => $p->slug, 'naam' => $p->brand.' '.$p->name, 'prijs' => (float) $p->price, 'voorraad' => (int) $p->voorraad])->values()),
        rijen: @js(old('regels', [['slug' => '', 'qty' => 1]])),
        product(slug) { return this.producten.find(p => p.slug === slug); },
        get waarde() { return this.rijen.reduce((som, r) => som + ((this.product(r.slug)?.prijs ?? 0) * Math.max(1, parseInt(r.qty) || 1)), 0); },
        format(bedrag) { return new Intl.NumberFormat('nl-NL', { style: 'currency', currency: 'EUR' }).format(bedrag); },
      }">
    @csrf

    @error('regels')
        <p class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 text-[.88rem] leading-[1.6] text-red-700">
            <i class="fa-light fa-circle-exclamation mr-1.5"></i>{{ $message }}
        </p>
    @enderror

    <label class="block">
        <span class="{{ $labelKlassen }}">Voor wie / omschrijving</span>
        <input type="text" name="naam" value="{{ old('naam') }}" required placeholder="Bijv. Medewerker Lisa - eigen gebruik" class="{{ $inputKlassen }}">
        @error('naam')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
    </label>

    <div class="mt-6">
        <span class="{{ $labelKlassen }}">Producten</span>
        <div class="flex flex-col gap-3">
            <template x-for="(rij, i) in rijen" :key="i">
                <div class="grid grid-cols-[minmax(0,1fr)_110px_44px] items-center gap-3">
                    <select :name="`regels[${i}][slug]`" x-model="rij.slug" required class="{{ $inputKlassen }} cursor-pointer">
                        <option value="">Kies een product…</option>
                        <template x-for="p in producten" :key="p.slug">
                            <option :value="p.slug" :selected="rij.slug === p.slug" x-text="`${p.naam} (${p.voorraad} op voorraad)`" :disabled="p.voorraad === 0 && rij.slug !== p.slug"></option>
                        </template>
                    </select>
                    <input type="number" :name="`regels[${i}][qty]`" x-model.number="rij.qty" min="1" :max="product(rij.slug)?.voorraad ?? 999" class="{{ $inputKlassen }} text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none" aria-label="Aantal">
                    <button type="button" @click="rijen.splice(i, 1)" :disabled="rijen.length === 1"
                            class="grid h-11 w-11 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark disabled:opacity-30" aria-label="Regel verwijderen">
                        <i class="fa-light fa-trash-can text-[.9rem]"></i>
                    </button>
                </div>
            </template>
        </div>
        <button type="button" @click="rijen.push({ slug: '', qty: 1 })" class="mt-3 inline-flex items-center gap-2 text-[.85rem] font-semibold text-primary-deep transition-colors hover:text-primary">
            <i class="fa-light fa-plus text-[.75rem]"></i> Product toevoegen
        </button>
    </div>

    <label class="mt-6 block">
        <span class="{{ $labelKlassen }}">Opmerking <span class="font-normal normal-case">(optioneel)</span></span>
        <textarea name="opmerking" rows="2" placeholder="Bijv. meegenomen voor gebruik in de salon" class="w-full rounded-2xl border border-primary/20 bg-offwhite px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('opmerking') }}</textarea>
        @error('opmerking')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
    </label>

    <div class="mt-7 flex flex-wrap items-center justify-between gap-4 border-t border-primary/15 pt-6">
        <p class="text-[.9rem] text-dark-soft">Waarde van de producten: <b class="font-serif text-[1.15rem] font-semibold text-dark" x-text="format(waarde)"></b></p>
        <button type="submit" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">
            <i class="fa-light fa-check"></i> Bestelling aanmaken
        </button>
    </div>
</form>

@endsection
