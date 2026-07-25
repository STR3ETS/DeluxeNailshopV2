@extends('layouts.admin')

@section('title', $titel . ' - Beheer ' . config('app.name'))

@section('content')

<div class="load-reveal">
    <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">{{ $titel }}</h1>
</div>

<div class="load-reveal mt-9 rounded-card border border-primary/15 bg-offwhite p-12 text-center">
    <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light fa-screwdriver-wrench text-[1.3rem]"></i></span>
    <h2 class="mt-5 mb-2 font-serif text-[1.4rem] font-medium">Deze module bouwen we <em class="text-primary italic">hierna</em></h2>
    <p class="font-light text-dark-soft">"{{ $titel }}" staat al klaar in het menu - de inhoud volgt in een volgende stap.</p>
</div>

@endsection
