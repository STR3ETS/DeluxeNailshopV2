{{--
    Filterpaneel voor de productenpagina. Wordt zowel in de desktop-sidebar
    als in het mobiele schuifpaneel ge-include't en deelt de Alpine-state
    van de omliggende productFilter-component ($catCounts, $brandCounts en
    $totalCount komen uit producten.blade.php).
--}}
<div class="flex flex-col gap-8">

    {{-- Sale --}}
    <label class="flex cursor-pointer items-center gap-3 rounded-card border border-primary/15 bg-offwhite p-4 transition-colors hover:border-primary/40">
        <input type="checkbox" x-model="saleOnly" @change="sync()" class="h-4 w-4 accent-primary">
        <span class="text-[.9rem] font-semibold text-primary">Sale <span class="font-normal text-dark-soft">- alleen aanbiedingen</span></span>
    </label>

    {{-- Categorieën --}}
    <div>
        <h3 class="mb-3 text-[.78rem] font-semibold tracking-[.18em] text-dark-soft uppercase">Categorie</h3>
        <ul class="flex flex-col gap-1">
            <li>
                <button type="button" @click="cat = ''; subs = []; sync()"
                        class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-[.9rem] transition-colors hover:bg-cream-deep"
                        :class="cat === '' ? 'font-semibold text-primary-deep' : ''">
                    Alle producten <span class="text-[.75rem] text-dark-soft">{{ $totalCount }}</span>
                </button>
            </li>
            @foreach (config('shop.categories') as $filterCat)
                <li>
                    <button type="button" @click="selectCat('{{ $filterCat['slug'] }}')"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-[.9rem] transition-colors hover:bg-cream-deep"
                            :class="cat === '{{ $filterCat['slug'] }}' ? 'font-semibold text-primary-deep' : ''">
                        {{ $filterCat['name'] }} <span class="text-[.75rem] text-dark-soft">{{ $catCounts[$filterCat['slug']] ?? 0 }}</span>
                    </button>
                    @if (count($filterCat['subcategories']))
                        <ul x-cloak x-show="cat === '{{ $filterCat['slug'] }}'" class="mt-1 mb-2 ml-3 flex flex-col gap-0.5 border-l border-primary/20 pl-3">
                            @foreach ($filterCat['subcategories'] as $sub)
                                <li>
                                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-2 py-1.5 text-[.85rem] transition-colors hover:bg-cream-deep">
                                        <input type="checkbox" value="{{ $sub['slug'] }}" x-model="subs" class="h-3.5 w-3.5 accent-primary">
                                        {{ $sub['name'] }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Merk --}}
    <div>
        <h3 class="mb-3 text-[.78rem] font-semibold tracking-[.18em] text-dark-soft uppercase">Merk</h3>
        <ul class="flex flex-col gap-1">
            @foreach (config('shop.brands') as $filterBrand)
                <li>
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg px-3 py-2 text-[.9rem] transition-colors hover:bg-cream-deep">
                        <input type="checkbox" value="{{ $filterBrand }}" x-model="brands" class="h-4 w-4 accent-primary">
                        {{ $filterBrand }} <span class="ml-auto text-[.75rem] text-dark-soft">{{ $brandCounts[$filterBrand] ?? 0 }}</span>
                    </label>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Reset --}}
    <button type="button" x-cloak x-show="activeCount > 0" @click="reset()"
            class="inline-flex items-center justify-center gap-2 rounded-full border border-dark/20 px-5 py-2.5 text-[.85rem] font-semibold transition-colors hover:border-dark">
        <i class="fa-light fa-xmark"></i> Wis alle filters
    </button>
</div>
