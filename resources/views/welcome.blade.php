@extends('layouts.shop')

@section('title', config('app.name') . ' - Professionele nagelproducten voor salon en thuis')
@section('meta_description', 'Dé webshop voor professionele nagelproducten. Shop rubber base, gellak, builder gel, acrygel en nail art van DNKa\', Valeri en Touch. Gratis verzending vanaf €75 (NL) en €100 (BE).')
@section('meta_keywords', 'nagelproducten, nagelproducten kopen, gellak, gelpolish, rubber base, cover base, builder gel, acrygel, polygel, nail art, DNKa, Valeri, Touch, nagelstyliste, salonkwaliteit')

@php
    /*
    |--------------------------------------------------------------------------
    | Content van de homepage
    |--------------------------------------------------------------------------
    | Header, footer en categorieën zijn gedeeld: zie partials/ en
    | config/shop.php. Kleuren en fonts komen uit config/theme.php.
    */

    $tagline = 'Professionele nagelproducten';

    $heroText = 'Van een sterke basis tot de perfecte kleur: ontdek zorgvuldig geselecteerde producten van Valeri, DNKa\' en Touch. Voor nagelstylisten die geen genoegen nemen met "goed genoeg".';

    $stats = [
        ['count' => 12000, 'label' => 'Tevreden klanten'],
        ['count' => 350,   'label' => 'Producten'],
        ['text'  => '4.9', 'star' => true, 'label' => 'Beoordeling'],
    ];

    // Waaier in de hero: [kleur boven, kleur onder] per swatch + hoek in graden
    $swatches = [
        ['#f7e0e0', '#ecc6c8'],
        ['#f2b39e', '#e18b73'],
        ['#e2758f', '#c94f6d'],
        ['#a63d55', '#82293f'],
        ['#b8829d', '#96617e'],
        ['#e9d3e4', '#d1b2ca'],
        ['#f6e6e1', '#e3c6bf'],
    ];
    $angles = [-54, -36, -18, 0, 18, 36, 54];
    $fanCaption = ['Onze kleurencollectie.', 'Kleurrijk en eindeloos creatief.'];

    // Slanke strip met kernbeloftes, direct onder de hero (Font Awesome-iconen)
    $uspStrip = [
        ['icon' => 'fa-sparkles',     'text' => 'Salonkwaliteit van DNKa\', Valeri & Touch'],
        ['icon' => 'fa-comments',     'text' => 'Persoonlijk productadvies'],
        ['icon' => 'fa-shield-check', 'text' => 'Veilig betalen met iDEAL & Bancontact'],
    ];

    // Merkpanelen: naam, slogan en tekst (aangeleverd door Lena)
    $brandPanels = [
        ['name' => 'DNKa\'', 'style' => 'dark',  'tagline' => 'The code of your beauty', 'text' => 'Een premium Oekraïens nail brand waarin schoonheid, esthetiek en professionaliteit samenkomen. Ontwikkeld voor nail artists die comfortabel willen werken met producten die kwaliteit en trend combineren.'],
        ['name' => 'Valeri', 'style' => 'blush', 'tagline' => 'Color. Create. Inspire.', 'text' => 'Een Oekraïens nail brand met een passie voor kleur, kwaliteit en professionele nagelstyling. Ontdek de collectie van Valeri: van dagelijkse essentials tot producten voor jouw meest creatieve designs.'],
        ['name' => 'Touch',  'style' => 'gold',  'tagline' => 'From master to master', 'text' => 'Een professioneel Oekraïens nail brand, ontwikkeld vanuit de praktijk door een nail artist voor nail artists. Innovatieve producten die jouw werk makkelijker maken en ruimte geven aan creativiteit.'],
    ];

    $nieuwsbriefKorting = config('shop.nieuwsbrief.korting_procent');

    // $bestsellers komt uit de route: de vier best beoordeelde actieve producten
@endphp

@section('content')

