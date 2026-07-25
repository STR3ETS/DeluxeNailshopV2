@extends('layouts.shop')

@section('title', 'Veelgestelde vragen - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | FAQ-pagina
    |--------------------------------------------------------------------------
    | Vragen en antwoorden zijn overgenomen van de bestaande website
    | (deluxenailshop.nl/faq). 'list' => regels die als lijstje onder het
    | antwoord komen (bijv. openingstijden).
    */

    $faqGroups = [
        [
            'title' => 'Bestellen & Betalen',
            'slug'  => 'bestellen',
            'icon'  => 'fa-credit-card',
            'items' => [
                ['q' => 'Hoe plaats ik een bestelling?', 'a' => 'Kies je favoriete producten, voeg ze toe aan je winkelmandje en reken veilig af via iDEAL, creditcard, PayPal, Bancontact of KBC/CBC.'],
                ['q' => 'Kan ik mijn bestelling nog wijzigen of annuleren?', 'a' => 'Dat kan zolang je bestelling nog niet is verzonden. Stuur ons binnen 2 uur na je bestelling een berichtje via de e-mail, dan passen we het direct aan.'],
                ['q' => 'Welke betaalmethodes accepteren jullie?', 'a' => 'We accepteren iDEAL, creditcard, PayPal, Bancontact of KBC/CBC. Zo kies je wat voor jou het makkelijkst is.'],
            ],
        ],
        [
            'title' => 'Verzending & Levertijd',
            'slug'  => 'verzending',
            'icon'  => 'fa-truck-fast',
            'items' => [
                ['q' => 'Hoe snel wordt mijn bestelling geleverd?', 'a' => 'Bestellingen die vóór 15:00 uur zijn geplaatst, worden dezelfde dag verzonden. Meestal heb je ze binnen 1–2 werkdagen in huis (NL & BE).'],
                ['q' => 'Wat zijn de verzendkosten?', 'a' => 'Binnen Nederland: €7,45 (gratis vanaf €75). België: €12,35 (gratis vanaf €100). We versturen met PostNL.'],
            ],
        ],
        [
            'title' => 'Openingstijden',
            'slug'  => 'openingstijden',
            'icon'  => 'fa-clock',
            'items' => [
                ['q' => 'Wat zijn de openingstijden?', 'a' => 'Je bent welkom op de volgende tijden:', 'list' => [
                    'Ma–Do: 09:00–14:00 (woensdag tot 17:00)',
                    'Vrijdag: 09:00–17:00',
                    'Zaterdag & zondag: gesloten',
                ]],
            ],
        ],
        [
            'title' => 'Retourneren & Ruilen',
            'slug'  => 'retourneren',
            'icon'  => 'fa-rotate-left',
            'items' => [
                ['q' => 'Kan ik producten retourneren?', 'a' => 'Ja, binnen 14 dagen na ontvangst. Producten moeten ongebruikt en ongeopend zijn. Meld je retour aan door ons een e-mail te sturen.'],
                ['q' => 'Wanneer krijg ik mijn geld terug?', 'a' => 'Binnen 5 werkdagen na ontvangst van je retourzending. Let op: de verzendkosten voor het retourneren zijn voor eigen rekening.'],
            ],
        ],
        [
            'title' => 'Over Deluxe Nail Shop',
            'slug'  => 'over-ons',
            'icon'  => 'fa-store',
            'items' => [
                ['q' => 'Waar staat Deluxe Nail Shop voor?', 'a' => 'Wij geloven in kwaliteit, luxe en liefde voor detail. Elk product is zorgvuldig geselecteerd en getest, zodat jij het beste resultaat krijgt, zowel thuis als in je salon.'],
                ['q' => 'Hebben jullie ook een fysieke winkel?', 'a' => 'Ja! We zijn gevestigd aan Thorbeckestraat 3, 6904 BS Zevenaar. We staan voor je klaar!'],
            ],
        ],
        [
            'title' => 'Contact & Service',
            'slug'  => 'contact',
            'icon'  => 'fa-comments',
            'items' => [
                ['q' => 'Hoe kan ik contact opnemen?', 'a' => 'Via e-mail, WhatsApp of Instagram DM. We reageren meestal binnen enkele uren (ma–vrij 09:00–18:00).'],
                ['q' => 'Kan ik advies krijgen over de producten?', 'a' => 'Zeker! We helpen je graag persoonlijk bij het kiezen van de juiste producten of kleuren. Stuur ons gerust een bericht via e-mail, WhatsApp of Instagram DM, we denken met je mee!'],
            ],
        ],
    ];

    // Doorzoekbare metadata voor de client-side zoekfunctie
    $faqMeta = collect($faqGroups)->flatMap(fn ($g) => collect($g['items'])->map(fn ($item) => [
        'group' => $g['slug'],
        'text'  => mb_strtolower($item['q'].' '.$item['a'].' '.implode(' ', $item['list'] ?? [])),
    ]))->values();
    $totalQuestions = $faqMeta->count();
@endphp

@section('content')

<section class="px-6 pt-10 pb-16" x-data="{
    open: 'bestellen-0',
    query: '',
    faqs: @js($faqMeta),
    match(text) {
        const q = this.query.trim().toLowerCase();
        return q === '' || text.includes(q);
    },
    groupVisible(slug) {
        return this.faqs.some(f => f.group === slug && this.match(f.text));
    },
    get hits() {
        return this.faqs.filter(f => this.match(f.text)).length;
    },
}">
    <div class="mx-auto max-w-[860px]">

        {{-- Breadcrumb --}}
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">Veelgestelde vragen</span>
        </nav>

        {{-- Paginakop + zoeken --}}
        <div class="load-reveal mb-12">
            <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">Veelgestelde <em class="text-primary italic">vragen</em></h1>
            <p class="mt-3 max-w-[52ch] font-light text-dark-soft">Het antwoord op de meestgestelde vragen over bestellen, verzenden en retourneren. Staat je vraag er niet tussen? Neem gerust contact op.</p>

            <div class="relative mt-7 max-w-[420px]">
                <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.95rem] text-dark-soft"></i>
                <input type="search" x-model="query" placeholder="Zoek in {{ $totalQuestions }} vragen…" aria-label="Zoek in veelgestelde vragen"
                       class="w-full rounded-full border border-primary/20 bg-offwhite py-3.5 pr-6 pl-12 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/60 focus:border-primary">
            </div>
            <p x-cloak x-show="query.trim() !== ''" class="mt-3 text-[.85rem] text-dark-soft"><span x-text="hits"></span> resultaten</p>
        </div>

        {{-- Vraaggroepen --}}
        <div class="flex flex-col gap-10">
            @foreach ($faqGroups as $group)
                <div x-show="groupVisible('{{ $group['slug'] }}')" class="load-reveal">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light {{ $group['icon'] }} text-[1rem]"></i></span>
                        <h2 class="font-serif text-[1.45rem] font-medium">{{ $group['title'] }}</h2>
                    </div>
                    <div class="overflow-hidden rounded-card border border-primary/15 bg-offwhite">
                        @foreach ($group['items'] as $i => $item)
                            @php $itemId = $group['slug'].'-'.$i; @endphp
                            <div x-show="match(@js(mb_strtolower($item['q'].' '.$item['a'].' '.implode(' ', $item['list'] ?? []))))" class="border-b border-primary/10 last:border-b-0">
                                <button type="button" @click="open = open === '{{ $itemId }}' ? '' : '{{ $itemId }}'"
                                        class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left transition-colors hover:bg-cream-deep/50"
                                        :aria-expanded="open === '{{ $itemId }}'">
                                    <span class="text-[.98rem] font-medium">{{ $item['q'] }}</span>
                                    <i class="fa-light fa-chevron-down shrink-0 text-[.8rem] text-dark-soft transition-transform duration-300" :class="open === '{{ $itemId }}' && 'rotate-180'"></i>
                                </button>
                                <div x-show="open === '{{ $itemId }}'" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="px-6 pb-5">
                                    <p class="max-w-[62ch] leading-[1.75] font-light text-dark-soft">{{ $item['a'] }}</p>
                                    @if (!empty($item['list']))
                                        <ul class="mt-3 flex flex-col gap-1.5">
                                            @foreach ($item['list'] as $line)
                                                <li class="flex items-center gap-2.5 text-[.92rem] font-light text-dark-soft"><i class="fa-light fa-clock text-[.8rem] text-primary-deep"></i>{{ $line }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Geen resultaten --}}
        <div x-cloak x-show="hits === 0" class="rounded-card border border-primary/15 bg-offwhite p-12 text-center">
            <i class="fa-light fa-magnifying-glass mb-4 text-[2rem] text-primary"></i>
            <h3 class="mb-2 font-serif text-[1.4rem] font-medium">Niets gevonden</h3>
            <p class="mb-6 font-light text-dark-soft">Geen vragen gevonden voor "<span x-text="query"></span>". Probeer een ander zoekwoord of stel je vraag direct.</p>
            <button type="button" @click="query = ''" class="inline-flex items-center gap-2.5 rounded-full border border-dark/20 px-6 py-3 text-[.88rem] font-semibold transition-colors hover:border-dark">Wis zoekopdracht</button>
        </div>

        {{-- Contact-CTA --}}
        <div class="load-reveal relative mt-14 overflow-hidden rounded-[calc(var(--radius)+10px)] bg-linear-[120deg] from-primary to-primary-deep px-8 py-12 text-center text-white before:absolute before:-top-[120px] before:-left-16 before:h-[240px] before:w-[240px] before:rounded-full before:bg-white/10 before:content-[''] after:absolute after:-right-10 after:-bottom-[100px] after:h-[200px] after:w-[200px] after:rounded-full after:bg-white/10 after:content-['']">
            <h2 class="relative z-[1] mb-2 font-serif text-[clamp(1.5rem,2.8vw,2rem)] font-normal">Staat je vraag er <em class="italic">niet tussen</em>?</h2>
            <p class="relative z-[1] mb-7 font-light opacity-90">Ons team denkt graag met je mee - we reageren meestal binnen enkele uren.</p>
            <div class="relative z-[1] flex flex-wrap items-center justify-center gap-3.5">
                <a href="mailto:info@deluxenailshop.nl" class="inline-flex items-center gap-2.5 rounded-full bg-dark px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-[color-mix(in_srgb,var(--color-dark)_70%,black)]">
                    <i class="fa-light fa-envelope"></i> Stuur een e-mail
                </a>
                <a href="https://wa.me/31612345678" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 rounded-full bg-white/95 px-7 py-3.5 text-[.9rem] font-semibold text-dark transition-colors hover:bg-white">
                    <i class="fa-brands fa-whatsapp text-[1.05rem]"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
