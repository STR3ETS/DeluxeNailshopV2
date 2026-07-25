@php
    /*
    | Header + navigatie. De categorieën komen uit config/shop.php en linken
    | naar de productenpagina met de categorie als voorgeselecteerd filter.
    | "Sale" staat vooraan en filtert op aanbiedingen.
    */
    $freeFrom = config('shop.free_shipping_from');
    $announcement = [
        'text' => 'Gratis verzending vanaf €'.$freeFrom,
        'em'   => 'Voor 16:00 besteld, morgen in huis',
    ];

    $headerCategories = config('shop.categories');
    $onProducten      = request()->is('producten');
    $activeCategorie  = $onProducten ? request('categorie') : null;
    $saleActive       = $onProducten && request()->boolean('sale');
@endphp

{{-- Announcement --}}
<div class="bg-dark px-4 py-2.5 text-center text-[.78rem] tracking-[.14em] text-cream uppercase">
    {{ $announcement['text'] }} &nbsp;·&nbsp; <em class="font-serif tracking-[.02em] normal-case italic text-gold">{{ $announcement['em'] }}</em>
</div>

{{-- Navigatie --}}
<header x-data="{ mobileOpen: false }" class="sticky top-0 z-[60] border-b border-primary/20 bg-cream/85 backdrop-blur-[14px]">
    <nav class="mx-auto flex max-w-[1240px] items-center gap-8 px-6 py-3.5">
        <a href="{{ url('/') }}" class="shrink-0">
            <img src="{{ asset('logo/deluxenailshop_transp_primair_v1.png') }}" alt="{{ config('app.name') }}" class="h-11 w-auto">
        </a>

        <div class="mx-auto hidden flex-wrap justify-center gap-[1.4rem] lg:flex">
            <a href="{{ url('/producten') }}?sale=1"
               class="relative py-1 text-[.85rem] font-semibold tracking-[.02em] text-primary transition-colors after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-left after:rounded-full after:bg-primary after:transition-transform after:duration-300 after:content-[''] hover:text-primary-deep hover:after:scale-x-100 {{ $saleActive ? 'after:scale-x-100' : 'after:scale-x-0' }}">Sale</a>
            @foreach ($headerCategories as $cat)
                <a href="{{ url('/producten') }}?categorie={{ $cat['slug'] }}"
                   class="relative py-1 text-[.85rem] font-medium tracking-[.02em] transition-colors after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-left after:rounded-full after:bg-primary after:transition-transform after:duration-300 after:content-[''] hover:text-primary-deep hover:after:scale-x-100 {{ $activeCategorie === $cat['slug'] ? 'text-primary-deep after:scale-x-100' : 'after:scale-x-0' }}">{{ $cat['name'] }}</a>
            @endforeach
            <a href="{{ url('/faq') }}"
               class="relative py-1 text-[.85rem] font-medium tracking-[.02em] transition-colors after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-left after:rounded-full after:bg-primary after:transition-transform after:duration-300 after:content-[''] hover:text-primary-deep hover:after:scale-x-100 {{ request()->is('faq') ? 'text-primary-deep after:scale-x-100' : 'after:scale-x-0' }}">FAQ</a>
        </div>

        <div class="ml-auto flex items-center gap-2.5 lg:ml-0">
            <button type="button" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Zoeken">
                <i class="fa-light fa-magnifying-glass text-[1.1rem]"></i>
            </button>
            <a href="{{ auth()->check() ? auth()->user()->portalUrl() : url('/login') }}"
               class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep {{ request()->is('login') || request()->is('account') ? 'bg-cream-deep text-primary-deep' : '' }}"
               aria-label="{{ auth()->check() ? 'Mijn account' : 'Inloggen' }}">
                <i class="{{ auth()->check() ? 'fa-solid' : 'fa-light' }} fa-user text-[1.1rem]"></i>
            </a>
            {{-- Winkelwagen + dropdown --}}
            <div class="relative" @click.outside="$store.cart.open = false">
                <button type="button" id="cartButton" @click="$store.cart.open = !$store.cart.open"
                        class="relative grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep"
                        aria-label="Winkelwagen" :aria-expanded="$store.cart.open">
                    <i class="fa-light fa-bag-shopping text-[1.1rem]"></i>
                    <span class="absolute top-0.5 right-0 grid h-4 w-4 place-items-center rounded-full bg-primary text-[.62rem] font-semibold text-white transition-transform duration-300"
                          :class="$store.cart.bumped ? 'scale-150' : 'scale-100'"
                          x-text="$store.cart.count">0</span>
                </button>

                <div x-cloak x-show="$store.cart.open"
                     x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                     x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0"
                     class="absolute top-[calc(100%+.75rem)] right-0 z-[70] w-[350px] max-w-[calc(100vw-2rem)] origin-top-right rounded-card border border-primary/15 bg-offwhite p-5 shadow-card">

                    <div class="mb-1 flex items-center justify-between">
                        <h3 class="font-serif text-[1.15rem] font-medium">Winkelwagen</h3>
                        <span class="text-[.8rem] text-dark-soft" x-show="$store.cart.count > 0"><span x-text="$store.cart.count"></span> artikelen</span>
                    </div>

                    {{-- Leeg --}}
                    <div x-show="$store.cart.items.length === 0" class="py-8 text-center">
                        <i class="fa-light fa-bag-shopping mb-3 text-[1.8rem] text-primary"></i>
                        <p class="text-[.9rem] font-light text-dark-soft">Je winkelwagen is nog leeg.</p>
                    </div>

                    {{-- Artikelen --}}
                    <div class="max-h-[320px] divide-y divide-primary/10 overflow-y-auto">
                        <template x-for="item in $store.cart.items" :key="item.id">
                            <div class="flex items-center gap-3 py-3.5">
                                <div class="grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-xl bg-cream-deep">
                                    <template x-if="item.image"><img :src="item.image" :alt="item.name" class="max-h-11 w-auto object-contain"></template>
                                    <template x-if="!item.image"><i class="fa-light fa-bottle-droplet text-[1.1rem] text-primary"></i></template>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[.84rem] leading-tight font-medium" x-text="item.name" :title="item.name"></p>
                                    <p class="mt-0.5 text-[.66rem] font-semibold tracking-[.16em] text-primary-deep uppercase" x-text="item.brand"></p>
                                    <div class="mt-1.5 inline-flex items-center gap-2.5 rounded-full border border-dark/15">
                                        <button type="button" @click="$store.cart.dec(item.id)" class="grid h-6 w-6 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Minder"><i class="fa-light fa-minus text-[.65rem]"></i></button>
                                        <span class="min-w-3 text-center text-[.8rem] font-semibold" x-text="item.qty"></span>
                                        <button type="button" @click="$store.cart.inc(item.id)" class="grid h-6 w-6 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Meer"><i class="fa-light fa-plus text-[.65rem]"></i></button>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 self-stretch">
                                    <button type="button" @click="$store.cart.remove(item.id)" class="grid h-6 w-6 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Verwijderen"><i class="fa-light fa-xmark text-[.8rem]"></i></button>
                                    <span class="mt-auto font-serif text-[.95rem] font-semibold" x-text="$store.cart.format(item.price * item.qty)"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Subtotaal + gratis verzending + afrekenen --}}
                    <div x-show="$store.cart.items.length > 0" class="border-t border-primary/15 pt-4">
                        <div class="mb-3 flex items-center justify-between text-[.9rem]">
                            <span class="text-dark-soft">Subtotaal</span>
                            <b class="font-serif text-[1.05rem]" x-text="$store.cart.format($store.cart.total)"></b>
                        </div>
                        <template x-if="$store.cart.total < {{ $freeFrom }}">
                            <div class="mb-4">
                                <p class="mb-1.5 text-[.78rem] text-dark-soft">Nog <b x-text="$store.cart.format({{ $freeFrom }} - $store.cart.total)"></b> tot gratis verzending</p>
                                <div class="h-1.5 overflow-hidden rounded-full bg-cream-deep">
                                    <div class="h-full rounded-full bg-primary transition-all duration-500" :style="{ width: Math.min(100, $store.cart.total / {{ $freeFrom }} * 100) + '%' }"></div>
                                </div>
                            </div>
                        </template>
                        <template x-if="$store.cart.total >= {{ $freeFrom }}">
                            <p class="mb-4 text-[.78rem] font-medium text-primary-deep"><i class="fa-light fa-truck-fast mr-1.5"></i>Je bestelling wordt gratis verzonden!</p>
                        </template>
                        <a href="#" class="flex w-full items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">
                            Afrekenen <i class="fa-light fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" @click="mobileOpen = !mobileOpen" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep lg:hidden" aria-label="Menu">
                <i class="fa-light text-[1.1rem]" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </nav>

    {{-- Mobiel menu --}}
    <div x-cloak x-show="mobileOpen" x-transition.opacity.duration.200ms @click.outside="mobileOpen = false" class="border-t border-primary/15 px-6 py-4 lg:hidden">
        <div class="flex flex-col gap-1">
            <a href="{{ url('/producten') }}?sale=1" @click="mobileOpen = false" class="rounded-lg px-3 py-2 text-[.95rem] font-semibold text-primary transition-colors hover:bg-cream-deep">Sale</a>
            @foreach ($headerCategories as $cat)
                <a href="{{ url('/producten') }}?categorie={{ $cat['slug'] }}" @click="mobileOpen = false" class="rounded-lg px-3 py-2 text-[.95rem] font-medium transition-colors hover:bg-cream-deep">{{ $cat['name'] }}</a>
            @endforeach
            <a href="{{ url('/faq') }}" @click="mobileOpen = false" class="rounded-lg px-3 py-2 text-[.95rem] font-medium transition-colors hover:bg-cream-deep">FAQ</a>
        </div>
    </div>
</header>