{{-- Hero --}}
<section class="relative mx-auto grid max-w-[1240px] grid-cols-1 items-center gap-12 px-6 pt-12 pb-[5.5rem] lg:grid-cols-[1.05fr_.95fr] lg:pt-[4.5rem]">
    <div class="hero-blob b1 pointer-events-none absolute -top-20 right-[6%] z-0 h-[420px] w-[420px] rounded-[46%_54%_60%_40%/48%_44%_56%_52%] bg-accent opacity-50 blur-[60px]"></div>
    <div class="hero-blob b2 pointer-events-none absolute -bottom-[120px] -left-[60px] z-0 h-[340px] w-[340px] rounded-[46%_54%_60%_40%/48%_44%_56%_52%] bg-gold opacity-35 blur-[60px]"></div>

    <div class="relative z-[2]">
        <span id="heroEyebrow" class="mb-5 inline-block text-[.74rem] font-semibold tracking-[.22em] text-primary-deep uppercase">{{ $tagline }}</span>
        <h1 id="heroTitle" class="font-serif text-[clamp(2.6rem,5.2vw,4.4rem)] leading-[1.06] font-normal tracking-[-.01em]">
            <span class="line block overflow-hidden"><span class="inline-block">Nagels die</span></span>
            <span class="line block overflow-hidden"><span class="inline-block"><em class="font-medium text-primary italic">voor zich</em> spreken</span></span>
        </h1>
        <p id="heroText" class="mt-6 mb-9 max-w-[44ch] text-[1.05rem] leading-[1.7] font-light text-dark-soft">{{ $heroText }}</p>
        <div id="heroCta" class="flex flex-wrap items-center gap-4">
            <a href="#bestsellers" class="btn inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-[translate,box-shadow,background-color] duration-300 ease-spring hover:-translate-y-[3px] hover:bg-primary-deep hover:shadow-[0_20px_36px_-12px_color-mix(in_srgb,var(--color-primary-deep)_75%,transparent)]">
                Shop bestsellers <i class="fa-light fa-arrow-right"></i>
            </a>
            <a href="#categorieen" class="btn inline-flex items-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] transition-[translate,border-color] duration-300 ease-spring hover:-translate-y-[3px] hover:border-dark">
                Ontdek categorieën
            </a>
        </div>
        <div id="heroStats" class="mt-12 flex gap-6 sm:gap-10">
            @foreach ($stats as $stat)
                <div class="stat">
                    @if (isset($stat['count']))
                        <b class="block font-serif text-[1.6rem] font-medium" data-count="{{ $stat['count'] }}">0</b>
                    @else
                        <b class="block font-serif text-[1.6rem] font-medium">{{ $stat['text'] }}@if ($stat['star'] ?? false)<i class="fa-solid fa-star ml-1 text-[.9rem] text-primary"></i>@endif</b>
                    @endif
                    <small class="text-[.76rem] tracking-[.1em] text-dark-soft uppercase">{{ $stat['label'] }}</small>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Kleurenwaaier --}}
    <div class="relative z-[1] order-first grid min-h-[430px] place-items-center lg:order-none lg:min-h-[480px]">
        <div id="fan" class="relative h-[400px] w-[120px]">
            @foreach ($swatches as $i => $swatch)
                <div class="swatch absolute bottom-0 left-1/2 -ml-[37px] h-[340px] w-[74px] origin-[50%_92%] rounded-[60px_60px_18px_18px] border border-white/50 shadow-[0_18px_40px_-18px_color-mix(in_srgb,var(--color-dark)_35%,transparent)] after:absolute after:inset-0 after:rounded-[inherit] after:bg-[linear-gradient(115deg,rgba(255,255,255,.55)_0%,rgba(255,255,255,0)_38%)] after:content-['']"
                     style="background:linear-gradient(180deg,{{ $swatch[0] }},{{ $swatch[1] }});transform:rotate({{ $angles[$i] }}deg)">
                    <span class="absolute bottom-3.5 left-1/2 grid h-[26px] w-[26px] -translate-x-1/2 place-items-center rounded-full bg-white/85 text-[.55rem] font-bold tracking-[.02em] text-dark">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                </div>
            @endforeach
            <div class="absolute bottom-2 left-1/2 z-[9] grid h-[38px] w-[38px] -translate-x-1/2 place-items-center rounded-full bg-dark shadow-[0_8px_20px_-6px_color-mix(in_srgb,var(--color-dark)_50%,transparent)] after:h-2.5 after:w-2.5 after:rounded-full after:bg-gold after:content-['']"></div>
            <span class="fan-caption absolute -bottom-8 md:-bottom-0 -top-0 sm:-top-8 left-1/2 w-max max-w-[calc(100vw-3rem)] -translate-x-1/2 text-center text-[.74rem] leading-[1.7] tracking-[.16em] text-dark-soft uppercase">{{ $fanCaption[0] }}<br class="sm:hidden"> {{ $fanCaption[1] }}</span>
        </div>
    </div>
