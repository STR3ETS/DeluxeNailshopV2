<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

@php
    /*
    | SEO. Pagina's kunnen 'title', 'meta_description', 'meta_keywords',
    | 'robots', 'canonical', 'og_type' en 'og_image' als section aanleveren;
    | alles heeft een verstandige standaardwaarde. Beheer- en accountpagina's
    | worden altijd uit de zoekresultaten gehouden. Structured data (JSON-LD)
    | pushen pagina's naar de stack 'structured-data'.
    */
    $seoTitel = trim($__env->yieldContent('title', config('app.name').' - Professionele nagelproducten'));
    $seoOmschrijving = trim($__env->yieldContent('meta_description', 'Professionele nagelproducten van DNKa\', Valeri en Touch: rubber base, gellak, builder gel, acrygel en nail art. Gratis verzending vanaf €'.config('shop.verzending.NL.gratis_vanaf').' (NL) en €'.config('shop.verzending.BE.gratis_vanaf').' (BE).'));
    $seoKeywords = trim($__env->yieldContent('meta_keywords', 'nagelproducten, gellak, gelpolish, rubber base, builder gel, acrygel, polygel, nail art, DNKa, Valeri, nagelstyliste, professionele nagelproducten kopen'));
    $seoRobots = request()->is('admin*', 'account', 'afrekenen*', 'bedankt/*', 'test/*', 'login', 'registreren', 'wachtwoord-vergeten')
        ? 'noindex, nofollow'
        : trim($__env->yieldContent('robots', 'index, follow'));
    $seoIndexeerbaar = ! str_contains($seoRobots, 'noindex');
    // Standaard de huidige URL zonder querystring (zelf escapen: komt uit de request)
    $seoCanonical = $__env->hasSection('canonical') ? trim($__env->yieldContent('canonical')) : e(url()->current());

    // Standaard deelafbeelding is 1200x630 (public/og-image.jpg); productpagina's geven hun eigen foto mee
    $seoEigenAfbeelding = $__env->hasSection('og_image');
    $seoAfbeelding = $seoEigenAfbeelding ? trim($__env->yieldContent('og_image')) : asset('og-image.jpg');
    $seoType = trim($__env->yieldContent('og_type', 'website'));
@endphp

{{-- Sectie-waarden zijn door Blade al ge-escaped (inline @section), dus hier bewust {!! !!} --}}
<title>{!! $seoTitel !!}</title>
<meta name="description" content="{!! $seoOmschrijving !!}">
<meta name="keywords" content="{!! $seoKeywords !!}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="author" content="{{ config('app.name') }}">
@if ($seoIndexeerbaar)
    <link rel="canonical" href="{!! $seoCanonical !!}">
@endif

{{-- Delen via social media (Open Graph + Twitter) --}}
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="nl_NL">
<meta property="og:locale:alternate" content="nl_BE">
<meta property="og:type" content="{!! $seoType !!}">
<meta property="og:title" content="{!! $seoTitel !!}">
<meta property="og:description" content="{!! $seoOmschrijving !!}">
<meta property="og:url" content="{!! $seoCanonical !!}">
<meta property="og:image" content="{!! $seoAfbeelding !!}">
@unless ($seoEigenAfbeelding)
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endunless
<meta property="og:image:alt" content="{!! $seoTitel !!}">
<meta name="twitter:card" content="{{ $seoEigenAfbeelding ? 'summary' : 'summary_large_image' }}">
<meta name="twitter:title" content="{!! $seoTitel !!}">
<meta name="twitter:description" content="{!! $seoOmschrijving !!}">
<meta name="twitter:image" content="{!! $seoAfbeelding !!}">

{{-- Favicon (gegenereerd uit het logo), app-manifest en browserbalk-kleur op mobiel --}}
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
<meta name="theme-color" content="{{ config('theme.colors.bg') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

@stack('structured-data')

@vite(['resources/css/app.css', 'resources/js/app.js'])
