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
      class="load-reveal mt-7 rounded-card border border-primary/15 bg-offwhite p-6 sm:p-8"
      x-data="{
        producten: @js($producten->map(fn ($p) => [
            'slug' => $p->slug,
            'naam' => $p->name,
            'merk' => $p->brand,
            'prijs' => (float) $p->price,
            'voorraad' => (int) $p->voorraad,
            'foto' => $p->image ? asset($p->image) : null,
            'tintVan' => $p->bg_from ?? '#f6e3de',
            'tintNaar' => $p->bg_to ?? '#ecc9bf',
        ])->values()),
        rijen: @js(old('regels', [['slug' => '', 'qty' => 1]])),
        openRij: null,
        zoek: '',
        product(slug) { return this.producten.find(p => p.slug === slug); },
        get gefilterd() {
            const q = this.zoek.trim().toLowerCase();
            return q === '' ? this.producten : this.producten.filter(p => (p.merk + ' ' + p.naam).toLowerCase().includes(q));
        },
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

                    {{-- Zoekbare productkiezer --}}
                    <div class="relative" @click.outside="if (openRij === i) openRij = null" @keydown.escape="openRij = null">
                        <input type="hidden" :name="`regels[${i}][slug]`" :value="rij.slug">

                        <button type="button" @click="openRij = openRij === i ? null : i; zoek = ''"
                                class="flex w-full items-center gap-3 rounded-full border bg-offwhite py-1.5 pr-5 pl-2 text-left text-[.92rem] transition-colors"
                                :class="openRij === i ? 'border-primary' : 'border-primary/20 hover:border-primary/50'">
                            <template x-if="product(rij.slug)">
                                <span class="flex min-w-0 flex-1 items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center overflow-hidden rounded-full" :style="`background:linear-gradient(160deg,${product(rij.slug).tintVan},${product(rij.slug).tintNaar})`">
                                        <img x-show="product(rij.slug).foto" :src="product(rij.slug).foto" alt="" class="max-h-7 w-auto object-contain">
                                    </span>
                                    <span class="truncate" x-text="`${product(rij.slug).merk} ${product(rij.slug).naam}`"></span>
                                </span>
                            </template>
                            <template x-if="!product(rij.slug)">
                                <span class="flex min-w-0 flex-1 items-center gap-3">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-cream-deep"><i class="fa-light fa-bottle-droplet text-[.85rem] text-dark-soft"></i></span>
                                    <span class="text-dark-soft/60">Kies een product…</span>
                                </span>
                            </template>
                            <i class="fa-light fa-chevron-down shrink-0 text-[.72rem] text-dark-soft transition-transform duration-200" :class="openRij === i ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-cloak x-show="openRij === i" x-transition.opacity.duration.150ms
                             class="absolute top-[calc(100%+8px)] right-0 left-0 z-30 overflow-hidden rounded-3xl border border-primary/15 bg-white shadow-card">
                            <div class="border-b border-primary/10 p-3">
                                <div class="relative">
                                    <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-4 -translate-y-1/2 text-[.8rem] text-dark-soft"></i>
                                    <input type="text" x-model="zoek"
                                           x-effect="openRij === i && $nextTick(() => $el.focus())"
                                           @keydown.enter.prevent="const eerste = gefilterd.find(p => p.voorraad > 0); if (eerste) { rij.slug = eerste.slug; openRij = null }"
                                           placeholder="Zoek op naam of merk…"
                                           class="w-full rounded-full border border-primary/20 bg-cream/60 py-2.5 pr-4 pl-10 text-[.88rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                                </div>
                            </div>
                            <ul class="scroll-slim max-h-[320px] overflow-y-auto overscroll-contain p-2">
                                <template x-for="p in gefilterd" :key="p.slug">
                                    <li>
                                        <button type="button" @click="rij.slug = p.slug; openRij = null" :disabled="p.voorraad === 0"
                                                class="flex w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-left transition-colors hover:bg-cream-deep disabled:cursor-not-allowed disabled:opacity-45 disabled:hover:bg-transparent"
                                                :class="rij.slug === p.slug ? 'bg-accent-soft/50' : ''">
                                            <span class="grid h-11 w-11 shrink-0 place-items-center overflow-hidden rounded-xl" :style="`background:linear-gradient(160deg,${p.tintVan},${p.tintNaar})`">
                                                <img x-show="p.foto" :src="p.foto" alt="" loading="lazy" class="max-h-9 w-auto object-contain">
                                            </span>
                                            <span class="min-w-0 flex-1">
                                                <span class="block truncate text-[.9rem] font-medium" x-text="p.naam"></span>
                                                <span class="mt-0.5 block text-[.66rem] font-bold tracking-[.14em] text-primary-deep uppercase" x-text="p.merk"></span>
                                            </span>
                                            <span class="shrink-0 text-right">
                                                <span class="block font-serif text-[.95rem] font-semibold" x-text="format(p.prijs)"></span>
                                                <span class="mt-0.5 block text-[.7rem]"
                                                      :class="p.voorraad === 0 ? 'font-semibold text-red-600' : (p.voorraad < 10 ? 'font-semibold text-orange-600' : 'text-dark-soft')"
                                                      x-text="p.voorraad === 0 ? 'Uitverkocht' : `${p.voorraad} op voorraad`"></span>
                                            </span>
                                        </button>
                                    </li>
                                </template>
                                <li x-show="gefilterd.length === 0" class="px-4 py-6 text-center text-[.85rem] font-light text-dark-soft">Geen producten gevonden voor "<span x-text="zoek"></span>"</li>
                            </ul>
                        </div>
                    </div>

                    <input type="number" :name="`regels[${i}][qty]`" x-model.number="rij.qty" min="1" :max="product(rij.slug)?.voorraad ?? 999" class="{{ $inputKlassen }} text-center [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none" aria-label="Aantal">
                    <button type="button" @click="rijen.splice(i, 1); openRij = null" :disabled="rijen.length === 1"
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
