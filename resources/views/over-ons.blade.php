@extends('layouts.shop')

@section('title', 'Over ons - ' . config('app.name'))
@section('meta_description', 'Deluxe Nail Shop is ontstaan vanuit liefde voor het nagelvak. Zorgvuldig geselecteerde merken voor professionals die van hun vak hun passie maken.')
@section('meta_keywords', 'over ons, ons verhaal, Deluxe Nail Shop, nagelproducten Zevenaar, nail artist, nagelstyliste, DNKa, Valeri, Touch, Staleks')

@php
    /*
    |--------------------------------------------------------------------------
    | Over ons (ons verhaal)
    |--------------------------------------------------------------------------
    | Teksten aangeleverd door Lena. **tekst** wordt vetgedrukt weergegeven.
    */

    $titel = ['Meer dan een webshop voor ', 'nagelproducten', '.'];
    $intro = 'Deluxe Nail Shop is ontstaan vanuit liefde voor het nagelvak en de overtuiging dat goed werk begint met de juiste producten.';

    $verhaal = [
        'Wij geloven dat een professionele nail artist niet alleen behoefte heeft aan mooie producten, maar vooral aan producten waarop je kunt vertrouwen. Daarom selecteren we onze merken met aandacht voor kwaliteit, innovatie, gebruiksgemak en de nieuwste ontwikkelingen binnen de nagelbranche.',
        'In onze collectie vind je merken met ieder hun eigen kracht. Van **DNKa\' en Valeri** tot **Touch en Staleks**, zorgvuldig gekozen voor professionals die hun vak serieus nemen en iedere behandeling naar een hoger niveau willen brengen.',
    ];

    $meerDan = ['Maar Deluxe Nail Shop gaat voor ons om ', 'meer dan producten', '.'];
    $passie = 'Het gaat om **creativiteit, vakmanschap en passie voor het vak**. Om inspiratie opdoen, nieuwe technieken ontdekken en blijven groeien als professional.';
    $pijlers = [
        ['icon' => 'fa-palette',        'title' => 'Creativiteit'],
        ['icon' => 'fa-hand-sparkles',  'title' => 'Vakmanschap'],
        ['icon' => 'fa-heart',          'title' => 'Passie voor het vak'],
    ];

    $slogan = ['Jij creëert.', 'Wij zorgen voor de tools.'];
    $afsluiter = 'Voor professionals die van hun vak hun passie maken.';

    // **vet** omzetten naar <strong>, na het escapen van de tekst
    $opmaak = fn (string $tekst) => preg_replace('/\*\*(.+?)\*\*/', '<strong class="font-semibold text-dark">$1</strong>', e($tekst));

    $contact = config('shop.contact');
@endphp

@section('content')

{{-- Paginakop --}}
<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[1240px]">
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">Over ons</span>
        </nav>

        <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_.9fr] lg:gap-16">
            <div class="load-reveal">
                <span class="mb-4 inline-block text-[.74rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Ons verhaal</span>
                <h1 class="font-serif text-[clamp(2.2rem,4.4vw,3.6rem)] leading-[1.08] font-normal">{{ $titel[0] }}<em class="text-primary italic">{{ $titel[1] }}</em>{{ $titel[2] }}</h1>
                <p class="mt-6 max-w-[52ch] text-[1.08rem] leading-[1.8] font-light text-dark-soft">{{ $intro }}</p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('producten') }}" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-[translate,background-color] duration-300 ease-spring hover:-translate-y-[3px] hover:bg-primary-deep">
                        Bekijk ons assortiment <i class="fa-light fa-arrow-right"></i>
                    </a>
                    <a href="#winkel" class="inline-flex items-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] transition-[translate,border-color] duration-300 ease-spring hover:-translate-y-[3px] hover:border-dark">
                        Kom langs
                    </a>
                </div>
            </div>

            <div class="load-reveal relative flex min-h-[380px] flex-col overflow-hidden rounded-[calc(var(--radius)+8px)] bg-dark px-8 py-10 text-cream sm:px-11">
                <span class="pointer-events-none absolute top-6 right-7 font-serif text-[clamp(4rem,8vw,6.5rem)] leading-none whitespace-nowrap text-gold italic opacity-[.14]">Deluxe</span>
                <img src="{{ asset('logo/deluxenailshop_transp_goud_v1.png') }}" alt="" class="mb-auto h-14 w-auto self-start">
                <span class="mt-10 h-px w-14 bg-gold"></span>
                <p class="mt-5 font-serif text-[clamp(1.7rem,2.8vw,2.3rem)] leading-[1.25] font-normal">{{ $slogan[0] }}<br><em class="text-gold italic">{{ $slogan[1] }}</em></p>
                <p class="mt-5 text-[.72rem] tracking-[.18em] uppercase opacity-60">{{ config('app.name') }} · {{ $afsluiter }}</p>
            </div>
        </div>
    </div>
