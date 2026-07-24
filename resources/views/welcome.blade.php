@php
    /*
    |--------------------------------------------------------------------------
    | Content van de homepage
    |--------------------------------------------------------------------------
    | Kleuren, fonts en radius komen uit config/theme.php en worden hieronder
    | als :root-variabelen gezet; resources/css/app.css koppelt ze aan
    | Tailwind-tokens (bg-primary, text-dark-soft, rounded-card, enz.).
    | De naam van de shop komt uit config/app.php (APP_NAME in .env).
    */

    $colors = config('theme.colors');
    $brand  = config('app.name', 'Deluxe Nail Shop');
    $tagline = 'Professionele nagelproducten';

    $announcement = [
        'text' => 'Gratis verzending vanaf €50',
        'em'   => 'Voor 16:00 besteld, morgen in huis',
    ];

    $navLinks = ['Base Coat', 'Top Coat', 'Acrylgel', 'Gel', 'Builder in a Bottle', 'Gelpolish', 'Liquids', 'Nail Art', 'Werkmateriaal'];

    $heroText = 'Van Builder in a Bottle tot de perfecte nude gelpolish: alles wat jij als nagelstyliste of thuis-artist nodig hebt. Salonkwaliteit van DNKa & Valeri, morgen al op je werktafel.';

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
    $fanCaption = 'Onze kleurencollectie · 48 tinten';

    // Slanke strip met kernbeloftes, direct onder de hero (Font Awesome-iconen)
    $uspStrip = [
        ['icon' => 'fa-truck-fast',  'text' => 'Voor 16:00 besteld, morgen in huis'],
        ['icon' => 'fa-sparkles',    'text' => 'Salonkwaliteit van DNKa & Valeri'],
        ['icon' => 'fa-rotate-left', 'text' => '30 dagen gratis retour'],
    ];

    $categories = [
        ['name' => 'Base Coat',           'sub' => 'De perfecte basis',        'dab' => ['#f6e3de', '#e9c9c2']],
        ['name' => 'Top Coat',            'sub' => 'Wekenlang glans',          'dab' => ['#f3e7db', '#d8bb98']],
        ['name' => 'Acrylgel',            'sub' => 'Sterk & flexibel',         'dab' => ['#e8d3c2', '#c9a184']],
        ['name' => 'Gel',                 'sub' => 'Sculpting & verlenging',   'dab' => ['#dcb99e', '#b38867']],
        ['name' => 'Builder in a Bottle', 'sub' => 'Onze bestseller',          'dab' => ['#c99b78', '#9a7052']],
        ['name' => 'Gelpolish',           'sub' => '48 tinten nude & kleur',   'dab' => ['#eec4bb', '#d99a90']],
        ['name' => 'Liquids',             'sub' => 'Cleanser, primer & meer',  'dab' => ['#f0e6dc', '#cfc0b0']],
        ['name' => 'Nail Art',            'sub' => 'Foils, glitters & charms', 'dab' => ['#e6cfd8', '#c9a3b4']],
        ['name' => 'Werkmateriaal',       'sub' => 'Vijlen, lampen & tools',   'dab' => ['#d9cfc5', '#a89685']],
    ];

    // Producten: 'image' = transparante productfoto in public/ (strak bijgesneden,
    // dus de kaart zorgt zelf voor witruimte); zonder foto valt de kaart terug
    // op het getekende SVG-flesje via 'bottle'.
    $products = [
        [
            'brand' => 'DNKa',
            'name' => 'Cat\'s Eye Gelpolish — Oranje №0001',
            'reviews' => 482,
            'price' => 9.95,
            'old_price' => 12.95,
            'badge' => 'Bestseller',
            'badge_gold' => true,
            'bg' => ['#fdeadd', '#f9ccae'],
            'image' => 'temp-producten/f4WT5upVonPx7Ay5Oy70YA6RRAXfRUSV5GUfW6OQ-removebg-preview.png',
        ],
        [
            'brand' => 'DNKa',
            'name' => 'Cat\'s Eye Gelpolish — Koraalrood №0002',
            'reviews' => 96,
            'price' => 9.95,
            'old_price' => null,
            'badge' => 'Nieuw',
            'badge_gold' => false,
            'bg' => ['#fde4dc', '#f8bfae'],
            'image' => 'temp-producten/XUsu7Rf4mzqONGKUTXXDfF96fIrjgT1x6FSX79ir-removebg-preview.png',
        ],
        [
            'brand' => 'DNKa',
            'name' => 'Cat\'s Eye Gelpolish — Rood №0003',
            'reviews' => 311,
            'price' => 9.95,
            'old_price' => null,
            'badge' => null,
            'badge_gold' => false,
            'bg' => ['#fce3e6', '#f6bec7'],
            'image' => 'temp-producten/9xn8lsHC0aiUZIb5MYlVgqmIiTSdrIUNflduKe2i-removebg-preview.png',
        ],
        [
            'brand' => 'DNKa',
            'name' => 'Cat\'s Eye Gelpolish — Fuchsia №0005',
            'reviews' => 258,
            'price' => 9.95,
            'old_price' => null,
            'badge' => 'Top rated',
            'badge_gold' => true,
            'bg' => ['#fbe2ef', '#f2bbd9'],
            'image' => 'temp-producten/PxxTPzW8IR2zK7bsjomwEewlgY8IvXxRTIg19CFl-removebg-preview.png',
        ],
    ];

    $brandPanels = [
        [
            'key' => 'dnka',
            'name' => 'DNKa',
            'text' => 'Professionele gelsystemen waar salons op bouwen. Sterke hechting, prachtige viscositeit en tinten die iedere huidtint flatteren.',
            'cta' => 'Ontdek DNKa',
        ],
        [
            'key' => 'valeri',
            'name' => 'Valeri',
            'text' => 'Kleuren met karakter. Van zachte nudes tot statement-tinten: gelpolish die strak dekt in één tot twee lagen en wekenlang blijft glanzen.',
            'cta' => 'Ontdek Valeri',
        ],
    ];

    // Footer: elke kolom bevat één of meer linkgroepen (titel + links)
    $footerColumns = [
        [
            ['title' => 'Shoppen', 'links' => ['Base Coat', 'Top Coat', 'Acrylgel', 'Gel', 'Builder in a Bottle', 'Gelpolish', 'Nail Art', 'Werkmateriaal']],
        ],
        [
            ['title' => 'Merken',   'links' => ['DNKa', 'Valeri', 'Alle merken']],
            ['title' => 'Over ons', 'links' => ['Ons verhaal', 'Reviews', 'Blog']],
        ],
        [
            ['title' => 'Klantenservice', 'links' => ['Contact', 'Verzenden & retour', 'Veelgestelde vragen', 'Betaalmethoden', 'Cadeaubonnen']],
        ],
    ];

    $footerContact = [
        ['icon' => 'fa-light fa-envelope',   'label' => 'E-mail',     'value' => 'info@deluxenailshop.nl'],
        ['icon' => 'fa-brands fa-whatsapp',  'label' => 'WhatsApp',   'value' => '+31 6 12 34 56 78'],
        ['icon' => 'fa-light fa-clock',      'label' => 'Bereikbaar', 'value' => 'ma t/m vr · 09:00–17:00'],
    ];

    $socials = [
        ['icon' => 'fa-instagram',   'label' => 'Instagram'],
        ['icon' => 'fa-tiktok',      'label' => 'TikTok'],
        ['icon' => 'fa-facebook-f',  'label' => 'Facebook'],
        ['icon' => 'fa-pinterest-p', 'label' => 'Pinterest'],
    ];

    // Betaalmethoden: FA-brand-icoon waar beschikbaar, anders een tekstbadge
    $payments = [
        ['type' => 'icon', 'icon' => 'fa-ideal',        'label' => 'iDEAL'],
        ['type' => 'icon', 'icon' => 'fa-paypal',       'label' => 'PayPal'],
        ['type' => 'icon', 'icon' => 'fa-cc-mastercard','label' => 'Mastercard'],
        ['type' => 'icon', 'icon' => 'fa-cc-visa',      'label' => 'Visa'],
        ['type' => 'icon', 'icon' => 'fa-cc-apple-pay', 'label' => 'Apple Pay'],
    ];

    $legalLinks = ['Privacybeleid', 'Algemene voorwaarden', 'Cookies'];

    $footerText = 'Dé webshop voor professionele nagelproducten. Voor nagelstylistes én iedereen die thuis salonresultaat wil.';
    $domain = 'deluxenailshop.nl';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brand }} — {{ $tagline }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="{{ config('theme.fonts.google') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-pro-7.3.1-web/css/all.min.css') }}" rel="stylesheet">

    {{-- Theme-variabelen uit config/theme.php; app.css koppelt ze aan Tailwind-tokens --}}
    <style>
        :root{
        @foreach ($colors as $name => $value)
            --{{ $name }}:{{ $value }};
        @endforeach
            --radius:{{ config('theme.radius') }};
            --serif:{!! config('theme.fonts.serif') !!};
            --sans:{!! config('theme.fonts.sans') !!};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-cream font-sans text-dark antialiased">

{{-- Announcement --}}
<div class="bg-dark px-4 py-2.5 text-center text-[.78rem] tracking-[.14em] text-cream uppercase">
    {{ $announcement['text'] }} &nbsp;·&nbsp; <em class="font-serif tracking-[.02em] normal-case italic text-gold">{{ $announcement['em'] }}</em>
</div>

{{-- Navigatie --}}
<header x-data="{ mobileOpen: false }" class="sticky top-0 z-[60] border-b border-primary/20 bg-cream/85 backdrop-blur-[14px]">
    <nav class="mx-auto flex max-w-[1240px] items-center gap-8 px-6 py-3.5">
        <a href="#" class="shrink-0">
            <img src="{{ asset('logo/deluxenailshop_transp_primair_v1.png') }}" alt="{{ $brand }}" class="h-11 w-auto">
        </a>

        <div class="mx-auto hidden flex-wrap justify-center gap-[1.4rem] lg:flex">
            @foreach ($navLinks as $link)
                <a href="#" class="relative py-1 text-[.85rem] font-medium tracking-[.02em] transition-colors after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-left after:scale-x-0 after:rounded-full after:bg-primary after:transition-transform after:duration-300 after:content-[''] hover:text-primary-deep hover:after:scale-x-100">{{ $link }}</a>
            @endforeach
        </div>

        <div class="ml-auto flex items-center gap-2.5 lg:ml-0">
            <button type="button" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Zoeken">
                <i class="fa-light fa-magnifying-glass text-[1.1rem]"></i>
            </button>
            <button type="button" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Account">
                <i class="fa-light fa-user text-[1.1rem]"></i>
            </button>
            <button type="button" class="relative grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Winkelwagen">
                <i class="fa-light fa-bag-shopping text-[1.1rem]"></i>
                <span class="absolute top-0.5 right-0 grid h-4 w-4 place-items-center rounded-full bg-primary text-[.62rem] font-semibold text-white transition-transform duration-300"
                      :class="$store.cart.bumped ? 'scale-150' : 'scale-100'"
                      x-text="$store.cart.count">0</span>
            </button>
            <button type="button" @click="mobileOpen = !mobileOpen" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep lg:hidden" aria-label="Menu">
                <i class="fa-light text-[1.1rem]" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </nav>

    {{-- Mobiel menu --}}
    <div x-cloak x-show="mobileOpen" x-transition.opacity.duration.200ms @click.outside="mobileOpen = false" class="border-t border-primary/15 px-6 py-4 lg:hidden">
        <div class="flex flex-col gap-1">
            @foreach ($navLinks as $link)
                <a href="#" @click="mobileOpen = false" class="rounded-lg px-3 py-2 text-[.95rem] font-medium transition-colors hover:bg-cream-deep">{{ $link }}</a>
            @endforeach
        </div>
    </div>
</header>

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
            <span class="fan-caption absolute -top-8 left-1/2 -translate-x-1/2 text-[.74rem] tracking-[.2em] whitespace-nowrap text-dark-soft uppercase">{{ $fanCaption }}</span>
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
            <a href="#" class="inline-flex items-center gap-2 text-[.86rem] font-semibold tracking-[.04em] text-primary-deep transition-all hover:gap-3.5">Alle producten <i class="fa-light fa-arrow-right"></i></a>
        </div>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-5">
            @foreach ($categories as $category)
                <a href="#" class="reveal group relative flex min-h-[170px] flex-col gap-3.5 overflow-hidden rounded-card border border-primary/15 bg-offwhite p-7 pb-6 transition-[translate,box-shadow,border-color] duration-[350ms] ease-spring hover:-translate-y-1.5 hover:border-primary/40 hover:shadow-card">
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
            <a href="#" class="inline-flex items-center gap-2 text-[.86rem] font-semibold tracking-[.04em] text-primary-deep transition-all hover:gap-3.5">Bekijk alle bestsellers <i class="fa-light fa-arrow-right"></i></a>
        </div>
        <div class="grid grid-cols-[repeat(auto-fill,minmax(255px,1fr))] gap-6">

            @foreach ($products as $product)
                <article x-data="{ wished: false, added: false }" class="reveal group flex flex-col overflow-hidden rounded-card bg-offwhite shadow-[0_8px_26px_-16px_color-mix(in_srgb,var(--color-dark)_20%,transparent)] transition-[translate,box-shadow] duration-[350ms] ease-spring hover:-translate-y-2 hover:shadow-card">
                    <div class="relative grid h-[230px] place-items-center overflow-hidden">
                        <div class="absolute inset-0" style="background:linear-gradient(160deg,{{ $product['bg'][0] }},{{ $product['bg'][1] }})"></div>
                        @if ($product['badge'])
                            <span class="absolute top-4 left-4 z-[2] rounded-full px-3 py-1.5 text-[.66rem] font-semibold tracking-[.14em] text-cream uppercase {{ $product['badge_gold'] ? 'bg-primary' : 'bg-dark' }}">{{ $product['badge'] }}</span>
                        @endif
                        <button type="button" @click="wished = !wished" class="absolute top-3.5 right-3.5 z-[2] grid h-9 w-9 place-items-center rounded-full bg-white/85 transition-all duration-300 hover:scale-[1.12] hover:bg-white" aria-label="Bewaar als favoriet">
                            <i class="fa-heart text-[.95rem]" :class="wished ? 'fa-solid text-primary' : 'fa-light text-dark'"></i>
                        </button>
                        @if (!empty($product['image']))
                            {{-- Foto is strak bijgesneden: max-hoogte houdt ±36px witruimte boven en onder --}}
                            <img src="{{ asset($product['image']) }}" alt="{{ $product['brand'] }} {{ $product['name'] }}" loading="lazy"
                                 class="relative z-[1] max-h-[158px] w-auto object-contain drop-shadow-[0_14px_18px_color-mix(in_srgb,var(--color-dark)_22%,transparent)] transition-transform duration-500 ease-spring group-hover:-translate-y-1.5 group-hover:-rotate-[7deg] group-hover:scale-105">
                        @elseif (!empty($product['bottle']))
                        @php $bottle = $product['bottle']; @endphp
                        <svg class="relative z-[1] transition-transform duration-500 ease-spring group-hover:-translate-y-1.5 group-hover:-rotate-[7deg] group-hover:scale-105" width="86" height="150" viewBox="0 0 86 150">
                            @if ($bottle['type'] === 'jar')
                                <rect x="33" y="6" width="20" height="34" rx="4" fill="{{ $colors['dark'] }}"/>
                                <rect x="30" y="36" width="26" height="8" rx="3" fill="{{ $colors['dark-soft'] }}"/>
                                <rect x="16" y="44" width="54" height="98" rx="16" fill="{{ $colors['white'] }}" stroke="#e5d5c8"/>
                                <rect x="24" y="58" width="38" height="70" rx="10" fill="{{ $bottle['fill'] }}"/>
                                <text x="43" y="98" text-anchor="middle" font-family="Georgia" font-size="{{ $bottle['label_size'] }}" fill="{{ $bottle['label_color'] }}" font-style="italic">{{ $bottle['label'] }}</text>
                            @else
                                <rect x="35" y="4" width="16" height="30" rx="3" fill="{{ $bottle['cap'] }}"/>
                                <rect x="20" y="34" width="46" height="110" rx="12" fill="{{ $colors['white'] }}" stroke="#e5d5c8"/>
                                <rect x="27" y="46" width="32" height="86" rx="8" fill="{{ $bottle['fill'] }}"/>
                                <text x="43" y="92" text-anchor="middle" font-family="Georgia" font-size="{{ $bottle['label_size'] }}" fill="{{ $bottle['label_color'] }}" font-style="italic">{{ $bottle['label'] }}</text>
                            @endif
                        </svg>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col gap-2 p-5 pb-6">
                        <span class="text-[.7rem] font-bold tracking-[.2em] text-primary-deep uppercase">{{ $product['brand'] }}</span>
                        <h3 class="font-serif text-[1.12rem] leading-[1.3] font-medium">{{ $product['name'] }}</h3>
                        <div class="flex items-center gap-[3px] text-[.7rem] text-primary">
                            @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                            <small class="ml-1.5 text-[.74rem] text-dark-soft">({{ $product['reviews'] }})</small>
                        </div>
                        <div class="mt-auto flex items-center justify-between pt-3">
                            <span class="font-serif text-[1.25rem] font-semibold">
                                @if ($product['old_price'])<s class="mr-1.5 text-[.85rem] font-normal text-dark-soft">€{{ number_format($product['old_price'], 2, ',', '.') }}</s>@endif€{{ number_format($product['price'], 2, ',', '.') }}
                            </span>
                            <button type="button"
                                    @click="added = true; $store.cart.add(); setTimeout(() => added = false, 1600)"
                                    :class="added ? 'bg-primary' : 'bg-dark'"
                                    class="inline-flex items-center rounded-full bg-dark px-4.5 py-2.5 text-[.8rem] font-semibold tracking-[.03em] text-white transition-all duration-300 hover:scale-105 hover:bg-primary">
                                <span x-show="!added">+ Winkelwagen</span>
                                <span x-show="added" x-cloak><i class="fa-solid fa-check mr-1.5"></i>Toegevoegd</span>
                            </button>
                        </div>
                    </div>
                </article>
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
        <div class="grid gap-6 lg:grid-cols-2">
            @foreach ($brandPanels as $panel)
                @php $isDnka = $panel['key'] === 'dnka'; @endphp
                <div class="reveal relative flex min-h-[340px] flex-col justify-end overflow-hidden rounded-[calc(var(--radius)+8px)] px-8 py-[3.2rem] sm:px-11 {{ $isDnka ? 'bg-dark text-cream' : 'bg-linear-[140deg] from-accent-soft to-accent text-dark' }}">
                    <span class="pointer-events-none absolute top-6 right-7 font-serif text-[clamp(4rem,8vw,7rem)] leading-none whitespace-nowrap italic opacity-[.14] {{ $isDnka ? 'text-gold' : 'text-white' }}">{{ $panel['name'] }}</span>
                    <h3 class="mb-3 font-serif text-[2rem] font-medium">{{ $panel['name'] }}</h3>
                    <p class="mb-6 max-w-[40ch] leading-[1.65] font-light opacity-85">{{ $panel['text'] }}</p>
                    <a href="#" class="inline-flex items-center gap-2.5 self-start rounded-full px-7 py-4 text-[.92rem] font-semibold tracking-[.02em] transition-all duration-300 ease-spring hover:-translate-y-[3px] {{ $isDnka ? 'bg-gold text-dark hover:bg-[color-mix(in_srgb,var(--color-gold)_70%,white)]' : 'bg-dark text-cream hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]' }}">
                        {{ $panel['cta'] }} <i class="fa-light fa-arrow-right"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Nieuwsbrief --}}
