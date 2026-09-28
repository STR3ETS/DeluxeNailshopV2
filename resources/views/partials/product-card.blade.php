@php
    /*
    | Productkaart. Verwacht $product met: brand, name, reviews, price,
    | old_price, badge, badge_gold, bg ([van, naar]) en image (of 'bottle'
    | voor het getekende SVG-flesje als fallback).
    |
    | Opties:
    |   $reveal     - true voor scroll-reveal-animatie (homepage)
    |   $filterable - true op de productenpagina: voegt data-attributen +
    |                 Alpine-bindings toe zodat de kaart meefiltert/sorteert
    |   $index      - volgorde-index (voor sorteren op "Aanbevolen")
    |
    | Onder sm is de kaart compacter, zodat er op mobiel twee naast elkaar
    | passen (grid met minmax(150px,1fr)).
    */
    $reveal = $reveal ?? false;
    $filterable = $filterable ?? false;

    // Sale-producten zonder eigen badge krijgen automatisch een Sale-badge
    $badge = $product['badge'] ?? null;
    $badgeGold = $product['badge_gold'] ?? false;
    if (! $badge && ! empty($product['old_price'])) {
        $badge = 'Sale';
        $badgeGold = true;
    }

    // Uitverkocht gaat boven alles: eigen badge + gedimde foto
    $uitverkocht = ($product['voorraad'] ?? 1) < 1;
    if ($uitverkocht) {
        $badge = 'Uitverkocht';
        $badgeGold = false;
    }

    // Slug voor de detailpagina; ook gebruikt als winkelwagen-id
    $productSlug = $product['slug'] ?? \Illuminate\Support\Str::slug($product['brand'].' '.$product['name']);
    $productUrl  = route('product.show', $productSlug);

    // Payload voor de winkelwagen-store (Alpine)
    $cartItem = [
        'id'    => $productSlug,
        'brand' => $product['brand'],
        'name'  => $product['name'],
        'price' => $product['price'],
        'image' => ! empty($product['image']) ? asset($product['image']) : null,
    ];
