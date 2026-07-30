<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

@php
    /*
    | SEO. Pagina's kunnen 'title', 'meta_description', 'meta_keywords',
    | 'robots', 'og_type' en 'og_image' als section aanleveren; alles heeft
    | een verstandige standaardwaarde. Beheer- en accountpagina's worden
    | altijd uit de zoekresultaten gehouden.
    */
    $seoTitel = trim($__env->yieldContent('title', config('app.name').' - Professionele nagelproducten'));
    $seoOmschrijving = trim($__env->yieldContent('meta_description', 'Dé webshop voor professionele nagelproducten. Rubber base, gellak, builder gel, acrygel en nail art van DNKa\' en Valeri. Voor 16:00 besteld, morgen in huis. Gratis verzending vanaf €75.'));
    $seoKeywords = trim($__env->yieldContent('meta_keywords', 'nagelproducten, gellak, gelpolish, rubber base, builder gel, acrygel, polygel, nail art, DNKa, Valeri, nagelstyliste, professionele nagelproducten kopen'));
    $seoRobots = request()->is('admin*', 'account', 'afrekenen*', 'bedankt/*', 'login', 'registreren', 'wachtwoord-vergeten')
        ? 'noindex, nofollow'
        : trim($__env->yieldContent('robots', 'index, follow'));
    $seoAfbeelding = trim($__env->yieldContent('og_image', asset('logo/deluxenailshop_transp_primair_v1.png')));
    $seoType = trim($__env->yieldContent('og_type', 'website'));
@endphp

{{-- Sectie-waarden zijn door Blade al ge-escaped (inline @section), dus hier bewust {!! !!} --}}
<title>{!! $seoTitel !!}</title>
<meta name="description" content="{!! $seoOmschrijving !!}">
<meta name="keywords" content="{!! $seoKeywords !!}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="author" content="{{ config('app.name') }}">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Delen via social media (Open Graph + Twitter) --}}
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="nl_NL">
<meta property="og:type" content="{!! $seoType !!}">
<meta property="og:title" content="{!! $seoTitel !!}">
<meta property="og:description" content="{!! $seoOmschrijving !!}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{!! $seoAfbeelding !!}">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{!! $seoTitel !!}">
<meta name="twitter:description" content="{!! $seoOmschrijving !!}">
<meta name="twitter:image" content="{!! $seoAfbeelding !!}">

{{-- Favicon (gegenereerd uit het logo) --}}
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

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
