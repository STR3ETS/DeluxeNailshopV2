@extends('layouts.shop')

{{-- Met header en footer, zodat een verdwaalde bezoeker direct verder kan shoppen --}}
@section('title', 'Pagina niet gevonden - ' . config('app.name'))
@section('robots', 'noindex, follow')

@section('content')

<section class="relative px-6 pt-14 pb-6 max-[1240px]:overflow-x-clip sm:pt-20">
    <div class="pointer-events-none absolute -top-10 right-[8%] h-[300px] w-[300px] rounded-full bg-accent opacity-40 blur-[60px]"></div>

    <div class="relative mx-auto max-w-[720px] text-center">
        <span class="load-reveal block font-serif text-[clamp(5rem,20vw,8.5rem)] leading-none text-primary/30 italic">404</span>
        <h1 class="load-reveal mt-3 font-serif text-[clamp(2rem,5vw,3rem)] leading-[1.1] font-normal">Deze pagina is <em class="text-primary italic">zoekgeraakt</em></h1>
        <p class="load-reveal mx-auto mt-4 max-w-[46ch] leading-[1.75] font-light text-dark-soft">De pagina die je zoekt bestaat niet (meer) of is verhuisd. Geen zorgen, hieronder vind je snel je weg terug.</p>

        <div class="load-reveal mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('producten') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.92rem] font-semibold text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep max-sm:w-full">
                Bekijk alle producten <i class="fa-light fa-arrow-right"></i>
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-4 text-[.92rem] font-semibold transition-colors hover:border-dark max-sm:w-full">
                Naar de homepage
            </a>
        </div>
    </div>

    {{-- Snel naar een categorie --}}
    <div class="load-reveal relative mx-auto mt-14 max-w-[900px] text-center">
        <h2 class="text-[.74rem] font-semibold tracking-[.22em] text-dark-soft uppercase">Of kies een categorie</h2>
        <div class="mt-5 flex flex-wrap justify-center gap-2.5">
            @foreach (config('shop.categories') as $categorie)
                <a href="{{ url('/producten') }}?categorie={{ $categorie['slug'] }}" class="inline-flex items-center gap-2.5 rounded-full border border-primary/20 bg-offwhite py-2 pr-4.5 pl-2 text-[.88rem] font-medium transition-colors hover:border-primary/50 hover:text-primary-deep">
                    <span class="h-6 w-6 rounded-[58%_42%_55%_45%/50%_60%_40%_50%]" style="background:linear-gradient(135deg,{{ $categorie['dab'][0] }},{{ $categorie['dab'][1] }})"></span>
                    {{ $categorie['name'] }}
                </a>
            @endforeach
        </div>
        <p class="mt-8 text-[.88rem] font-light text-dark-soft">Hulp nodig? Bekijk de <a href="{{ route('faq') }}" class="font-medium text-primary-deep transition-colors hover:text-primary">veelgestelde vragen</a> of <a href="https://wa.me/{{ config('shop.contact.whatsapp') }}" target="_blank" rel="noopener" class="font-medium text-primary-deep transition-colors hover:text-primary">stuur ons een WhatsApp</a>.</p>
    </div>
</section>

@endsection
