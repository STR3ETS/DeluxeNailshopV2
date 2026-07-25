@extends('layouts.admin')

@section('title', 'Voorraad - Beheer ' . config('app.name'))

@section('content')

<div class="load-reveal">
    <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Voorraad</h1>
</div>

@if (session('status'))
    <p class="load-reveal mt-6 rounded-2xl border border-primary/20 bg-accent-soft/60 px-5 py-3.5 text-[.9rem] text-dark">
        <i class="fa-light fa-circle-check mr-1.5 text-primary-deep"></i>{{ session('status') }}
    </p>
@endif

{{-- Kerncijfers --}}
<div class="load-reveal mt-7 grid gap-5 sm:grid-cols-3">
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Producten</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-bottle-droplet text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">{{ $producten->count() }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Bijna op (&lt; 10)</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-triangle-exclamation text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold text-primary-deep">{{ $bijnaOp }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Uitverkocht</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-box-open text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">{{ $uitverkocht }}</p>
    </div>
</div>

{{-- Voorraadlijst --}}
<div class="load-reveal mt-7" x-data="{ zoek: '' }">
    <div class="relative max-w-[380px]">
        <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
        <input type="search" x-model="zoek" placeholder="Zoek op naam of merk…" aria-label="Zoek in voorraad"
               class="w-full rounded-full border border-primary/20 bg-offwhite py-3 pr-6 pl-12 text-[.9rem] outline-none transition-colors placeholder:text-dark-soft/60 focus:border-primary">
    </div>

    <div class="mt-6 divide-y divide-primary/10 overflow-hidden rounded-card border border-primary/15 bg-offwhite">
        @foreach ($producten as $p)
            <div class="grid grid-cols-[48px_minmax(0,1fr)_auto] items-center gap-x-6 px-6 py-5 lg:grid-cols-[56px_minmax(0,1fr)_180px_auto]"
                 x-show="zoek.trim() === '' || $el.dataset.zoek.includes(zoek.toLowerCase())"
                 data-zoek="{{ mb_strtolower($p->brand.' '.$p->name) }}">

                <span class="grid h-12 w-12 place-items-center overflow-hidden rounded-xl lg:h-14 lg:w-14" style="background:linear-gradient(160deg,{{ $p->bg_from }},{{ $p->bg_to }})">
                    @if ($p->image)
                        <img src="{{ asset($p->image) }}" alt="" class="max-h-10 w-auto object-contain">
                    @endif
                </span>

                <div class="min-w-0">
                    <p class="truncate text-[.92rem] font-medium">{{ $p->name }}</p>
                    <p class="mt-0.5 text-[.66rem] font-bold tracking-[.16em] text-primary-deep uppercase">{{ $p->brand }}</p>
                </div>

                <span class="hidden lg:block">
                    @if ($p->voorraad === 0)
                        <span class="inline-block rounded-full bg-red-100 px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] text-red-700 uppercase">0 op voorraad</span>
                    @elseif ($p->voorraad < 10)
                        <span class="inline-block rounded-full bg-orange-100 px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] text-orange-700 uppercase">{{ $p->voorraad }} op voorraad</span>
                    @endif
                </span>

                <form method="POST" action="{{ route('admin.voorraad.bijwerken', $p) }}" x-data="{ v: {{ $p->voorraad }} }" class="flex items-center justify-end gap-2">
                    @csrf
                    @method('PATCH')
                    <div class="inline-flex items-center gap-1 rounded-full border border-dark/15">
                        <button type="button" @click="v = Math.max(0, v - 1)" class="grid h-9 w-9 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Minder"><i class="fa-light fa-minus text-[.7rem]"></i></button>
                        <input type="number" name="voorraad" x-model.number="v" min="0" class="w-14 border-0 bg-transparent text-center text-[.92rem] font-semibold outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" aria-label="Voorraad">
                        <button type="button" @click="v++" class="grid h-9 w-9 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Meer"><i class="fa-light fa-plus text-[.7rem]"></i></button>
                    </div>
                    <button type="submit" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep {{ '' }}" :class="v === {{ $p->voorraad }} ? 'text-dark-soft/50' : 'bg-primary text-white hover:bg-primary-deep'" aria-label="Voorraad opslaan" title="Opslaan">
                        <i class="fa-light fa-check text-[.9rem]"></i>
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

@endsection
