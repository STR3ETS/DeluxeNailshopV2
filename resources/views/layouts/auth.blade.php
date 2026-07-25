{{--
    Layout voor de klantenportaal-pagina's (inloggen, registreren,
    wachtwoord vergeten): geen header en footer, maar een viewport-vullend
    scherm met gecentreerde content en een terug-link naar de shop.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="overflow-x-hidden bg-cream font-sans text-dark antialiased">

<div class="relative flex min-h-svh flex-col">
    <a href="{{ url('/') }}" class="absolute top-5 left-5 z-10 inline-flex items-center gap-2 rounded-full px-4 py-2 text-[.85rem] font-medium text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark">
        <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar de shop
    </a>

    <main class="grid flex-1 place-items-center">
        @yield('content')
    </main>
</div>

@include('partials.cookie-consent')

@stack('scripts')
</body>
</html>
