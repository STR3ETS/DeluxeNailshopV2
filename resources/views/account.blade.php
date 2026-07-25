@extends('layouts.shop')

@section('title', 'Mijn account - ' . config('app.name'))

@php
    $voornaam = \Illuminate\Support\Str::before(auth()->user()->name, ' ');

    // Eerste opzet van het klantenportaal; deze onderdelen bouwen we later uit
    $accountBlokken = [
        ['icon' => 'fa-box-open', 'title' => 'Bestellingen', 'text' => 'Hier zie je straks je bestellingen, de status en je facturen.'],
        ['icon' => 'fa-user',     'title' => 'Mijn gegevens', 'text' => 'Beheer straks je adres- en accountgegevens voor sneller afrekenen.'],
        ['icon' => 'fa-heart',    'title' => 'Favorieten',    'text' => 'Bewaar je favoriete producten en kleuren op één plek.'],
    ];
@endphp

@section('content')

<section class="px-6 pt-10 pb-16">
    <div class="mx-auto max-w-[1200px]">

        <div class="load-reveal mb-10 flex flex-wrap items-end justify-between gap-6">
            <div>
                <h1 class="font-serif text-[clamp(2.2rem,4vw,3.2rem)] leading-[1.1] font-normal">Hoi <em class="text-primary italic">{{ $voornaam }}</em></h1>
                <p class="mt-2 font-light text-dark-soft">Welkom in jouw klantenportaal.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2.5 rounded-full border border-dark/20 px-6 py-3 text-[.88rem] font-semibold transition-colors hover:border-dark">
                    Uitloggen <i class="fa-light fa-arrow-right-from-bracket text-[.8rem]"></i>
                </button>
            </form>
        </div>

        <div class="load-reveal grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($accountBlokken as $blok)
                <div class="relative rounded-card border border-primary/15 bg-offwhite p-6">
                    <span class="absolute top-5 right-5 rounded-full border border-dark/15 px-3 py-1 text-[.68rem] font-semibold tracking-[.1em] text-dark-soft uppercase">Binnenkort</span>
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light {{ $blok['icon'] }} text-[1.05rem]"></i></span>
                    <h2 class="mt-4 font-serif text-[1.2rem] font-medium">{{ $blok['title'] }}</h2>
                    <p class="mt-1.5 text-[.9rem] leading-[1.65] font-light text-dark-soft">{{ $blok['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
