@extends('layouts.admin')

@section('title', 'Facturen - Beheer ' . config('app.name'))

@section('content')

<div class="load-reveal">
    <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Facturen</h1>
    <p class="mt-2 max-w-[62ch] text-[.9rem] font-light text-dark-soft">Facturen worden automatisch aangemaakt zodra een bestelling betaald is. Je vindt ze hier én op de detailpagina van de bestelling.</p>
</div>

{{-- Kerncijfers --}}
<div class="load-reveal mt-7 grid gap-5 sm:grid-cols-3">
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Facturen</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-file-invoice text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">{{ $facturen->count() }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Deze maand</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-calendar text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold text-primary-deep">{{ $dezeMaand }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Gefactureerd totaal</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-euro-sign text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">€ {{ number_format($totaalBedrag, 2, ',', '.') }}</p>
    </div>
</div>

{{-- Zoeken + lijst --}}
<div class="load-reveal mt-7" x-data="{ zoek: '' }">
    <div class="relative max-w-[380px]">
        <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
        <input type="search" x-model="zoek" placeholder="Zoek op factuurnummer, bestelling of klant…" aria-label="Zoek facturen"
               class="w-full rounded-full border border-primary/20 bg-offwhite py-3 pr-6 pl-12 text-[.9rem] outline-none transition-colors placeholder:text-dark-soft/60 focus:border-primary">
    </div>

    <div class="mt-6 divide-y divide-primary/10 overflow-hidden rounded-card border border-primary/15 bg-offwhite">
        @forelse ($facturen as $f)
            <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-5 px-6 py-5 sm:grid-cols-[130px_minmax(0,1fr)_120px_auto]"
                 x-show="zoek.trim() === '' || $el.dataset.zoek.includes(zoek.toLowerCase())"
                 data-zoek="{{ mb_strtolower($f->number.' '.$f->order->nummer().' '.$f->order->name.' '.$f->order->email) }}">

                <span class="hidden font-serif text-[1rem] font-semibold sm:block">{{ $f->number }}</span>

                <span class="min-w-0">
                    <span class="block truncate text-[.95rem] font-medium"><span class="sm:hidden">{{ $f->number }} · </span>{{ $f->order->name }}</span>
                    <span class="mt-0.5 block truncate text-[.8rem] font-light text-dark-soft">{{ $f->created_at->translatedFormat('j M Y') }} · {{ $f->order->nummer() }} · {{ (int) ($f->order->artikelen ?? 0) }} {{ (int) ($f->order->artikelen ?? 0) === 1 ? 'artikel' : 'artikelen' }}</span>
                </span>

                <span class="hidden text-right font-serif text-[1.05rem] font-semibold sm:block">€{{ number_format($f->order->total, 2, ',', '.') }}</span>

                <span class="flex items-center justify-end gap-1.5">
                    <a href="{{ route('admin.bestellingen.detail', $f->order) }}" class="grid h-10 w-10 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Naar bestelling" title="Naar bestelling">
                        <i class="fa-light fa-box-open text-[.9rem]"></i>
                    </a>
                    <a href="{{ route('admin.facturen.download', $f) }}" class="grid h-10 w-10 place-items-center rounded-full bg-primary text-white transition-colors hover:bg-primary-deep" aria-label="Download PDF" title="Download PDF">
                        <i class="fa-light fa-arrow-down-to-line text-[.9rem]"></i>
                    </a>
                </span>
            </div>
        @empty
            <p class="px-6 py-12 text-center font-light text-dark-soft">Nog geen facturen - die verschijnen hier automatisch zodra een bestelling betaald is.</p>
        @endforelse
    </div>
</div>

@endsection
