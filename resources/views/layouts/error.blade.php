{{--
    Layout voor foutpagina's (403, 419, 429, 500, 503). Bewust zonder
    header, footer en winkelwagen: die raken de database en de sessie,
    en juist die kunnen bij een serverfout of onderhoud onbereikbaar zijn.
    De 404 gebruikt wel de volledige shoplayout (zie errors/404).

    Secties: code, icon, kop (mag <em> bevatten), tekst en optioneel acties.
--}}
@section('robots', 'noindex, nofollow')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-cream font-sans text-dark antialiased">

<div class="relative flex min-h-svh flex-col overflow-hidden">
    <div class="pointer-events-none absolute -top-24 -right-24 h-[320px] w-[320px] rounded-full bg-accent opacity-40 blur-[60px]"></div>
    <div class="pointer-events-none absolute -bottom-28 -left-20 h-[280px] w-[280px] rounded-full bg-gold opacity-30 blur-[60px]"></div>

    <header class="relative px-6 py-6">
        <a href="{{ url('/') }}" class="inline-block">
            <img src="{{ asset('logo/deluxenailshop_transp_primair_v1.png') }}" alt="{{ config('app.name') }}" class="h-11 w-auto">
        </a>
    </header>

    <main class="relative grid flex-1 place-items-center px-6 pt-4 pb-16">
        <div class="max-w-[560px] text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-accent-soft text-primary-deep">
                <i class="fa-light @yield('icon', 'fa-triangle-exclamation') text-[1.5rem]"></i>
            </span>
            <p class="mt-6 text-[.74rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Foutcode @yield('code')</p>
            <h1 class="mt-3 font-serif text-[clamp(2rem,6vw,3rem)] leading-[1.1] font-normal">@yield('kop')</h1>
            <p class="mx-auto mt-4 max-w-[46ch] leading-[1.75] font-light text-dark-soft">@yield('tekst')</p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                @hasSection('acties')
                    @yield('acties')
                @else
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep max-sm:w-full">
                        Naar de homepage <i class="fa-light fa-arrow-right"></i>
                    </a>
                    <a href="mailto:{{ config('shop.bedrijf.email') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-3.5 text-[.9rem] font-semibold transition-colors hover:border-dark max-sm:w-full">
                        <i class="fa-light fa-envelope"></i> Neem contact op
                    </a>
                @endif
            </div>
        </div>
    </main>

    <footer class="relative border-t border-primary/15 px-6 py-5 text-center text-[.78rem] text-dark-soft">
        © {{ date('Y') }} {{ config('app.name') }} · <a href="mailto:{{ config('shop.bedrijf.email') }}" class="font-medium transition-colors hover:text-primary-deep">{{ config('shop.bedrijf.email') }}</a>
    </footer>
</div>

</body>
</html>