</section>

{{-- USP-strip --}}
<div class="border-y border-primary/15 bg-offwhite px-6 py-3.5">
    <div class="mx-auto flex max-w-[1240px] flex-wrap items-center justify-center gap-x-11 gap-y-3">
        @foreach ($uspStrip as $usp)
            <span class="inline-flex items-center gap-2.5 text-[.86rem] font-medium tracking-[.02em] text-dark-soft">
                <i class="fa-light {{ $usp['icon'] }} text-base text-primary-deep"></i>{{ $usp['text'] }}
            </span>
        @endforeach
    </div>
</div>

{{-- Categorieën --}}
<section id="categorieen" class="px-6 py-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div class="reveal mb-12 flex flex-wrap items-end justify-between gap-8">
            <h2 class="font-serif text-[clamp(1.9rem,3.4vw,2.8rem)] leading-[1.15] font-normal">Shop per <em class="text-primary italic">categorie</em></h2>
            <a href="{{ url('/producten') }}" class="inline-flex items-center gap-2 text-[.86rem] font-semibold tracking-[.04em] text-primary-deep transition-all hover:gap-3.5">Alle producten <i class="fa-light fa-arrow-right"></i></a>
        </div>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-5">
            @foreach (config('shop.categories') as $category)
                <a href="{{ url('/producten') }}?categorie={{ $category['slug'] }}" class="reveal group relative flex min-h-[170px] flex-col gap-3.5 overflow-hidden rounded-card border border-primary/15 bg-offwhite p-7 pb-6 transition-[translate,box-shadow,border-color] duration-[350ms] ease-spring hover:-translate-y-1.5 hover:border-primary/40 hover:shadow-card">
                    <span class="h-[52px] w-[52px] rounded-[58%_42%_55%_45%/50%_60%_40%_50%] transition-transform duration-500 ease-spring group-hover:rotate-[18deg] group-hover:scale-[1.12]" style="background:linear-gradient(135deg,{{ $category['dab'][0] }},{{ $category['dab'][1] }})"></span>
                    <span class="absolute top-5 right-5 grid h-8 w-8 place-items-center rounded-full border border-dark/20 text-[.85rem] transition-all duration-300 group-hover:-rotate-45 group-hover:border-primary group-hover:bg-primary group-hover:text-white"><i class="fa-light fa-arrow-right"></i></span>
                    <h3 class="mt-auto font-serif text-[1.18rem] font-medium">{{ $category['name'] }}</h3>
                    <small class="text-[.78rem] tracking-[.02em] text-dark-soft">{{ $category['sub'] }}</small>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Bestsellers --}}
<section id="bestsellers" class="bg-linear-to-b from-cream to-cream-deep px-6 py-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div class="reveal mb-12 flex flex-wrap items-end justify-between gap-8">
            <h2 class="font-serif text-[clamp(1.9rem,3.4vw,2.8rem)] leading-[1.15] font-normal">Deze week <em class="text-primary italic">favoriet</em></h2>
            <a href="{{ url('/producten') }}" class="inline-flex items-center gap-2 text-[.86rem] font-semibold tracking-[.04em] text-primary-deep transition-all hover:gap-3.5">Bekijk alle bestsellers <i class="fa-light fa-arrow-right"></i></a>
        </div>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(255px,1fr))] gap-6">
            @foreach ($bestsellers as $product)
                @include('partials.product-card', ['product' => $product, 'reveal' => true])
            @endforeach
        </div>
    </div>
</section>

