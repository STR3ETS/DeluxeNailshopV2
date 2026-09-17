@extends('layouts.shop')

@section('title', 'Mijn account - ' . config('app.name'))

@php
    $voornaam = \Illuminate\Support\Str::before(auth()->user()->name, ' ');

    // Persoonlijke kortingscode uit de nieuwsbriefinschrijving (op e-mailadres)
    $welkomstCode = \App\Models\NewsletterSubscriber::voorEmail(auth()->user()->email)?->discountCode;
    $nieuwsbriefKorting = config('shop.nieuwsbrief.korting_procent');

    // Eerste opzet van het klantenportaal; deze onderdelen bouwen we later uit
    $accountBlokken = [
        ['icon' => 'fa-box-open', 'title' => 'Bestellingen', 'text' => 'Hier zie je straks je bestellingen, de status en je facturen.'],
        ['icon' => 'fa-user',     'title' => 'Mijn gegevens', 'text' => 'Beheer straks je adres- en accountgegevens voor sneller afrekenen.'],
        ['icon' => 'fa-heart',    'title' => 'Favorieten',    'text' => 'Bewaar je favoriete producten en kleuren op één plek.'],
    ];
@endphp

@section('content')

<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[1200px]">

        <div class="load-reveal mb-10 flex flex-wrap items-end justify-between gap-6">
            <div>
                <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">Hoi <em class="text-primary italic">{{ $voornaam }}</em></h1>
                <p class="mt-2 font-light text-dark-soft">Welkom in jouw klantenportaal.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2.5 rounded-full border border-dark/20 px-6 py-3 text-[.88rem] font-semibold transition-colors hover:border-dark">
                    Uitloggen <i class="fa-light fa-arrow-right-from-bracket text-[.8rem]"></i>
                </button>
            </form>
        </div>

        {{-- Welkomstkorting (nieuwsbrief) --}}
        <div class="load-reveal relative mb-5 overflow-hidden rounded-card bg-linear-[120deg] from-primary to-primary-deep px-6 py-7 text-white before:absolute before:-top-[120px] before:-right-16 before:h-[240px] before:w-[240px] before:rounded-full before:bg-white/10 before:content-[''] sm:px-8">
            <div class="relative z-[1] flex flex-wrap items-center justify-between gap-x-10 gap-y-6">
                <div class="flex items-start gap-4">
                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white/15"><i class="fa-light fa-ticket text-[1.05rem]"></i></span>
                    <div>
                        @if ($welkomstCode)
                            <h2 class="font-serif text-[1.35rem] font-medium">{{ $nieuwsbriefKorting }}% korting op je <em class="italic">eerste bestelling</em></h2>
                            <p class="mt-1 max-w-[52ch] text-[.9rem] leading-[1.65] font-light opacity-90">
                                @if ($welkomstCode->isOpgebruikt())
                                    Je hebt je persoonlijke kortingscode al gebruikt. Bedankt voor je bestelling!
                                @else
                                    Bedankt voor je inschrijving! Kopieer je persoonlijke code en vul hem in bij het afrekenen. De code is eenmalig te gebruiken.
                                @endif
                            </p>
                        @else
                            <h2 class="font-serif text-[1.35rem] font-medium">Ontvang <em class="italic">{{ $nieuwsbriefKorting }}% korting</em></h2>
                            <p class="mt-1 max-w-[52ch] text-[.9rem] leading-[1.65] font-light opacity-90">Schrijf je in voor de nieuwsbrief en krijg direct een persoonlijke kortingscode voor je eerste bestelling.</p>
                        @endif
                    </div>
                </div>

                @if ($welkomstCode)
                    <div x-data="{
                            gekopieerd: false,
                            async kopieer() {
                                const code = this.$refs.code.textContent.trim();
                                try {
                                    await navigator.clipboard.writeText(code);
                                } catch {
                                    const bereik = document.createRange();
                                    bereik.selectNodeContents(this.$refs.code);
                                    const selectie = window.getSelection();
                                    selectie.removeAllRanges();
                                    selectie.addRange(bereik);
                                    document.execCommand('copy');
                                    selectie.removeAllRanges();
                                }
                                this.gekopieerd = true;
                                setTimeout(() => this.gekopieerd = false, 2000);
                            },
                         }" class="flex items-center gap-2 rounded-full border border-dashed border-white/60 bg-white/10 py-1.5 pr-1.5 pl-5">
                        <span x-ref="code" class="font-mono text-[1rem] font-semibold tracking-[.12em] {{ $welkomstCode->isOpgebruikt() ? 'line-through opacity-60' : '' }}">{{ $welkomstCode->code }}</span>
                        @if ($welkomstCode->isOpgebruikt())
                            <span class="rounded-full bg-white/15 px-4 py-2.5 text-[.8rem] font-semibold">Gebruikt</span>
                        @else
                            <button type="button" @click="kopieer()" class="inline-flex items-center gap-2 rounded-full bg-white px-4.5 py-2.5 text-[.82rem] font-semibold text-dark transition-colors hover:bg-cream">
                                <i class="fa-light text-[.85rem]" :class="gekopieerd ? 'fa-check' : 'fa-copy'"></i>
                                <span x-text="gekopieerd ? 'Gekopieerd' : 'Kopieer code'" aria-live="polite">Kopieer code</span>
                            </button>
                        @endif
                    </div>
                @else
                    <form method="POST" action="{{ route('nieuwsbrief.inschrijven') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                        <button type="submit" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-6 py-3.5 text-[.88rem] font-semibold text-white transition-colors hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">
                            Inschrijven en code ontvangen <i class="fa-light fa-arrow-right"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="load-reveal grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($accountBlokken as $blok)
                <div class="relative rounded-card border border-primary/15 bg-offwhite p-6">
                    <span class="absolute top-5 right-5 rounded-full border border-dark/15 px-3 py-1 text-[.68rem] font-semibold tracking-[.1em] text-dark-soft uppercase">Binnenkort</span>
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light {{ $blok['icon'] }} text-[1.05rem]"></i></span>
                    <h2 class="mt-4 font-serif text-[1.2rem] font-medium">{{ $blok['title'] }}</h2>
                    <p class="mt-1.5 text-[.9rem] leading-[1.65] font-light text-dark-soft">{{ $blok['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
