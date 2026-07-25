<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', config('app.name'))</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="{{ config('theme.fonts.google') }}" rel="stylesheet">
<link href="{{ asset('fontawesome-pro-7.3.1-web/css/all.min.css') }}" rel="stylesheet">

{{-- Theme-variabelen uit config/theme.php; app.css koppelt ze aan Tailwind-tokens --}}
<style>
    :root{
    @foreach (config('theme.colors') as $name => $value)
        --{{ $name }}:{{ $value }};
    @endforeach
        --radius:{{ config('theme.radius') }};
        --serif:{!! config('theme.fonts.serif') !!};
        --sans:{!! config('theme.fonts.sans') !!};
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
