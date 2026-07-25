{{--
    Layout voor het beheerpaneel: zwevende icon-rail links zonder
    achtergrond - ronde icoonknoppen met label eronder, verticaal
    gecentreerd, met onderin het profiel en uitloggen. Op mobiel een
    zwevende onderbalk. Modules komen uit config/admin.php.
--}}
@php
    $adminItems = array_merge(
        [['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fa-gauge-high']],
        config('admin.modules'),
    );

    $adminInitialen = collect(explode(' ', auth()->user()->name))
        ->map(fn ($woord) => mb_substr($woord, 0, 1))
        ->take(2)
        ->implode('');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="overflow-x-hidden bg-cream font-sans text-dark antialiased">

{{-- Zwevende icon-rail (desktop) --}}
<aside class="fixed inset-y-0 left-0 z-[70] hidden w-[104px] lg:block" aria-label="Beheer">
    <nav class="absolute top-6 left-1/2 flex -translate-x-1/2 flex-col items-center gap-4">
        @foreach ($adminItems as $item)
            @php $actief = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}" class="group flex flex-col items-center gap-1.5" @if($actief) aria-current="page" @endif>
                <span class="grid h-12 w-12 place-items-center rounded-full border transition-all duration-300 {{ $actief
                    ? 'border-primary bg-primary text-white shadow-[0_10px_22px_-8px_color-mix(in_srgb,var(--color-primary)_70%,transparent)]'
                    : 'border-primary/15 bg-offwhite text-dark group-hover:border-primary/40 group-hover:text-primary-deep group-hover:shadow-card' }}">
                    <i class="fa-light {{ $item['icon'] }} text-[1rem]"></i>
                </span>
                <span class="text-[.62rem] font-semibold tracking-[.04em] {{ $actief ? 'text-primary-deep' : 'text-dark-soft' }}">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="absolute bottom-6 left-1/2 flex -translate-x-1/2 flex-col items-center gap-3.5">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-dark text-[.75rem] font-bold text-gold" title="{{ auth()->user()->name }} - Beheerder">{{ strtoupper($adminInitialen) }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="grid h-11 w-11 place-items-center rounded-full border border-primary/15 bg-offwhite text-dark transition-all duration-300 hover:border-primary/40 hover:text-primary-deep hover:shadow-card" aria-label="Uitloggen" title="Uitloggen">
                <i class="fa-light fa-arrow-right-from-bracket text-[.95rem]"></i>
            </button>
        </form>
    </div>
</aside>

{{-- Zwevende onderbalk (mobiel) --}}
<div class="fixed inset-x-4 bottom-4 z-[70] lg:hidden">
    <nav class="mx-auto flex w-fit max-w-full items-center gap-1.5 overflow-x-auto rounded-full border border-primary/15 bg-offwhite/90 px-3 py-2 shadow-card backdrop-blur-[10px] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden" aria-label="Beheer">
        @foreach ($adminItems as $item)
            @php $actief = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}" aria-label="{{ $item['label'] }}" title="{{ $item['label'] }}"
               class="grid h-10 w-10 shrink-0 place-items-center rounded-full transition-colors {{ $actief ? 'bg-primary text-white' : 'text-dark hover:bg-cream-deep' }}">
                <i class="fa-light {{ $item['icon'] }} text-[.95rem]"></i>
            </a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
            @csrf
            <button type="submit" class="grid h-10 w-10 place-items-center rounded-full text-dark transition-colors hover:bg-cream-deep" aria-label="Uitloggen" title="Uitloggen">
                <i class="fa-light fa-arrow-right-from-bracket text-[.95rem]"></i>
            </button>
        </form>
    </nav>
</div>

<div class="flex min-h-svh flex-col lg:pl-[104px]">
    <main class="mx-auto w-full max-w-[1140px] flex-1 px-6 py-10 pb-32 lg:pb-12">
        @yield('content')
    </main>

    <footer class="border-t border-primary/15">
        <div class="mx-auto flex max-w-[1140px] flex-wrap items-center justify-between gap-3 px-6 py-5 pb-24 text-[.78rem] text-dark-soft lg:pb-5">
            <span>© {{ date('Y') }} {{ config('app.name') }} - Alle rechten voorbehouden</span>
            <span>Gemaakt door <a href="https://halfmanmedia.nl" target="_blank" rel="noopener" class="font-medium transition-colors hover:text-primary-deep">HalfmanMedia</a></span>
        </div>
    </footer>
</div>

@stack('scripts')
</body>
</html>