{{-- Merken --}}
<section class="px-6 py-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div class="reveal mb-12 flex flex-wrap items-end justify-between gap-8">
            <h2 class="font-serif text-[clamp(1.9rem,3.4vw,2.8rem)] leading-[1.15] font-normal">Onze <em class="text-primary italic">merken</em></h2>
        </div>
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($brandPanels as $panel)
                @php
                    $isDark = $panel['style'] === 'dark';
                    $panelBg = match ($panel['style']) {
                        'dark'  => 'bg-dark text-cream',
                        'blush' => 'bg-linear-[140deg] from-accent-soft to-accent text-dark',
                        default => 'bg-linear-[140deg] from-cream-deep to-gold text-dark',
                    };
                @endphp
                {{-- Ruime bovenpadding houdt de grote merknaam vrij van de titel; knoppen lijnen onderaan uit --}}
                <div class="reveal relative flex flex-col overflow-hidden rounded-[calc(var(--radius)+8px)] px-8 pt-[7.5rem] pb-[3.2rem] sm:px-10 lg:pt-[8.5rem] {{ $panelBg }}">
                    <span class="pointer-events-none absolute top-6 right-7 font-serif text-[clamp(4rem,7vw,6rem)] leading-none whitespace-nowrap italic opacity-[.14] {{ $isDark ? 'text-gold' : 'text-white' }}">{{ $panel['name'] }}</span>
                    <h3 class="font-serif text-[2rem] leading-tight font-medium">{{ $panel['name'] }}</h3>
                    <p class="mt-1 mb-4 font-serif text-[1.1rem] italic {{ $isDark ? 'text-gold' : 'text-primary-deep' }}">{{ $panel['tagline'] }}</p>
                    <p class="mb-8 max-w-[44ch] leading-[1.7] font-light opacity-85">{{ $panel['text'] }}</p>
                    <a href="{{ url('/producten') }}?merk={{ urlencode($panel['name']) }}" class="mt-auto inline-flex items-center gap-2.5 self-start rounded-full px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] transition-all duration-300 ease-spring hover:-translate-y-[3px] {{ $isDark ? 'bg-gold text-dark hover:bg-[color-mix(in_srgb,var(--color-gold)_70%,white)]' : 'bg-dark text-cream hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]' }}">
                        Ontdek {{ $panel['name'] }} <i class="fa-light fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Nieuwsbrief --}}