<section class="px-6 pt-4 pb-[5.5rem]">
    <div class="mx-auto max-w-[1240px]">
        <div x-data="{ sent: false }" class="reveal relative overflow-hidden rounded-[calc(var(--radius)+10px)] bg-linear-[120deg] from-primary to-primary-deep px-8 py-16 text-center text-white before:absolute before:-top-[140px] before:-left-20 before:h-[300px] before:w-[300px] before:rounded-full before:bg-white/10 before:content-[''] after:absolute after:-right-10 after:-bottom-[110px] after:h-[220px] after:w-[220px] after:rounded-full after:bg-white/10 after:content-['']">
            <h2 class="relative z-[1] mb-3 font-serif text-[clamp(1.8rem,3.4vw,2.6rem)] font-normal">Als eerste de <em class="italic">nieuwe tinten</em> zien?</h2>
            <p class="relative z-[1] mb-8 font-light opacity-90">Schrijf je in en ontvang 10% korting op je eerste bestelling.</p>
            <form x-show="!sent" @submit.prevent="sent = true" class="relative z-[1] mx-auto flex max-w-[460px] flex-wrap justify-center gap-3">
                <input type="email" required placeholder="jouw@email.nl" aria-label="E-mailadres" class="min-w-[230px] flex-1 rounded-full bg-white/95 px-6 py-4 text-[.92rem] text-dark outline-none">
                <button type="submit" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-4 text-[.92rem] font-semibold text-white transition-colors duration-300 hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">Inschrijven</button>
            </form>
            <p x-cloak x-show="sent" x-transition.opacity.duration.400ms class="relative z-[1] text-[1.05rem] font-medium"><i class="fa-solid fa-circle-check mr-2"></i>Gelukt — je hoort als eerste over nieuwe tinten!</p>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="mt-[5.5rem] bg-dark px-6 pt-16 pb-8 text-cream">
    <div class="mx-auto grid max-w-[1240px] grid-cols-1 gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr_1.35fr]">

        {{-- Merk, reviews & socials --}}
        <div>
            <a href="#" class="mb-5 inline-block">
                <img src="{{ asset('logo/deluxenailshop_transp_goud_v1.png') }}" alt="{{ $brand }}" class="h-14 w-auto">
            </a>
            <p class="max-w-[32ch] text-[.88rem] leading-[1.7] font-light opacity-75">{{ $footerText }}</p>
            <div class="mt-5 flex items-center gap-[3px] text-[.7rem] text-gold">
                @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                <span class="ml-2 text-[.8rem] font-medium text-cream/80">4,9/5 — 2.400+ reviews</span>
            </div>
            <div class="mt-6 flex gap-2.5">
                @foreach ($socials as $social)
                    <a href="#" aria-label="{{ $social['label'] }}" class="grid h-10 w-10 place-items-center rounded-full border border-cream/20 transition-colors hover:border-gold hover:bg-gold hover:text-dark">
                        <i class="fa-brands {{ $social['icon'] }} text-[1rem]"></i>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Linkkolommen --}}
        @foreach ($footerColumns as $column)
            <div class="flex flex-col gap-10">
                @foreach ($column as $group)
                    <div>
                        <h4 class="mb-4 text-[.78rem] tracking-[.18em] text-gold uppercase">{{ $group['title'] }}</h4>
                        <ul class="flex flex-col gap-2.5">
                            @foreach ($group['links'] as $link)
                                <li><a href="#" class="text-[.9rem] font-light opacity-85 transition-colors hover:text-gold hover:opacity-100">{{ $link }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- Hulp nodig? --}}
        <div class="self-start rounded-card border border-cream/10 bg-white/5 p-6 sm:col-span-2 lg:col-span-1">
            <h4 class="mb-2 font-serif text-[1.35rem] font-medium">Hulp <em class="text-gold italic">nodig</em>?</h4>
            <p class="mb-5 text-[.85rem] leading-[1.6] font-light opacity-75">Ons team van nagelstylistes denkt graag met je mee.</p>
            <ul class="flex flex-col gap-3.5">
                @foreach ($footerContact as $contact)
                    <li class="flex items-center gap-3">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gold/15 text-gold"><i class="{{ $contact['icon'] }} text-[.9rem]"></i></span>
                        <span class="flex flex-col">
                            <small class="text-[.68rem] tracking-[.12em] uppercase opacity-60">{{ $contact['label'] }}</small>
                            <span class="text-[.88rem] font-medium">{{ $contact['value'] }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Onderbalk --}}
    <div class="mx-auto mt-14 flex max-w-[1240px] flex-wrap items-center justify-between gap-x-8 gap-y-4 border-t border-cream/10 pt-6">
        <span class="text-[.78rem] opacity-60">© {{ date('Y') }} {{ $domain }}&nbsp;&nbsp;·&nbsp;&nbsp;Gemaakt door <a href="https://halfmanmedia.nl" target="_blank" rel="noopener" class="font-medium transition-colors hover:text-gold">HalfmanMedia</a></span>
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2">
            @foreach ($legalLinks as $link)
                <a href="#" class="text-[.78rem] opacity-60 transition-all hover:text-gold hover:opacity-100">{{ $link }}</a>
            @endforeach
        </div>
        <div class="flex items-center gap-3">
            @foreach ($payments as $payment)
                @if ($payment['type'] === 'icon')
                    <i class="fa-brands {{ $payment['icon'] }} text-[1.45rem] opacity-70" title="{{ $payment['label'] }}" aria-label="{{ $payment['label'] }}"></i>
                @else
                    <span class="rounded-[6px] border border-cream/25 px-1.5 py-0.5 text-[.62rem] font-semibold tracking-[.08em] uppercase opacity-70" title="{{ $payment['label'] }}">{{ $payment['label'] }}</span>
                @endif
            @endforeach
        </div>
    </div>
</footer>

{{-- Pagina-animaties (GSAP komt uit resources/js/app.js) --}}
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

    /* tellers */
    document.querySelectorAll("[data-count]").forEach(el => {
        const end = +el.dataset.count;
        gsap.fromTo(el, { innerText: 0 }, {
            innerText: end, duration: 1.8, delay: 1, snap: { innerText: 1 }, ease: "power2.out",
            onUpdate() { el.innerText = Math.round(el.innerText).toLocaleString("nl-NL") + "+"; }
        });
    });

    /* scroll reveals — na afloop inline stijlen opruimen zodat hover-effecten werken */
    gsap.utils.toArray(".reveal").forEach(el => {
        gsap.to(el, {
            opacity: 1, y: 0, duration: .8, ease: "power3.out",
            scrollTrigger: { trigger: el, start: "top 86%" },
            onComplete() { el.classList.remove("reveal"); gsap.set(el, { clearProps: "all" }); }
        });
    });
} else {
    document.querySelectorAll(".reveal").forEach(el => el.classList.remove("reveal"));
    document.querySelectorAll("[data-count]").forEach(el => { el.textContent = (+el.dataset.count).toLocaleString("nl-NL") + "+"; });
}
</script>
</body>
</html>