@endphp
<article
    x-data="{ wished: false, added: false, pop: false }"
    @if ($filterable)
    data-cat="{{ $product['category'] ?? '' }}"
    data-sub="{{ $product['subcategory'] ?? '' }}"
    data-brand="{{ $product['brand'] }}"
    data-sale="{{ empty($product['old_price']) ? 0 : 1 }}"
    data-price="{{ (int) round($product['price'] * 100) }}"
    data-reviews="{{ $product['reviews'] }}"
    data-index="{{ $index ?? 0 }}"
    x-show="matches($el.dataset)"
    :style="{ order: orderOf($el.dataset) }"
    @endif
    class="{{ $reveal ? 'reveal ' : '' }}group flex flex-col overflow-hidden rounded-card bg-offwhite shadow-[0_8px_26px_-16px_color-mix(in_srgb,var(--color-dark)_20%,transparent)] transition-[translate,box-shadow] duration-[350ms] ease-spring hover:-translate-y-2 hover:shadow-card">
    <div class="relative grid h-[180px] place-items-center overflow-hidden sm:h-[230px]">
        <div class="absolute inset-0" style="background:linear-gradient(160deg,{{ $product['bg'][0] }},{{ $product['bg'][1] }})"></div>
        @if ($badge)
            <span class="absolute top-2.5 left-2.5 z-[2] rounded-full px-2.5 py-1 text-[.56rem] font-semibold tracking-[.12em] text-cream uppercase sm:top-4 sm:left-4 sm:px-3 sm:py-1.5 sm:text-[.66rem] sm:tracking-[.14em] {{ $badgeGold ? 'bg-primary' : 'bg-dark' }}">{{ $badge }}</span>
        @endif
        <button type="button" @click="wished = !wished" class="absolute top-2 right-2 z-[2] grid h-8 w-8 place-items-center rounded-full bg-white/85 transition-all duration-300 hover:scale-[1.12] hover:bg-white sm:top-3.5 sm:right-3.5 sm:h-9 sm:w-9" aria-label="Bewaar als favoriet">
            <i class="fa-heart text-[.85rem] sm:text-[.95rem]" :class="wished ? 'fa-solid text-primary' : 'fa-light text-dark'"></i>
        </button>
        @if (!empty($product['image']))
            {{-- Foto is strak bijgesneden: max-hoogte houdt ±30-36px witruimte boven en onder --}}
            <img src="{{ asset($product['image']) }}" alt="{{ $product['brand'] }} {{ $product['name'] }}" loading="lazy"
                 class="relative z-[1] max-h-[120px] w-auto max-w-[85%] object-contain sm:max-h-[158px] drop-shadow-[0_14px_18px_color-mix(in_srgb,var(--color-dark)_22%,transparent)] transition-transform duration-500 ease-spring group-hover:-translate-y-1.5 group-hover:-rotate-[7deg] group-hover:scale-105 {{ $uitverkocht ? 'opacity-50 saturate-50' : '' }}">
        @elseif (!empty($product['bottle']))
        @php $bottle = $product['bottle']; $colors = config('theme.colors'); @endphp
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
        {{-- Uitgestrekte link over het beeldvlak (onder de knoppen, boven de foto) --}}
        <a href="{{ $productUrl }}" class="absolute inset-0 z-[1]" aria-label="{{ $product['name'] }}"></a>
    </div>
    <div class="flex flex-1 flex-col gap-1.5 p-3.5 pb-4 sm:gap-2 sm:p-5 sm:pb-6">
        <span class="text-[.6rem] font-bold tracking-[.16em] text-primary-deep uppercase sm:text-[.7rem] sm:tracking-[.2em]">{{ $product['brand'] }}</span>
        <h3 class="font-serif text-[.95rem] leading-[1.3] font-medium sm:text-[1.12rem]"><a href="{{ $productUrl }}" class="transition-colors hover:text-primary-deep">{{ $product['name'] }}</a></h3>
        <div class="flex items-center gap-[3px] text-[.62rem] text-primary sm:text-[.7rem]">
            @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
            <small class="ml-1 text-[.7rem] text-dark-soft sm:ml-1.5 sm:text-[.74rem]">({{ $product['reviews'] }})</small>
        </div>
        <div class="mt-auto flex items-center justify-between gap-2 pt-2 sm:pt-3">
            {{-- Op mobiel staat een doorgestreepte oude prijs boven de prijs --}}
            <span class="font-serif text-[1.05rem] leading-tight font-semibold sm:text-[1.25rem]">
                @if (!empty($product['old_price']))<s class="block text-[.78rem] font-normal text-dark-soft sm:mr-1.5 sm:inline sm:text-[.85rem]">€{{ number_format($product['old_price'], 2, ',', '.') }}</s>@endif€{{ number_format($product['price'], 2, ',', '.') }}
            </span>
            @if ($uitverkocht)
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center gap-2 rounded-full bg-dark/8 text-[.72rem] font-semibold tracking-[.1em] text-dark-soft uppercase sm:h-auto sm:w-auto sm:px-4 sm:py-2.5" title="Uitverkocht">
                    <i class="fa-light fa-clock-rotate-left text-[.8rem]"></i><span class="hidden sm:inline">Uitverkocht</span>
                </span>
            @else
                <button type="button"
                        data-cart-item="{{ json_encode($cartItem) }}"
                        @click="added = true; pop = true; setTimeout(() => pop = false, 180); $store.cart.add(JSON.parse($el.dataset.cartItem)); setTimeout(() => added = false, 1600)"
                        :class="[added ? 'bg-primary' : 'bg-dark hover:bg-primary', pop ? 'scale-90' : '']"
                        class="relative grid h-10 w-10 shrink-0 place-items-center rounded-full bg-dark text-white transition-all duration-300 hover:scale-105 sm:h-11 sm:w-11"
                        aria-label="In winkelwagen">
                    <i x-show="!added" class="fa-light fa-bag-shopping-plus text-[1rem]"></i>
                    <i x-show="added" x-cloak class="fa-solid fa-check text-[1rem]"></i>
                    <template x-if="added"><span class="cart-ring"></span></template>
                </button>
            @endif
        </div>
    </div>
</article>