<section id="nieuwsbrief" class="scroll-mt-28 px-6 pt-4 pb-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div x-data="{
                email: '',
                status: @js(session('nieuwsbrief') ? 'klaar' : 'open'),
                eigenAccount: @js(session('nieuwsbrief') === 'account'),
                fout: '',
                async inschrijven() {
                    if (this.status === 'bezig') return;
                    this.status = 'bezig';
                    this.fout = '';
                    try {
                        const res = await fetch(@js(route('nieuwsbrief.inschrijven')), {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': @js(csrf_token()), 'Accept': 'application/json' },
                            body: JSON.stringify({ email: this.email }),
                        });
                        const data = await res.json().catch(() => ({}));
                        if (res.ok) { this.eigenAccount = data.eigen_account; this.status = 'klaar'; return; }
                        this.fout = data.errors?.email?.[0] ?? (res.status === 429 ? 'Te veel pogingen, probeer het over een minuut opnieuw.' : 'Inschrijven lukte niet, probeer het opnieuw.');
                    } catch { this.fout = 'Inschrijven lukte niet, probeer het opnieuw.'; }
                    this.status = 'open';
                },
             }" class="reveal relative overflow-hidden rounded-[calc(var(--radius)+10px)] bg-linear-[120deg] from-primary to-primary-deep px-8 py-16 text-center text-white before:absolute before:-top-[140px] before:-left-20 before:h-[300px] before:w-[300px] before:rounded-full before:bg-white/10 before:content-[''] after:absolute after:-right-10 after:-bottom-[110px] after:h-[220px] after:w-[220px] after:rounded-full after:bg-white/10 after:content-['']">
            <h2 class="relative z-[1] mb-3 font-serif text-[clamp(1.8rem,3.4vw,2.6rem)] font-normal">Als eerste de <em class="italic">nieuwe tinten</em> zien?</h2>
            <p class="relative z-[1] mb-8 font-light opacity-90">Schrijf je in en ontvang {{ $nieuwsbriefKorting }}% korting op je eerste bestelling.</p>
            <form method="POST" action="{{ route('nieuwsbrief.inschrijven') }}" x-show="status !== 'klaar'" @submit.prevent="inschrijven()" class="relative z-[1] mx-auto flex max-w-[460px] flex-wrap justify-center gap-3">
                @csrf
                <input type="email" name="email" x-model="email" required placeholder="jouw@email.nl" aria-label="E-mailadres" class="min-w-[230px] flex-1 rounded-full bg-white/95 px-6 py-4 text-[.92rem] text-dark outline-none">
                <button type="submit" :disabled="status === 'bezig'" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-4 text-[.92rem] font-semibold text-white transition-colors duration-300 hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)] disabled:opacity-70">
                    <span x-text="status === 'bezig' ? 'Even geduld…' : 'Inschrijven'">Inschrijven</span>
                </button>
                <p x-cloak x-show="fout !== ''" x-text="fout" class="w-full text-[.85rem] font-medium"></p>
            </form>
            <div x-cloak x-show="status === 'klaar'" x-transition.opacity.duration.400ms class="relative z-[1]">
                <p class="text-[1.05rem] font-medium"><i class="fa-solid fa-circle-check mr-2"></i>Gelukt! Je persoonlijke kortingscode staat klaar in je account.</p>
                <template x-if="eigenAccount">
                    <a href="{{ route('account') }}" class="mt-6 inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-4 text-[.92rem] font-semibold text-white transition-colors duration-300 hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">
                        Bekijk je kortingscode <i class="fa-light fa-arrow-right"></i>
                    </a>
                </template>
                <template x-if="!eigenAccount">
                    <div>
                        <p class="mt-2 font-light opacity-90">Log in of maak een account aan met dit e-mailadres om je code te bekijken.</p>
                        <div class="mt-6 flex flex-wrap justify-center gap-3">
                            <a :href="@js(route('registreren')) + (email ? '?email=' + encodeURIComponent(email) : '')" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-4 text-[.92rem] font-semibold text-white transition-colors duration-300 hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">
                                Account aanmaken <i class="fa-light fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 rounded-full bg-white/95 px-7 py-4 text-[.92rem] font-semibold text-dark transition-colors duration-300 hover:bg-white">
                                Inloggen
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
{{-- Hero-animaties (GSAP komt uit resources/js/app.js; reveals/tellers zitten daar ook) --}}
<script type="module">
const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const angles = @json($angles);

if (window.gsap && !reduce) {
    const gsap = window.gsap;

    /* hero laad-sequence */
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });
    tl.from("#heroEyebrow", { y: 20, opacity: 0, duration: .6 })
        .from("#heroTitle .line > span", { yPercent: 110, duration: .9, stagger: .12, ease: "power4.out" }, "-=.3")
        .from("#heroText", { y: 24, opacity: 0, duration: .7 }, "-=.5")
        .from("#heroCta .btn", { y: 20, opacity: 0, duration: .5, stagger: .1, clearProps: "all" }, "-=.4")
        .from("#heroStats .stat", { y: 20, opacity: 0, duration: .5, stagger: .1 }, "-=.3");

    /* signature: waaier staat open, komt zacht binnen */
    gsap.set(".swatch", { rotate: i => angles[i] });
    tl.from(".swatch", {
        opacity: 0,
        y: 34,
        duration: .8,
        stagger: { each: .06, from: "center" },
        ease: "power3.out"
    }, .4);
    tl.from(".fan-caption", { opacity: 0, y: 10, duration: .5 }, "-=.4");

    /* waaier ademt zachtjes mee op scroll */
    gsap.to(".swatch", {
        rotate: i => angles[i] * 1.18,
        scrollTrigger: { trigger: "#fan", start: "top center", end: "bottom top", scrub: 1 }
    });

    /* blobs zweven */
    gsap.to(".hero-blob.b1", { y: 40, x: -30, duration: 7, yoyo: true, repeat: -1, ease: "sine.inOut" });
    gsap.to(".hero-blob.b2", { y: -30, x: 20, duration: 9, yoyo: true, repeat: -1, ease: "sine.inOut" });
}
</script>
@endpush
