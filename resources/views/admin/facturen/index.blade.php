@extends('layouts.admin')

@section('title', 'Facturen - Beheer ' . config('app.name'))

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
        <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Facturen</h1>
        <p class="mt-2 max-w-[62ch] text-[.9rem] font-light text-dark-soft">Facturen worden automatisch aangemaakt zodra een bestelling betaald is. Een handmatige factuur krijgt het volgende nummer in dezelfde reeks.</p>
    </div>
    <a href="{{ route('admin.facturen.nieuw') }}" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-6 py-3 text-[.88rem] font-semibold text-white transition-colors hover:bg-primary-deep max-sm:w-full max-sm:justify-center">
        <i class="fa-light fa-plus"></i> Handmatige factuur
    </a>
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
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Facturen</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-file-invoice text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">{{ $facturen->count() }}</p>
        @if ($openstaand)
            <p class="mt-0.5 text-[.78rem] font-medium text-orange-600">{{ $openstaand }} nog te betalen</p>
        @endif
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
            @php
                $handmatig = $f->isHandmatig();
                $artikelen = $f->aantalArtikelen();
                $zoektekst = $handmatig
                    ? $f->number.' '.$f->name.' '.$f->company.' '.$f->email.' handmatig'
                    : $f->number.' '.$f->order->nummer().' '.$f->order->name.' '.$f->order->email;
            @endphp
            <div class="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-x-5 px-6 py-5 sm:grid-cols-[130px_minmax(0,1fr)_120px_auto] {{ session('nieuweFactuur') === $f->id ? 'bg-accent-soft/40' : '' }}"
                 x-show="zoek.trim() === '' || $el.dataset.zoek.includes(zoek.toLowerCase())"
                 data-zoek="{{ mb_strtolower($zoektekst) }}">

                <span class="hidden font-serif text-[1rem] font-semibold sm:block">{{ $f->number }}</span>

                <span class="min-w-0">
                    <span class="flex min-w-0 items-center gap-2">
                        <span class="truncate text-[.95rem] font-medium"><span class="sm:hidden">{{ $f->number }} · </span>{{ $f->klantNaam() }}</span>
                        @if ($handmatig && ! $f->paid)
                            <span class="shrink-0 rounded-full bg-orange-100 px-2.5 py-0.5 text-[.66rem] font-semibold tracking-[.06em] text-orange-700 uppercase">Nog te betalen</span>
                        @endif
                    </span>
                    <span class="mt-0.5 block truncate text-[.8rem] font-light text-dark-soft">
                        {{ $f->created_at->translatedFormat('j M Y') }} · {{ $handmatig ? 'Handmatig' : $f->order->nummer() }} · {{ $artikelen }} {{ $artikelen === 1 ? 'artikel' : 'artikelen' }}
                    </span>
                </span>

                <span class="hidden text-right font-serif text-[1.05rem] font-semibold sm:block">€{{ number_format($f->totaalBedrag(), 2, ',', '.') }}</span>

                <span class="flex items-center justify-end gap-1.5">
                    @if ($handmatig)
                        <form method="POST" action="{{ route('admin.facturen.betaald', $f) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="grid h-10 w-10 place-items-center rounded-full transition-colors hover:bg-cream-deep {{ $f->paid ? 'text-dark-soft hover:text-dark' : 'text-orange-600' }}"
                                    aria-label="{{ $f->paid ? 'Terugzetten naar nog te betalen' : 'Markeer als betaald' }}" title="{{ $f->paid ? 'Terugzetten naar nog te betalen' : 'Markeer als betaald' }}">
                                <i class="fa-light {{ $f->paid ? 'fa-circle-check' : 'fa-hourglass-half' }} text-[.9rem]"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.bestellingen.detail', $f->order) }}" class="grid h-10 w-10 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Naar bestelling" title="Naar bestelling">
                            <i class="fa-light fa-box-open text-[.9rem]"></i>
                        </a>
                    @endif
                    <a href="{{ route('admin.facturen.download', $f) }}" class="grid h-10 w-10 place-items-center rounded-full bg-primary text-white transition-colors hover:bg-primary-deep" aria-label="Download PDF" title="Download PDF">
                        <i class="fa-light fa-arrow-down-to-line text-[.9rem]"></i>
                    </a>
                </span>
            </div>
        @empty
            <p class="px-6 py-12 text-center font-light text-dark-soft">Nog geen facturen - die verschijnen hier automatisch zodra een bestelling betaald is, of maak een handmatige factuur.</p>
        @endforelse
    </div>
</div>

@endsection
