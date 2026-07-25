@extends('layouts.admin')

@section('title', 'Producten - Beheer ' . config('app.name'))

@php
    $catNames = collect(config('shop.categories'))->pluck('name', 'slug');
@endphp

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
        <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Producten</h1>
    </div>
    <a href="{{ route('admin.producten.nieuw') }}" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-6 py-3 text-[.9rem] font-semibold text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep">
        <i class="fa-light fa-plus"></i> Nieuw product
    </a>
</div>

@if (session('status'))
    <p class="load-reveal mt-6 rounded-2xl border border-primary/20 bg-accent-soft/60 px-5 py-3.5 text-[.9rem] text-dark">
        <i class="fa-light fa-circle-check mr-1.5 text-primary-deep"></i>{{ session('status') }}
    </p>
@endif

<div class="load-reveal mt-7" x-data="{ zoek: '' }">
    <div class="relative max-w-[380px]">
        <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
        <input type="search" x-model="zoek" placeholder="Zoek op naam, merk of categorie…" aria-label="Zoek producten"
               class="w-full rounded-full border border-primary/20 bg-offwhite py-3 pr-6 pl-12 text-[.9rem] outline-none transition-colors placeholder:text-dark-soft/60 focus:border-primary">
    </div>

    <div class="mt-6 divide-y divide-primary/10 overflow-hidden rounded-card border border-primary/15 bg-offwhite">
        @forelse ($producten as $p)
            <div class="grid grid-cols-[56px_minmax(0,1fr)_auto] items-center gap-x-6 px-6 py-5 sm:grid-cols-[56px_minmax(0,1fr)_130px_auto] md:grid-cols-[56px_minmax(0,1fr)_160px_130px_auto] lg:grid-cols-[64px_minmax(0,1fr)_160px_130px_180px_auto]"
                 x-show="zoek.trim() === '' || $el.dataset.zoek.includes(zoek.toLowerCase())"
                 data-zoek="{{ mb_strtolower($p->brand.' '.$p->name.' '.($catNames[$p->category] ?? '')) }}">

                <span class="grid h-14 w-14 place-items-center overflow-hidden rounded-xl lg:h-16 lg:w-16" style="background:linear-gradient(160deg,{{ $p->bg_from }},{{ $p->bg_to }})">
                    @if ($p->image)
                        <img src="{{ asset($p->image) }}" alt="" class="max-h-12 w-auto object-contain">
                    @endif
                </span>

                <div class="min-w-0">
                    <a href="{{ route('admin.producten.bewerken', $p) }}" class="block truncate text-[.95rem] font-medium transition-colors hover:text-primary-deep">{{ $p->name }}</a>
                    <p class="mt-0.5 flex items-center gap-2 text-[.68rem] font-bold tracking-[.16em] text-primary-deep uppercase">
                        {{ $p->brand }}
                        @unless ($p->actief)
                            <span class="rounded-full border border-dark/15 px-2 py-0.5 text-[.6rem] font-semibold tracking-[.08em] text-dark-soft normal-case">Inactief</span>
                        @endunless
                    </p>
                </div>

                <span class="hidden text-[.82rem] text-dark-soft md:block">{{ $catNames[$p->category] ?? $p->category }}</span>

                <span class="hidden text-right font-serif text-[1.05rem] font-semibold sm:block">
                    @if ($p->old_price)<s class="mr-1 text-[.78rem] font-normal text-dark-soft">€{{ number_format($p->old_price, 2, ',', '.') }}</s>@endif€{{ number_format($p->price, 2, ',', '.') }}
                </span>

                <span class="hidden lg:block">
                    @if ($p->voorraad === 0)
                        <span class="inline-block rounded-full bg-red-100 px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] text-red-700 uppercase">0 op voorraad</span>
                    @elseif ($p->voorraad < 10)
                        <span class="inline-block rounded-full bg-orange-100 px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] text-orange-700 uppercase">{{ $p->voorraad }} op voorraad</span>
                    @else
                        <span class="inline-block rounded-full border border-dark/15 px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] text-dark-soft uppercase">{{ $p->voorraad }} op voorraad</span>
                    @endif
                </span>

                <div class="flex items-center justify-end gap-1.5">
                    <a href="{{ route('admin.producten.bewerken', $p) }}" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep" aria-label="Bewerken" title="Bewerken">
                        <i class="fa-light fa-pen text-[.9rem]"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.producten.dupliceren', $p) }}">
                        @csrf
                        <button type="submit" class="grid h-10 w-10 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Dupliceren" title="Dupliceren">
                            <i class="fa-light fa-copy text-[.9rem]"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.producten.verwijderen', $p) }}" x-data="{ bevestig: false }" @click.outside="bevestig = false">
                        @csrf
                        @method('DELETE')
                        <button type="button" x-show="!bevestig" @click="bevestig = true" class="grid h-10 w-10 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Verwijderen" title="Verwijderen">
                            <i class="fa-light fa-trash-can text-[.9rem]"></i>
                        </button>
                        <span x-cloak x-show="bevestig" class="flex items-center gap-1">
                            <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-[.75rem] font-semibold text-white transition-colors hover:bg-red-700">Zeker?</button>
                            <button type="button" @click="bevestig = false" class="grid h-9 w-9 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Annuleren" title="Annuleren">
                                <i class="fa-light fa-xmark text-[.9rem]"></i>
                            </button>
                        </span>
                    </form>
                </div>
            </div>
        @empty
            <p class="px-6 py-12 text-center font-light text-dark-soft">Nog geen producten - voeg je eerste product toe.</p>
        @endforelse
    </div>
</div>

@endsection
