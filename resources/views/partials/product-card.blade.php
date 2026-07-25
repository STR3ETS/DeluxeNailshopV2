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
    <div class="relative grid h-[230px] place-items-center overflow-hidden">
        <div class="absolute inset-0" style="background:linear-gradient(160deg,{{ $product['bg'][0] }},{{ $product['bg'][1] }})"></div>
        @if ($badge)
            <span class="absolute top-4 left-4 z-[2] rounded-full px-3 py-1.5 text-[.66rem] font-semibold tracking-[.14em] text-cream uppercase {{ $badgeGold ? 'bg-primary' : 'bg-dark' }}">{{ $badge }}</span>
        @endif
        <button type="button" @click="wished = !wished" class="absolute top-3.5 right-3.5 z-[2] grid h-9 w-9 place-items-center rounded-full bg-white/85 transition-all duration-300 hover:scale-[1.12] hover:bg-white" aria-label="Bewaar als favoriet">
            <i class="fa-heart text-[.95rem]" :class="wished ? 'fa-solid text-primary' : 'fa-light text-dark'"></i>
        </button>
        @if (!empty($product['image']))
            {{-- Foto is strak bijgesneden: max-hoogte houdt ±36px witruimte boven en onder --}}
            <img src="{{ asset($product['image']) }}" alt="{{ $product['brand'] }} {{ $product['name'] }}" loading="lazy"
                 class="relative z-[1] max-h-[158px] w-auto object-contain drop-shadow-[0_14px_18px_color-mix(in_srgb,var(--color-dark)_22%,transparent)] transition-transform duration-500 ease-spring group-hover:-translate-y-1.5 group-hover:-rotate-[7deg] group-hover:scale-105">
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
    <div class="flex flex-1 flex-col gap-2 p-5 pb-6">
        <span class="text-[.7rem] font-bold tracking-[.2em] text-primary-deep uppercase">{{ $product['brand'] }}</span>
        <h3 class="font-serif text-[1.12rem] leading-[1.3] font-medium"><a href="{{ $productUrl }}" class="transition-colors hover:text-primary-deep">{{ $product['name'] }}</a></h3>
        <div class="flex items-center gap-[3px] text-[.7rem] text-primary">
            @for ($s = 0; $s < 5; $s++)<i class="fa-solid fa-star"></i>@endfor
            <small class="ml-1.5 text-[.74rem] text-dark-soft">({{ $product['reviews'] }})</small>
        </div>
        <div class="mt-auto flex items-center justify-between pt-3">
            <span class="font-serif text-[1.25rem] font-semibold">
                @if (!empty($product['old_price']))<s class="mr-1.5 text-[.85rem] font-normal text-dark-soft">€{{ number_format($product['old_price'], 2, ',', '.') }}</s>@endif€{{ number_format($product['price'], 2, ',', '.') }}
            </span>
            <button type="button"
                    data-cart-item="{{ json_encode($cartItem) }}"
                    @click="added = true; pop = true; setTimeout(() => pop = false, 180); $store.cart.add(JSON.parse($el.dataset.cartItem)); setTimeout(() => added = false, 1600)"
                    :class="[added ? 'bg-primary' : 'bg-dark hover:bg-primary', pop ? 'scale-90' : '']"
                    class="relative grid h-11 w-11 place-items-center rounded-full bg-dark text-white transition-all duration-300 hover:scale-105"
                    aria-label="In winkelwagen">
                <i x-show="!added" class="fa-light fa-bag-shopping-plus text-[1rem]"></i>
                <i x-show="added" x-cloak class="fa-solid fa-check text-[1rem]"></i>
                <template x-if="added"><span class="cart-ring"></span></template>
            </button>
        </div>
    </div>
</article>
