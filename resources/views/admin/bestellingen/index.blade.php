@extends('layouts.admin')

@section('title', 'Bestellingen - Beheer ' . config('app.name'))

@section('content')

<div class="load-reveal">
    <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Bestellingen</h1>
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
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Bestellingen</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-box-open text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">{{ $bestellingen->count() }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Klaar om te verzenden</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-truck-fast text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold text-primary-deep">{{ $teVerzenden }}</p>
    </div>
    <div class="rounded-card border border-primary/15 bg-offwhite p-5">
        <div class="flex items-center justify-between gap-3">
            <span class="text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Omzet 30 dagen</span>
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-accent-soft text-primary-deep"><i class="fa-light fa-euro-sign text-[.85rem]"></i></span>
        </div>
        <p class="mt-1 font-serif text-[1.7rem] font-semibold">€ {{ number_format($omzet30, 2, ',', '.') }}</p>
    </div>
</div>

{{-- Zoeken + statusfilter + lijst --}}
<div class="load-reveal mt-7" x-data="{ zoek: '', st: '' }">
    <div class="flex flex-wrap items-center gap-4">
        <div class="relative w-full max-w-[380px]">
            <i class="fa-light fa-magnifying-glass pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
            <input type="search" x-model="zoek" placeholder="Zoek op nummer, naam of e-mail…" aria-label="Zoek bestellingen"
                   class="w-full rounded-full border border-primary/20 bg-offwhite py-3 pr-6 pl-12 text-[.9rem] outline-none transition-colors placeholder:text-dark-soft/60 focus:border-primary">
        </div>
        <div class="flex flex-wrap items-center gap-1.5">
            <button type="button" @click="st = ''"
                    class="rounded-full border px-4 py-2 text-[.8rem] font-semibold transition-colors"
                    :class="st === '' ? 'border-primary bg-primary text-white' : 'border-dark/15 text-dark-soft hover:border-dark/40'">Alle</button>
            @foreach ($statussen as $statusKey => $statusLabel)
                <button type="button" @click="st = st === '{{ $statusKey }}' ? '' : '{{ $statusKey }}'"
                        class="rounded-full border px-4 py-2 text-[.8rem] font-semibold transition-colors"
                        :class="st === '{{ $statusKey }}' ? 'border-primary bg-primary text-white' : 'border-dark/15 text-dark-soft hover:border-dark/40'">{{ $statusLabel }}</button>
            @endforeach
        </div>
    </div>

    <div class="mt-6 divide-y divide-primary/10 overflow-hidden rounded-card border border-primary/15 bg-offwhite">
        @forelse ($bestellingen as $b)
            <a href="{{ route('admin.bestellingen.detail', $b) }}"
               class="grid grid-cols-[minmax(0,1fr)_auto_24px] items-center gap-x-5 px-6 py-5 transition-colors hover:bg-cream-deep/50 sm:grid-cols-[110px_minmax(0,1fr)_130px_auto_24px] lg:grid-cols-[110px_minmax(0,1fr)_120px_130px_110px_24px]"
               x-show="(zoek.trim() === '' || $el.dataset.zoek.includes(zoek.toLowerCase())) && (st === '' || $el.dataset.status === st)"
               data-zoek="{{ mb_strtolower($b->nummer().' '.$b->name.' '.$b->email) }}"
               data-status="{{ $b->status }}">

                <span class="hidden font-serif text-[1rem] font-semibold sm:block">{{ $b->nummer() }}</span>

                <span class="min-w-0">
                    <span class="block truncate text-[.95rem] font-medium"><span class="sm:hidden">{{ $b->nummer() }} · </span>{{ $b->name }}</span>
                    <span class="mt-0.5 block truncate text-[.8rem] font-light text-dark-soft">{{ $b->created_at->translatedFormat('j M Y · H:i') }} · {{ $b->email }}</span>
                </span>

                <span class="hidden text-[.85rem] text-dark-soft lg:block">{{ (int) ($b->artikelen ?? 0) }} {{ (int) ($b->artikelen ?? 0) === 1 ? 'artikel' : 'artikelen' }}</span>

                <span class="hidden sm:block">
                    @include('admin.bestellingen.status-chip', ['status' => $b->status, 'statussen' => $statussen])
                </span>

                <span class="text-right font-serif text-[1.05rem] font-semibold">€{{ number_format($b->total, 2, ',', '.') }}</span>

                <i class="fa-light fa-angle-right justify-self-end text-[.85rem] text-dark-soft"></i>
            </a>
        @empty
            <p class="px-6 py-12 text-center font-light text-dark-soft">Nog geen bestellingen - zodra er iets wordt afgerekend, zie je het hier.</p>
        @endforelse
    </div>
</div>

@endsection
