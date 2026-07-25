@extends('layouts.shop')

@section('title', 'Afrekenen - ' . config('app.name'))

@php
    $inputKlassen = 'w-full rounded-full border border-primary/20 bg-white px-5 py-3.5 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary';
    $labelKlassen = 'mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $foutKlassen  = 'mt-2 pl-4 text-[.8rem] font-medium text-red-600';

    $ingelogd = auth()->user();
    $voornaam = old('voornaam', $ingelogd ? \Illuminate\Support\Str::before($ingelogd->name, ' ') : '');
    $achternaam = old('achternaam', $ingelogd && str_contains($ingelogd->name, ' ') ? \Illuminate\Support\Str::after($ingelogd->name, ' ') : '');
@endphp

@section('content')

<section class="px-6 pt-10 pb-16"
         x-data="{
            land: @js(old('land', 'NL')),
            tarieven: @js(config('shop.verzending')),
            get tarief() { return this.tarieven[this.land] ?? this.tarieven.NL; },
            get verzend() {
                if (this.$store.cart.items.length === 0) return 0;
                return this.$store.cart.total >= this.tarief.gratis_vanaf ? 0 : this.tarief.kosten;
            },
            get totaal() { return this.$store.cart.total + this.verzend; },
         }">
    <div class="mx-auto max-w-[1100px]">

        {{-- Breadcrumb --}}
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">Afrekenen</span>
        </nav>

        <h1 class="load-reveal font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">Bijna <em class="text-primary italic">van jou</em></h1>

        {{-- Lege winkelwagen --}}
        <div x-cloak x-show="$store.cart.items.length === 0" class="mt-10 rounded-card border border-primary/15 bg-offwhite p-12 text-center">
            <i class="fa-light fa-bag-shopping mb-4 text-[2rem] text-primary"></i>
            <h2 class="mb-2 font-serif text-[1.4rem] font-medium">Je winkelwagen is nog leeg</h2>
            <p class="mb-6 font-light text-dark-soft">Voeg eerst wat moois toe, dan zien we je hier terug.</p>
            <a href="{{ route('producten') }}" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">Bekijk producten <i class="fa-light fa-arrow-right"></i></a>
        </div>

        <div x-show="$store.cart.items.length > 0" class="load-reveal mt-8 grid items-start gap-10 lg:grid-cols-[1.15fr_1fr]">

            {{-- Gegevens --}}
            <form method="POST" action="{{ route('afrekenen.plaatsen') }}"
                  @submit="$refs.winkelwagen.value = JSON.stringify($store.cart.items.map(i => ({ id: i.id, qty: i.qty })))"
                  class="flex flex-col gap-6 rounded-card border border-primary/15 bg-offwhite p-7 sm:p-8">
                @csrf
                <input type="hidden" name="winkelwagen" x-ref="winkelwagen">

                @error('winkelwagen')
                    <p class="rounded-2xl border border-red-200 bg-red-50 px-5 py-3.5 text-[.88rem] leading-[1.6] text-red-700">
                        <i class="fa-light fa-circle-exclamation mr-1.5"></i>{{ $message }}
                    </p>
                @enderror

                <div>
                    <h2 class="font-serif text-[1.25rem] font-medium">Jouw gegevens</h2>
                    @guest
                        <p class="mt-1 text-[.85rem] font-light text-dark-soft">Al klant? <a href="{{ url('/login') }}" class="font-medium text-primary-deep transition-colors hover:text-primary">Log in</a> voor sneller afrekenen.</p>
                    @endguest
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Voornaam</span>
                        <input type="text" name="voornaam" value="{{ $voornaam }}" required autocomplete="given-name" placeholder="Sophie" class="{{ $inputKlassen }}">
                        @error('voornaam')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Achternaam</span>
                        <input type="text" name="achternaam" value="{{ $achternaam }}" required autocomplete="family-name" placeholder="Jansen" class="{{ $inputKlassen }}">
                        @error('achternaam')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <label class="block">
                        <span class="{{ $labelKlassen }}">E-mailadres</span>
                        <input type="email" name="email" value="{{ old('email', $ingelogd->email ?? '') }}" required autocomplete="email" placeholder="jouw@email.nl" class="{{ $inputKlassen }}">
                        @error('email')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Telefoon <span class="font-normal normal-case">(optioneel)</span></span>
                        <input type="tel" name="telefoon" value="{{ old('telefoon') }}" autocomplete="tel" placeholder="06 12345678" class="{{ $inputKlassen }}">
                        @error('telefoon')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                </div>

                <h2 class="mt-2 font-serif text-[1.25rem] font-medium">Bezorgadres</h2>

                <label class="block">
                    <span class="{{ $labelKlassen }}">Straat en huisnummer</span>
                    <input type="text" name="straat" value="{{ old('straat') }}" required autocomplete="street-address" placeholder="Thorbeckestraat 3" class="{{ $inputKlassen }}">
                    @error('straat')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>

                <div class="grid gap-6 sm:grid-cols-[140px_1fr_150px]">
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Postcode</span>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" required autocomplete="postal-code" placeholder="6904 BS" class="{{ $inputKlassen }}">
                        @error('postcode')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Plaats</span>
                        <input type="text" name="plaats" value="{{ old('plaats') }}" required autocomplete="address-level2" placeholder="Zevenaar" class="{{ $inputKlassen }}">
                        @error('plaats')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                    <label class="block">
                        <span class="{{ $labelKlassen }}">Land</span>
                        <select name="land" x-model="land" class="{{ $inputKlassen }} cursor-pointer">
                            <option value="NL">Nederland</option>
                            <option value="BE">België</option>
                        </select>
                        @error('land')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                    </label>
                </div>

                <label class="block">
                    <span class="{{ $labelKlassen }}">Opmerking <span class="font-normal normal-case">(optioneel)</span></span>
                    <textarea name="opmerking" rows="2" placeholder="Bijv. bezorgen bij de buren" class="w-full rounded-2xl border border-primary/20 bg-white px-5 py-3.5 text-[.92rem] leading-[1.7] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">{{ old('opmerking') }}</textarea>
                    @error('opmerking')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>

                <button type="submit" class="mt-1 flex w-full items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.95rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep">
                    Bestellen en betalen <i class="fa-light fa-arrow-right"></i>
                </button>
                <p class="text-center text-[.78rem] leading-[1.6] font-light text-dark-soft"><i class="fa-light fa-lock mr-1"></i>Je rekent veilig af via Mollie met iDEAL, creditcard, Bancontact en meer.</p>
            </form>

            {{-- Besteloverzicht --}}
            <div class="lg:sticky lg:top-24">
                <div class="rounded-card border border-primary/15 bg-offwhite p-6 sm:p-7">
                    <h2 class="font-serif text-[1.25rem] font-medium">Jouw <em class="text-primary italic">bestelling</em></h2>

                    <div class="mt-2 max-h-[320px] divide-y divide-primary/10 overflow-y-auto">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex items-center gap-3 py-3.5">
                                <div class="grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-xl bg-cream-deep">
                                    <template x-if="item.image"><img :src="item.image" :alt="item.name" class="max-h-11 w-auto object-contain"></template>
                                    <template x-if="!item.image"><i class="fa-light fa-bottle-droplet text-[1.1rem] text-primary"></i></template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[.84rem] leading-tight font-medium" x-text="item.name" :title="item.name"></p>
                                    <p class="mt-0.5 text-[.66rem] font-semibold tracking-[.16em] text-primary-deep uppercase" x-text="item.brand"></p>
                                    <p class="mt-1 text-[.78rem] text-dark-soft" x-text="`${item.qty} × ${$store.cart.format(item.price)}`"></p>
                                </div>
                                <span class="font-serif text-[.95rem] font-semibold" x-text="$store.cart.format(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="border-t border-primary/15 pt-4">
                        <div class="flex items-center justify-between text-[.9rem]">
                            <span class="text-dark-soft">Subtotaal</span>
                            <span class="font-medium" x-text="$store.cart.format($store.cart.total)"></span>
                        </div>
                        <div class="mt-2 flex items-center justify-between text-[.9rem]">
                            <span class="text-dark-soft">Verzendkosten</span>
                            <span class="font-medium" x-text="verzend === 0 ? 'Gratis' : $store.cart.format(verzend)"></span>
                        </div>
                        <template x-if="verzend > 0">
                            <p class="mt-2 text-[.78rem] font-light text-dark-soft">Nog <b class="font-semibold" x-text="$store.cart.format(tarief.gratis_vanaf - $store.cart.total)"></b> tot gratis verzending <span x-text="land === 'BE' ? 'naar België' : 'binnen Nederland'"></span>.</p>
                        </template>
                        <div class="mt-4 flex items-center justify-between border-t border-primary/15 pt-4">
                            <span class="font-medium">Totaal</span>
                            <b class="font-serif text-[1.35rem]" x-text="$store.cart.format(totaal)"></b>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-[.78rem] text-dark-soft">
                    <span class="inline-flex items-center gap-2"><i class="fa-light fa-truck-fast text-primary-deep"></i>Voor 16:00 besteld, morgen in huis</span>
                    <span class="inline-flex items-center gap-2"><i class="fa-light fa-rotate-left text-primary-deep"></i>30 dagen gratis retour</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
