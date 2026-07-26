@extends('layouts.shop')

@section('title', $product['brand'] . ' ' . $product['name'] . ' - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Productdetailpagina
    |--------------------------------------------------------------------------
    | Ontvangt $product, $index en $slug via de route (bron: config/products.php).
    | Producten zonder eigen detailteksten krijgen nette standaardcontent -
    | vul per product 'description', 'kenmerken', 'gebruiksaanwijzing',
    | 'inhoud', 'voorzorg' en 'bewaren' voor eigen teksten.
    */

    $catNames = collect(config('shop.categories'))->pluck('name', 'slug');
    $catName  = $catNames[$product['category']] ?? 'Producten';
    $opVoorraad = ($product['voorraad'] ?? 0) > 0;

    // Sale-badge afleiden, net als op de kaarten
    $badge = $product['badge'] ?? null;
    $badgeGold = $product['badge_gold'] ?? false;
    if (! $badge && ! empty($product['old_price'])) {
        $badge = 'Sale';
        $badgeGold = true;
    }

    // Uitverkocht gaat boven alles: eigen badge + gedimde foto
    if (! $opVoorraad) {
        $badge = 'Uitverkocht';
        $badgeGold = false;
    }

    // Detailcontent met standaardteksten als fallback
    $description = $product['description']
        ?? 'De '.$product['brand'].' '.$product['name'].' is een professioneel product van salonkwaliteit. Ontwikkeld voor nagelstylistes én thuis-artists die het beste resultaat willen: mooi in gebruik, betrouwbaar in resultaat en wekenlang houdbaar.';

    $kenmerken = $product['kenmerken'] ?? [
        ['HEMA- en TPO-vrij', 'zacht voor de nagelplaat, geschikt voor gevoelige huid.'],
        ['Salonkwaliteit', 'professionele formule voor een langhoudend resultaat.'],
        ['Comfortabel in gebruik', 'gladde textuur voor optimale verwerkbaarheid.'],
        ['Bescherming tegen chips en splijten', 'verlengt de levensduur van nagellak of gel.'],
    ];

    $gebruiksaanwijzing = $product['gebruiksaanwijzing'] ?? [
        'Bereid de nagel voor: reinig en vijl de nagelplaat.',
        'Reinig met Valeri / DNKa\' Nail Prep & Cleanser 3in1.',
        'Breng Valeri / DNKa\' Dehydrator en Ultrabond aan voor optimale hechting.',
        'Breng het product in een dunne laag aan (uitharden: 60 sec in LED/Hybrid of 90 sec in UV).',
        'Werk af met een Valeri / DNKa\' Top Coat en hard uit (120 sec in LED/hybridelamp of 180 sec in UV-lamp).',
    ];

    // Inhoud: expliciet veld, anders uit de productnaam afleiden (bijv. "15ml")
    $inhoud = $product['inhoud'] ?? (preg_match('/(\d+\s?ml)/i', $product['name'], $m) ? trim($m[1]) : null);
    $voorzorg = $product['voorzorg'] ?? 'Vermijd contact met huid en ogen. Buiten bereik van kinderen bewaren.';
    $bewaren  = $product['bewaren'] ?? 'Bewaren tussen +15°C en +25°C, uit direct zonlicht.';

    // Productfoto's: hoofdfoto + optionele extra foto
    $fotos = array_values(array_filter([$product['image'] ?? null, $product['image_2'] ?? null]));

    // Payload voor de winkelwagen-store ($related komt uit de route)
    $cartItem = [
        'id'    => $product['slug'],
        'brand' => $product['brand'],
        'name'  => $product['name'],
        'price' => $product['price'],
        'image' => $product['image'] ? asset($product['image']) : null,
    ];
@endphp

@section('content')

<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[1240px]">

        {{-- Breadcrumb --}}
        <nav class="load-reveal mb-8 flex flex-wrap items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <a href="{{ url('/producten') }}" class="transition-colors hover:text-primary-deep">Producten</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <a href="{{ url('/producten') }}?categorie={{ $product['category'] }}" class="transition-colors hover:text-primary-deep">{{ $catName }}</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">{{ $product['name'] }}</span>
        </nav>

        <div class="grid items-start gap-10 lg:grid-cols-[1.05fr_1fr] lg:gap-14">

            {{-- Productbeeld --}}
            <div x-data="{ wished: false, foto: 0 }" class="load-reveal relative grid min-h-[420px] place-items-center overflow-hidden rounded-[calc(var(--radius)+10px)] lg:sticky lg:top-24 lg:min-h-[520px]" style="background:linear-gradient(160deg,{{ $product['bg'][0] }},{{ $product['bg'][1] }})">
                @if ($badge)
                    <span class="absolute top-5 left-5 z-[2] rounded-full px-3.5 py-1.5 text-[.7rem] font-semibold tracking-[.14em] text-cream uppercase {{ $badgeGold ? 'bg-primary' : 'bg-dark' }}">{{ $badge }}</span>
                @endif
                <button type="button" @click="wished = !wished" class="absolute top-4 right-4 z-[2] grid h-11 w-11 place-items-center rounded-full bg-white/85 transition-all duration-300 hover:scale-[1.12] hover:bg-white" aria-label="Bewaar als favoriet">
                    <i class="fa-heart text-[1.05rem]" :class="wished ? 'fa-solid text-primary' : 'fa-light text-dark'"></i>
                </button>
                @foreach ($fotos as $fotoIndex => $foto)
                    <img @if ($fotoIndex > 0) x-cloak @endif x-show="foto === {{ $fotoIndex }}"
                         src="{{ asset($foto) }}" alt="{{ $product['brand'] }} {{ $product['name'] }}{{ $fotoIndex > 0 ? ' - extra foto' : '' }}"
                         class="relative z-[1] max-h-[340px] w-auto object-contain drop-shadow-[0_24px_30px_color-mix(in_srgb,var(--color-dark)_25%,transparent)] lg:max-h-[400px] {{ $opVoorraad ? '' : 'opacity-50 saturate-50' }}">
                @endforeach

                {{-- Miniaturen om te wisselen (alleen bij meerdere foto's) --}}
                @if (count($fotos) > 1)
                    <div class="absolute bottom-5 left-1/2 z-[2] flex -translate-x-1/2 gap-2.5">
                        @foreach ($fotos as $fotoIndex => $foto)
                            <button type="button" @click="foto = {{ $fotoIndex }}"
                                    class="grid h-14 w-14 place-items-center overflow-hidden rounded-xl bg-white/70 p-1.5 transition-all duration-300 hover:bg-white"
                                    :class="foto === {{ $fotoIndex }} ? 'bg-white ring-2 ring-primary' : ''"
                                    aria-label="Bekijk foto {{ $fotoIndex + 1 }}">
                                <img src="{{ asset($foto) }}" alt="" class="max-h-10 w-auto object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Productinfo --}}
            <div x-data="{ qty: 1, added: false, pop: false, open: 'kenmerken' }">
                <div class="load-reveal">
                    <span class="text-[.72rem] font-bold tracking-[.2em] text-primary-deep uppercase">{{ $product['brand'] }}</span>
                    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.2vw,2.6rem)] leading-[1.12] font-normal">{{ $product['name'] }}</h1>

                    <div class="mt-3 flex items-center gap-[3px] text-[.78rem] text-primary">
                        @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
                        <small class="ml-1.5 text-[.8rem] text-dark-soft">({{ $product['reviews'] }} reviews)</small>
                    </div>
                </div>

                <p class="load-reveal mt-5 max-w-[52ch] leading-[1.75] font-light text-dark-soft">{{ $description }}</p>

                {{-- Prijs --}}
                <div class="load-reveal mt-7">
                    <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Prijs</span>
                    <div class="mt-1 flex items-baseline gap-2.5">
                        @if (!empty($product['old_price']))
                            <s class="text-[1.05rem] font-normal text-dark-soft">€{{ number_format($product['old_price'], 2, ',', '.') }}</s>
                        @endif
                        <span class="font-serif text-[2rem] font-semibold">€{{ number_format($product['price'], 2, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Aantal + toevoegen + voorraad --}}
                <div class="load-reveal mt-6 flex flex-wrap items-center gap-4">
                    @if ($opVoorraad)
                        <div class="inline-flex items-center gap-3 rounded-full border border-dark/20">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="grid h-12 w-11 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Minder"><i class="fa-light fa-minus text-[.8rem]"></i></button>
                            <span class="min-w-4 text-center text-[1rem] font-semibold" x-text="qty">1</span>
                            <button type="button" @click="qty++" class="grid h-12 w-11 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Meer"><i class="fa-light fa-plus text-[.8rem]"></i></button>
                        </div>

                        <button type="button"
                                data-cart-item="{{ json_encode($cartItem) }}"
                                @click="added = true; pop = true; setTimeout(() => pop = false, 180); $store.cart.add(JSON.parse($el.dataset.cartItem), qty); qty = 1; setTimeout(() => added = false, 1600)"
                                :class="[added ? 'bg-primary-deep' : 'bg-primary hover:bg-primary-deep', pop ? 'scale-95' : '']"
                                class="relative inline-flex items-center gap-2.5 rounded-full bg-primary px-8 py-4 text-[.95rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-all duration-300">
                            <i x-show="!added" class="fa-light fa-bag-shopping-plus text-[1.05rem]"></i>
                            <i x-show="added" x-cloak class="fa-solid fa-check text-[1.05rem]"></i>
                            In winkelwagen
                            <template x-if="added"><span class="cart-ring"></span></template>
                        </button>

                        <span class="inline-flex items-center gap-2 text-[.88rem] font-medium text-emerald-700">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Op voorraad
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2.5 rounded-full bg-dark/8 px-8 py-4 text-[.95rem] font-semibold tracking-[.02em] text-dark-soft">
                            <i class="fa-light fa-clock-rotate-left text-[1.05rem]"></i>Tijdelijk uitverkocht
                        </span>
                        <span class="max-w-[26ch] text-[.85rem] leading-[1.55] font-light text-dark-soft">We vullen onze voorraad zo snel mogelijk aan.</span>
                    @endif
                </div>

                {{-- Mini-USP's --}}
                <div class="load-reveal mt-7 flex flex-wrap gap-x-7 gap-y-2.5 border-t border-primary/15 pt-6">
                    <span class="inline-flex items-center gap-2 text-[.82rem] text-dark-soft"><i class="fa-light fa-truck-fast text-primary-deep"></i>Voor 16:00 besteld, morgen in huis</span>
                    <span class="inline-flex items-center gap-2 text-[.82rem] text-dark-soft"><i class="fa-light fa-rotate-left text-primary-deep"></i>30 dagen gratis retour</span>
                    <span class="inline-flex items-center gap-2 text-[.82rem] text-dark-soft"><i class="fa-light fa-sparkles text-primary-deep"></i>Salonkwaliteit</span>
                </div>

                {{-- Uitklapsecties --}}
                <div class="load-reveal mt-8">

                    <div class="border-t border-primary/15">
                        <button type="button" @click="open = open === 'kenmerken' ? '' : 'kenmerken'" class="flex w-full items-center justify-between py-5 text-left">
                            <h2 class="font-serif text-[1.15rem] font-medium">Belangrijkste kenmerken</h2>
                            <i class="fa-light fa-chevron-down text-[.85rem] text-dark-soft transition-transform duration-300" :class="open === 'kenmerken' && 'rotate-180'"></i>
                        </button>
                        <ul x-show="open === 'kenmerken'" class="flex flex-col gap-3 pb-6">
                            @foreach ($kenmerken as [$kTitel, $kTekst])
                                <li class="flex gap-3 text-[.92rem]">
                                    <i class="fa-light fa-check mt-1 shrink-0 text-primary"></i>
                                    <p class="leading-[1.6] font-light text-dark-soft"><b class="font-semibold text-dark">{{ $kTitel }}</b> - {{ $kTekst }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="border-t border-primary/15">
                        <button type="button" @click="open = open === 'gebruik' ? '' : 'gebruik'" class="flex w-full items-center justify-between py-5 text-left">
                            <h2 class="font-serif text-[1.15rem] font-medium">Gebruiksaanwijzing</h2>
                            <i class="fa-light fa-chevron-down text-[.85rem] text-dark-soft transition-transform duration-300" :class="open === 'gebruik' && 'rotate-180'"></i>
                        </button>
                        <ol x-cloak x-show="open === 'gebruik'" class="flex flex-col gap-3.5 pb-6">
                            @foreach ($gebruiksaanwijzing as $stapNr => $stap)
                                <li class="flex gap-3.5 text-[.92rem]">
                                    <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-accent-soft text-[.72rem] font-bold text-primary-deep">{{ $stapNr + 1 }}</span>
                                    <p class="leading-[1.6] font-light text-dark-soft">{{ $stap }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <div class="border-t border-b border-primary/15">
                        <button type="button" @click="open = open === 'specs' ? '' : 'specs'" class="flex w-full items-center justify-between py-5 text-left">
                            <h2 class="font-serif text-[1.15rem] font-medium">Specificaties &amp; bewaren</h2>
                            <i class="fa-light fa-chevron-down text-[.85rem] text-dark-soft transition-transform duration-300" :class="open === 'specs' && 'rotate-180'"></i>
                        </button>
                        <dl x-cloak x-show="open === 'specs'" class="flex flex-col gap-4 pb-6 text-[.92rem]">
                            @if ($inhoud)
                                <div>
                                    <dt class="mb-0.5 font-semibold">Inhoud</dt>
                                    <dd class="leading-[1.6] font-light text-dark-soft">{{ $inhoud }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="mb-0.5 font-semibold">Voorzorgsmaatregelen</dt>
                                <dd class="leading-[1.6] font-light text-dark-soft">{{ $voorzorg }}</dd>
                            </div>
                            <div>
                                <dt class="mb-0.5 font-semibold">Bewaarcondities</dt>
                                <dd class="leading-[1.6] font-light text-dark-soft">{{ $bewaren }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gerelateerde producten --}}
        @if (count($related))
            <div class="mt-24">
                <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-6">
                    <h2 class="font-serif text-[clamp(1.7rem,3vw,2.4rem)] leading-[1.15] font-normal">Meer uit <em class="text-primary italic">{{ $catName }}</em></h2>
                    <a href="{{ url('/producten') }}?categorie={{ $product['category'] }}" class="inline-flex items-center gap-2 text-[.86rem] font-semibold tracking-[.04em] text-primary-deep transition-all hover:gap-3.5">Bekijk alles <i class="fa-light fa-arrow-right"></i></a>
                </div>
                <div class="grid grid-cols-[repeat(auto-fill,minmax(255px,1fr))] gap-6">
                    @foreach ($related as $relatedProduct)
                        @include('partials.product-card', ['product' => $relatedProduct, 'reveal' => true])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