</section>

{{-- Verhaal --}}
<section class="px-6 pb-[5.5rem]">
    <div class="reveal mx-auto flex max-w-[760px] flex-col gap-6">
        @foreach ($verhaal as $alinea)
            <p class="text-[1.05rem] leading-[1.9] font-light text-dark-soft">{!! $opmaak($alinea) !!}</p>
        @endforeach
    </div>
</section>

{{-- Meer dan producten --}}
<section class="bg-linear-to-b from-cream to-cream-deep px-6 py-[5.5rem]">
    <div class="mx-auto max-w-[1000px] text-center">
        <h2 class="reveal mx-auto max-w-[30ch] font-serif text-[clamp(1.9rem,3.4vw,2.8rem)] leading-[1.15] font-normal">{{ $meerDan[0] }}<em class="text-primary italic">{{ $meerDan[1] }}</em>{{ $meerDan[2] }}</h2>
        <p class="reveal mx-auto mt-5 max-w-[58ch] text-[1.05rem] leading-[1.85] font-light text-dark-soft">{!! $opmaak($passie) !!}</p>
        <div class="mt-12 grid gap-5 sm:grid-cols-3">
            @foreach ($pijlers as $pijler)
                <div class="reveal flex flex-col items-center gap-4 rounded-card border border-primary/15 bg-offwhite px-6 py-8">
                    <span class="grid h-12 w-12 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light {{ $pijler['icon'] }} text-[1.15rem]"></i></span>
                    <h3 class="font-serif text-[1.3rem] font-medium">{{ $pijler['title'] }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Winkel & contact --}}
<section id="winkel" class="scroll-mt-28 px-6 pt-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div class="reveal relative grid gap-10 overflow-hidden rounded-[calc(var(--radius)+10px)] bg-linear-[120deg] from-primary to-primary-deep px-8 py-12 text-white before:absolute before:-top-[140px] before:-left-20 before:h-[300px] before:w-[300px] before:rounded-full before:bg-white/10 before:content-[''] sm:px-12 lg:grid-cols-[1.1fr_1fr] lg:items-center">
            <div class="relative z-[1]">
                <h2 class="font-serif text-[clamp(1.8rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Kom langs in <em class="italic">Zevenaar</em></h2>
                <p class="mt-3 max-w-[44ch] font-light opacity-90">Liever eerst zien en voelen? Je bent welkom in onze winkel. Of neem contact op, we helpen je graag persoonlijk verder.</p>
                <div class="mt-7 flex flex-wrap items-center gap-3">
                    <a href="https://wa.me/{{ $contact['whatsapp'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">
                        <i class="fa-brands fa-whatsapp text-[1.05rem]"></i> WhatsApp
                    </a>
                    <a href="mailto:info@deluxenailshop.nl" class="inline-flex items-center gap-2.5 rounded-full bg-white/95 px-7 py-3.5 text-[.9rem] font-semibold text-dark transition-colors hover:bg-white">
                        <i class="fa-light fa-envelope"></i> Stuur een e-mail
                    </a>
                </div>
            </div>

            <ul class="relative z-[1] flex flex-col gap-5 rounded-card bg-white/10 p-6 sm:p-7">
                <li class="flex items-start gap-3.5">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white/15"><i class="fa-light fa-location-dot"></i></span>
                    <span>
                        <small class="block text-[.68rem] tracking-[.12em] uppercase opacity-75">Adres</small>
                        <span class="font-medium">{{ $contact['adres'] }}</span>
                    </span>
                </li>
                <li class="flex items-start gap-3.5">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white/15"><i class="fa-light fa-clock"></i></span>
                    <span>
                        <small class="block text-[.68rem] tracking-[.12em] uppercase opacity-75">Openingstijden</small>
                        @foreach ($contact['openingstijden'] as $regel)
                            <span class="block font-medium">{{ $regel }}</span>
                        @endforeach
                    </span>
                </li>
                <li class="flex items-start gap-3.5">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white/15"><i class="fa-light fa-phone"></i></span>
                    <span>
                        <small class="block text-[.68rem] tracking-[.12em] uppercase opacity-75">Telefoon & WhatsApp</small>
                        <a href="tel:+{{ $contact['whatsapp'] }}" class="font-medium transition-opacity hover:opacity-80">{{ $contact['telefoon'] }}</a>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</section>

@endsection
