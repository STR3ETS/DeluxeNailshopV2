@extends('layouts.shop')

@section('title', 'Producten - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Productenpagina
    |--------------------------------------------------------------------------
    | Eén pagina met alle producten. Filters werken client-side (Alpine):
    | via de header komt een voorgeselecteerd filter binnen als query-param
    | (?categorie=slug of ?sale=1) en op de pagina kun je zelf verder
    | filteren op categorie, subcategorie, merk en sale + sorteren.
    | Demo-data hieronder; later te vervangen door producten uit de database.
    */

    // $products komt uit de route (database, alleen actieve producten)

    // Metadata voor de client-side teller/filtering + tellingen voor de sidebar
    $productMeta = collect($products)->values()->map(fn ($p, $i) => [
        'cat'     => $p['category'],
        'sub'     => $p['subcategory'],
        'brand'   => $p['brand'],
        'sale'    => empty($p['old_price']) ? 0 : 1,
        'price'   => (int) round($p['price'] * 100),
        'reviews' => $p['reviews'],
        'index'   => $i,
    ]);
    $totalCount  = count($products);
    $catCounts   = collect($products)->countBy('category');
    $brandCounts = collect($products)->countBy('brand');
    $catNames    = collect(config('shop.categories'))->pluck('name', 'slug');

    $initialCat  = (string) request('categorie', '');
    $initialSale = request()->boolean('sale');
    $initialTitle = $initialSale && ! $initialCat ? 'Sale' : ($catNames[$initialCat] ?? 'Alle producten');
@endphp

@section('content')

<section class="px-6 pt-10 pb-16" x-data="{
    cat: @js($initialCat),
    subs: [],
    brands: [],
    saleOnly: @js($initialSale),
    sort: 'aanbevolen',
    mobileFilters: false,
    products: @js($productMeta),
    catNames: @js($catNames),
    matches(p) {
        return (this.cat === '' || p.cat === this.cat)
            && (this.subs.length === 0 || this.subs.includes(p.sub))
            && (this.brands.length === 0 || this.brands.includes(p.brand))
            && (!this.saleOnly || p.sale == 1);
    },
    orderOf(p) {
        if (this.sort === 'prijs-asc') return parseInt(p.price);
        if (this.sort === 'prijs-desc') return -parseInt(p.price);
        if (this.sort === 'beoordeeld') return -parseInt(p.reviews);
        return parseInt(p.index);
    },
    get visible() { return this.products.filter(p => this.matches(p)); },
    get activeCount() { return (this.cat ? 1 : 0) + this.subs.length + this.brands.length + (this.saleOnly ? 1 : 0); },
    get title() {
        if (this.saleOnly && !this.cat) return 'Sale';
        return this.cat ? this.catNames[this.cat] : 'Alle producten';
    },
    selectCat(slug) { this.cat = this.cat === slug ? '' : slug; this.subs = []; this.sync(); },
    reset() { this.cat = ''; this.subs = []; this.brands = []; this.saleOnly = false; this.sync(); },
    sync() {
        const q = new URLSearchParams();
        if (this.cat) q.set('categorie', this.cat);
        if (this.saleOnly) q.set('sale', '1');
        history.replaceState(null, '', q.toString() ? location.pathname + '?' + q.toString() : location.pathname);
    },
}">
    <div class="mx-auto max-w-[1240px]">

        {{-- Breadcrumb + paginakop --}}
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark" x-text="title">{{ $initialTitle }}</span>
        </nav>

        <div class="load-reveal mb-10 flex flex-wrap items-end justify-between gap-6">
            <div>
                <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal" x-text="title">{{ $initialTitle }}</h1>
                <p class="mt-2 font-light text-dark-soft"><span x-text="visible.length">{{ $totalCount }}</span> producten</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" @click="mobileFilters = true" class="inline-flex items-center gap-2 rounded-full border border-dark/20 px-5 py-2.5 text-[.88rem] font-semibold transition-colors hover:border-dark lg:hidden">
                    <i class="fa-light fa-sliders"></i> Filters
                    <span x-cloak x-show="activeCount > 0" class="grid h-5 w-5 place-items-center rounded-full bg-primary text-[.7rem] font-semibold text-white" x-text="activeCount"></span>
                </button>
                <label class="inline-flex items-center gap-2.5">
                    <span class="hidden text-[.85rem] text-dark-soft sm:inline">Sorteren:</span>
                    <select x-model="sort" class="cursor-pointer rounded-full border border-dark/20 bg-offwhite px-4 py-2.5 text-[.88rem] font-medium outline-none transition-colors hover:border-dark">
                        <option value="aanbevolen">Aanbevolen</option>
                        <option value="prijs-asc">Prijs oplopend</option>
                        <option value="prijs-desc">Prijs aflopend</option>
                        <option value="beoordeeld">Best beoordeeld</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="grid items-start gap-10 lg:grid-cols-[260px_1fr]">

            {{-- Filters (desktop) --}}
            <aside class="load-reveal sticky top-24 hidden lg:block">
                @include('partials.product-filters')
            </aside>

            {{-- Productgrid --}}
            <div>
                <div class="load-reveal grid grid-cols-[repeat(auto-fill,minmax(255px,1fr))] gap-6">
                    @foreach ($products as $i => $product)
                        @include('partials.product-card', ['product' => $product, 'filterable' => true, 'index' => $i])
                    @endforeach
                </div>

                {{-- Lege staat --}}
                <div x-cloak x-show="visible.length === 0" class="rounded-card border border-primary/15 bg-offwhite p-12 text-center">
                    <i class="fa-light fa-magnifying-glass mb-4 text-[2rem] text-primary"></i>
                    <h3 class="mb-2 font-serif text-[1.4rem] font-medium">Geen producten gevonden</h3>
                    <p class="mb-6 font-light text-dark-soft">Probeer een andere combinatie van filters.</p>
                    <button type="button" @click="reset()" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">Wis alle filters</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters (mobiel schuifpaneel) --}}
    <div x-cloak x-show="mobileFilters" class="fixed inset-0 z-[80] lg:hidden">
        <div x-show="mobileFilters" x-transition.opacity.duration.200ms class="absolute inset-0 bg-dark/40" @click="mobileFilters = false"></div>
        <aside x-show="mobileFilters"
               x-transition:enter="transition-transform duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
               class="absolute top-0 right-0 h-full w-[320px] max-w-[85vw] overflow-y-auto bg-cream p-6">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="font-serif text-[1.3rem] font-medium">Filters</h2>
                <button type="button" @click="mobileFilters = false" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Sluiten">
                    <i class="fa-light fa-xmark text-[1.1rem]"></i>
                </button>
            </div>
            @include('partials.product-filters')
        </aside>
    </div>
</section>

@endsection
