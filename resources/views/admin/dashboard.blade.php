@extends('layouts.admin')

@section('title', 'Dashboard - Beheer ' . config('app.name'))

@php
    /*
    | Lijngrafiek "Bestellingen afgelopen 7 dagen" als inline SVG.
    | Eén serie: lijn 2px in de primaire kleur, punten met surface-ring,
    | terughoudende gridlijnen en labels in tekstkleuren.
    */
    $w = 720; $h = 230;
    $padL = 30; $padR = 14; $padT = 14; $padB = 34;
    $plotW = $w - $padL - $padR;
    $plotH = $h - $padT - $padB;
    $maxY = max(1, $chart->max('count'));
    $n = $chart->count();

    $pts = $chart->values()->map(function ($d, $i) use ($padL, $padT, $plotW, $plotH, $maxY, $n) {
        return $d + [
            'x' => round($padL + ($n > 1 ? $i * $plotW / ($n - 1) : $plotW / 2), 1),
            'y' => round($padT + $plotH - ($d['count'] / $maxY) * $plotH, 1),
        ];
    });
    $polyline = $pts->map(fn ($p) => $p['x'] . ',' . $p['y'])->implode(' ');

    $voornaam = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
@endphp

@section('content')

{{-- Welkomstpaneel --}}
<div class="load-reveal relative overflow-hidden rounded-[calc(var(--radius)+10px)] bg-dark p-8 text-cream sm:p-11">
    <div class="pointer-events-none absolute -bottom-28 -left-16 h-[260px] w-[260px] rounded-full bg-primary/15"></div>

    <span class="relative text-[.7rem] font-semibold tracking-[.22em] text-gold uppercase">Beheerpaneel</span>
    <h1 class="relative mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.7rem)] leading-[1.12] font-normal">Welkom in <em class="text-gold italic">jouw beheerpaneel</em>,<br>{{ $voornaam }}</h1>

    <div class="relative mt-6 flex flex-col gap-2.5">
        <div class="flex items-center gap-3">
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-gold/15 text-gold"><i class="fa-light fa-cubes text-[.78rem]"></i></span>
            <p class="text-[.9rem] font-light text-cream/85">Je hebt deze maand <b class="font-semibold text-cream">{{ $verkochtDezeMaand }}</b> {{ $verkochtDezeMaand === 1 ? 'product' : 'producten' }} verkocht.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-gold/15 text-gold"><i class="fa-light fa-box-open text-[.78rem]"></i></span>
            <p class="text-[.9rem] font-light text-cream/85">
                @if ($openBestellingen === 0)
                    Er staan geen bestellingen meer open.
                @elseif ($openBestellingen === 1)
                    Er staat nog <b class="font-semibold text-cream">1</b> bestelling open.
                @else
                    Er staan nog <b class="font-semibold text-cream">{{ $openBestellingen }}</b> bestellingen open.
                @endif
            </p>
        </div>
    </div>

    <div class="relative mt-8 flex flex-wrap gap-x-14 gap-y-5 border-t border-cream/10 pt-7">
        <div>
            <small class="text-[.7rem] font-semibold tracking-[.16em] text-cream/60 uppercase">Omzet afgelopen 30 dagen</small>
            <p class="mt-1 font-serif text-[1.9rem] leading-none font-semibold text-gold">€ {{ number_format($omzet30, 2, ',', '.') }}</p>
        </div>
        <div>
            <small class="text-[.7rem] font-semibold tracking-[.16em] text-cream/60 uppercase">Omzet afgelopen 7 dagen</small>
            <p class="mt-1 font-serif text-[1.9rem] leading-none font-semibold text-gold">€ {{ number_format($omzet7, 2, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Grafiek --}}
<div class="load-reveal mt-6 rounded-card border border-primary/15 bg-offwhite p-6 sm:p-7">
    <div class="mb-5 flex items-center gap-3.5">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-chart-line text-[.95rem]"></i></span>
        <h2 class="font-serif text-[1.3rem] leading-[1.2] font-medium">Bestellingen <em class="text-primary italic">afgelopen 7 dagen</em></h2>
    </div>

    <svg viewBox="0 0 {{ $w }} {{ $h }}" class="h-auto w-full" role="img" aria-label="Lijngrafiek van het aantal bestellingen per dag in de afgelopen 7 dagen">
        @foreach ($pts as $p)
            <line x1="{{ $p['x'] }}" y1="{{ $padT }}" x2="{{ $p['x'] }}" y2="{{ $padT + $plotH }}" style="stroke: var(--color-cream-deep)" stroke-width="1"/>
        @endforeach

        <line x1="{{ $padL }}" y1="{{ $padT + $plotH }}" x2="{{ $padL + $plotW }}" y2="{{ $padT + $plotH }}" style="stroke: color-mix(in srgb, var(--color-dark) 22%, transparent)" stroke-width="1"/>
        <text x="{{ $padL - 9 }}" y="{{ $padT + 4 }}" text-anchor="end" font-size="11" style="fill: var(--color-dark-soft)">{{ $maxY }}</text>
        <text x="{{ $padL - 9 }}" y="{{ $padT + $plotH + 4 }}" text-anchor="end" font-size="11" style="fill: var(--color-dark-soft)">0</text>

        <polyline points="{{ $polyline }}" fill="none" style="stroke: var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>

        @foreach ($pts as $p)
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4.5" style="fill: var(--color-primary); stroke: var(--color-offwhite)" stroke-width="2"/>
            <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="13" fill="transparent">
                <title>{{ $p['label'] }}: {{ $p['count'] }} {{ $p['count'] === 1 ? 'bestelling' : 'bestellingen' }}</title>
            </circle>
        @endforeach

        @foreach ($pts as $i => $p)
            <text x="{{ $p['x'] }}" y="{{ $h - 8 }}" text-anchor="{{ $i === 0 ? 'start' : ($i === $n - 1 ? 'end' : 'middle') }}" font-size="11" style="fill: var(--color-dark-soft)">{{ $p['label'] }}</text>
        @endforeach
    </svg>
</div>

{{-- Module-tegels --}}
<h2 class="load-reveal mt-11 font-serif text-[1.5rem] leading-[1.2] font-normal">Snel naar <em class="text-primary italic">een module</em></h2>
<div class="load-reveal mt-5 grid grid-cols-[repeat(auto-fill,minmax(210px,1fr))] gap-5">
    @foreach (config('admin.modules') as $module)
        <a href="{{ route($module['route']) }}" class="group relative flex min-h-[150px] flex-col gap-3 overflow-hidden rounded-card border border-primary/15 bg-offwhite p-6 transition-[translate,box-shadow,border-color] duration-[350ms] ease-spring hover:-translate-y-1.5 hover:border-primary/40 hover:shadow-card">
            <span class="grid h-12 w-12 place-items-center rounded-[58%_42%_55%_45%/50%_60%_40%_50%] bg-accent-soft text-primary-deep transition-transform duration-500 ease-spring group-hover:scale-110 group-hover:rotate-[14deg]"><i class="fa-light {{ $module['icon'] }} text-[1.05rem]"></i></span>
            <span class="absolute top-5 right-5 grid h-8 w-8 place-items-center rounded-full border border-dark/20 text-[.85rem] transition-all duration-300 group-hover:-rotate-45 group-hover:border-primary group-hover:bg-primary group-hover:text-white"><i class="fa-light fa-arrow-right"></i></span>
            <h3 class="mt-auto font-serif text-[1.15rem] font-medium">{{ $module['label'] }}</h3>
            <small class="text-[.78rem] tracking-[.02em] text-dark-soft">{{ $module['sub'] }}</small>
        </a>
    @endforeach
</div>

@endsection
