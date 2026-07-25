@extends('layouts.shop')

@section('title', 'Cookiebeleid - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Cookiespagina
    |--------------------------------------------------------------------------
    | Gebaseerd op artikel 11 van het privacybeleid. De knop
    | "Cookievoorkeuren aanpassen" heropent de consentbar.
    */

    $cookieCategories = [
        [
            'icon'   => 'fa-gear',
            'title'  => 'Functionele cookies',
            'text'   => 'Zoals sessie- en login cookies voor het bijhouden van sessie- en inloginformatie. Zonder deze cookies werken onder andere je winkelwagen en het inloggen niet.',
            'cookies' => ['Sessiecookies', 'Logincookies'],
            'locked' => true,
        ],
        [
            'icon'   => 'fa-chart-simple',
            'title'  => 'Geanonimiseerde analytische cookies',
            'text'   => 'Om inzage te krijgen in het bezoek aan onze website op basis van informatie over bezoekersaantallen, populaire pagina\'s en onderwerpen. We kunnen niet zien wie onze websites bezoekt of vanaf welke pc het bezoek plaatsvindt.',
            'cookies' => ['Google Analytics (geanonimiseerd)'],
            'locked' => false,
        ],
        [
            'icon'   => 'fa-chart-line',
            'title'  => 'Analytische cookies',
            'text'   => 'Om inzage te krijgen in het bezoek aan onze website op basis van informatie over bezoekersaantallen, populaire pagina\'s en onderwerpen. Zo stemmen we de communicatie en informatievoorziening beter af op de behoeften van bezoekers.',
            'cookies' => ['Google Analytics'],
            'locked' => false,
        ],
        [
            'icon'   => 'fa-bullhorn',
            'title'  => 'Tracking cookies',
            'text'   => 'Zoals advertentiecookies die zijn bedoeld voor het tonen van relevante advertenties. Uit de informatie over bezochte websites kunnen persoonlijke interesses worden afgeleid, waarmee bijvoorbeeld gerichte advertenties getoond kunnen worden.',
            'cookies' => ['Facebook', 'Google Adwords'],
            'locked' => false,
        ],
    ];
@endphp

@section('content')

<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[860px]">

        {{-- Breadcrumb --}}
        <nav class="load-reveal mb-5 flex items-center gap-2.5 text-[.8rem] text-dark-soft" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="transition-colors hover:text-primary-deep">Home</a>
            <i class="fa-light fa-angle-right text-[.65rem]"></i>
            <span class="font-medium text-dark">Cookies</span>
        </nav>

        {{-- Paginakop --}}
        <div class="load-reveal mb-10">
            <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">Cookie<em class="text-primary italic">beleid</em></h1>
            <p class="mt-4 max-w-[64ch] leading-[1.8] font-light text-dark-soft">Een cookie is een klein tekstbestand dat bij bezoek aan onze website geplaatst wordt op de harde schijf van uw computer. Een cookie bevat gegevens zodat u bij elk bezoek aan onze website als bezoeker kan worden herkend. Het is dan mogelijk om onze website speciaal op u in te stellen en het inloggen te vergemakkelijken.</p>
            <p class="mt-3 max-w-[64ch] leading-[1.8] font-light text-dark-soft">Uw toestemming is geldig voor een periode van dertien maanden. Hieronder zie je welke cookies we gebruiken - je voorkeuren pas je op ieder moment aan.</p>
        </div>

        {{-- Voorkeuren aanpassen --}}
        <div class="load-reveal mb-10 flex flex-wrap items-center justify-between gap-4 rounded-card border border-primary/15 bg-offwhite p-6">
            <div class="flex items-center gap-3.5">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light fa-sliders text-[1rem]"></i></span>
                <div>
                    <p class="font-serif text-[1.1rem] font-medium">Jouw cookievoorkeuren</p>
                    <p class="text-[.85rem] font-light text-dark-soft">Aanpassen kan wanneer je maar wilt.</p>
                </div>
            </div>
            {{-- x-data is nodig: zonder Alpine-scope wordt @click genegeerd --}}
            <button type="button" x-data @click="window.dispatchEvent(new CustomEvent('open-cookie-settings'))"
                    class="inline-flex items-center gap-2.5 rounded-full bg-primary px-6 py-3 text-[.88rem] font-semibold text-white transition-colors hover:bg-primary-deep">
                Cookievoorkeuren aanpassen
            </button>
        </div>

        {{-- Categorieën --}}
        <div class="flex flex-col gap-5">
            @foreach ($cookieCategories as $category)
                <div class="load-reveal rounded-card border border-primary/15 bg-offwhite p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex items-center gap-3.5">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light {{ $category['icon'] }} text-[.95rem]"></i></span>
                            <h2 class="font-serif text-[1.2rem] font-medium">{{ $category['title'] }}</h2>
                        </div>
                        @if ($category['locked'])
                            <span class="rounded-full bg-primary/10 px-3 py-1 text-[.7rem] font-semibold tracking-[.1em] text-primary-deep uppercase">Altijd actief</span>
                        @else
                            <span class="rounded-full border border-dark/15 px-3 py-1 text-[.7rem] font-semibold tracking-[.1em] text-dark-soft uppercase">Optioneel</span>
                        @endif
                    </div>
                    <p class="mt-3.5 leading-[1.75] font-light text-dark-soft">{{ $category['text'] }}</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($category['cookies'] as $cookie)
                            <span class="rounded-full bg-cream-deep px-3.5 py-1.5 text-[.78rem] font-medium text-dark-soft">{{ $cookie }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Meer informatie --}}
        <p class="load-reveal mt-10 leading-[1.8] font-light text-dark-soft">Meer weten? Lees <a href="{{ url('/privacybeleid') }}#artikel-11" class="font-medium text-primary-deep transition-colors hover:text-primary">artikel 11 van ons privacybeleid</a>, of bekijk de uitleg van de <a href="https://autoriteitpersoonsgegevens.nl/nl/onderwerpen/internet-telefoon-tv-en-post/cookies#faq" target="_blank" rel="noopener" class="font-medium text-primary-deep transition-colors hover:text-primary">Autoriteit Persoonsgegevens</a> over het gebruik, beheer en verwijderen van cookies.</p>
    </div>
</section>

@endsection
