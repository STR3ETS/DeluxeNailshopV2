<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="overflow-x-hidden bg-cream font-sans text-dark antialiased">

@include('partials.header')

@yield('content')

@include('partials.footer')

@include('partials.cookie-consent')

@stack('scripts')
</body>
</html>
