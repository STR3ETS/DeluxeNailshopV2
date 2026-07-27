@extends('layouts.admin')

@section('title', 'Bestelling ' . $order->nummer() . ' - Beheer ' . config('app.name'))

@php
    $subtotaal = (float) $order->total - (float) $order->shipping;
    $landen = ['NL' => 'Nederland', 'BE' => 'België'];
@endphp

@section('content')

<div class="load-reveal flex flex-wrap items-end justify-between gap-5">
    <div>
        <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Bestellingen</span>
        <div class="mt-2 flex flex-wrap items-center gap-4">
            <h1 class="font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Bestelling <em class="text-primary italic">{{ $order->nummer() }}</em></h1>
            @include('admin.bestellingen.status-chip', ['status' => $order->status, 'statussen' => $statussen])
        </div>
        <p class="mt-2 text-[.88rem] font-light text-dark-soft">Geplaatst op {{ $order->created_at->translatedFormat('j F Y \o\m H:i') }}</p>
    </div>
    <a href="{{ route('admin.bestellingen') }}" class="inline-flex items-center gap-2 text-[.88rem] font-semibold text-primary-deep transition-colors hover:text-primary">
        <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar bestellingen
    </a>
</div>

@if (session('status'))
    <p class="load-reveal mt-6 rounded-2xl border border-primary/20 bg-accent-soft/60 px-5 py-3.5 text-[.9rem] text-dark">
        <i class="fa-light fa-circle-check mr-1.5 text-primary-deep"></i>{{ session('status') }}
    </p>
@endif

<div class="load-reveal mt-7 grid items-start gap-6 lg:grid-cols-[1.25fr_1fr]">

    {{-- Artikelen + totalen --}}
    <div class="rounded-card border border-primary/15 bg-offwhite p-6 sm:p-7">
        <h2 class="font-serif text-[1.2rem] font-medium">Artikelen</h2>

        <div class="mt-2 divide-y divide-primary/10">
            @foreach ($order->items as $regel)
                @php $product = $producten[$regel->product_slug] ?? null; @endphp
                <div class="flex items-center gap-4 py-4">
                    <span class="grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-xl"
                          @if ($product) style="background:linear-gradient(160deg,{{ $product->bg_from }},{{ $product->bg_to }})" @else style="background:var(--color-cream-deep)" @endif>
                        @if ($product?->image)
                            <img src="{{ asset($product->image) }}" alt="" class="max-h-11 w-auto object-contain">
                        @else
                            <i class="fa-light fa-bottle-droplet text-[1.1rem] text-primary"></i>
                        @endif
                    </span>
                    <div class="min-w-0 flex-1">
                        @if ($product)
                            <a href="{{ route('admin.producten.bewerken', $product) }}" class="block truncate text-[.95rem] font-medium transition-colors hover:text-primary-deep">{{ $regel->name }}</a>
                        @else
                            <p class="truncate text-[.95rem] font-medium">{{ $regel->name }}</p>
                        @endif
                        <p class="mt-0.5 text-[.8rem] text-dark-soft">{{ $regel->qty }} × €{{ number_format($regel->price, 2, ',', '.') }}</p>
                    </div>
                    <span class="font-serif text-[1rem] font-semibold">€{{ number_format($regel->price * $regel->qty, 2, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-primary/15 pt-4 text-[.9rem]">
            <div class="flex items-center justify-between">
                <span class="text-dark-soft">Subtotaal</span>
                <span class="font-medium">€{{ number_format($subtotaal, 2, ',', '.') }}</span>
            </div>
            <div class="mt-2 flex items-center justify-between">
                <span class="text-dark-soft">Verzendkosten</span>
                <span class="font-medium">{{ (float) $order->shipping === 0.0 ? 'Gratis' : '€'.number_format($order->shipping, 2, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center justify-between border-t border-primary/15 pt-4">
                <span class="font-medium">Totaal</span>
                <b class="font-serif text-[1.3rem]">€{{ number_format($order->total, 2, ',', '.') }}</b>
            </div>
        </div>
    </div>

    {{-- Klant, adres, betaling, status --}}
    <div class="flex flex-col gap-6">

        <div class="rounded-card border border-primary/15 bg-offwhite p-6">
            <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-user text-[.95rem] text-primary-deep"></i> Klant</h2>
            <div class="mt-3 flex flex-col gap-1.5 text-[.9rem] leading-[1.7]">
                <p class="font-medium">{{ $order->name }}</p>
                <a href="mailto:{{ $order->email }}" class="font-light text-dark-soft transition-colors hover:text-primary-deep">{{ $order->email }}</a>
                @if ($order->phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $order->phone) }}" class="font-light text-dark-soft transition-colors hover:text-primary-deep">{{ $order->phone }}</a>
                @endif
                @if ($order->user_id)
                    <p class="mt-1 text-[.78rem] font-light text-dark-soft"><i class="fa-light fa-circle-user mr-1"></i>Besteld met klantaccount</p>
                @else
                    <p class="mt-1 text-[.78rem] font-light text-dark-soft"><i class="fa-light fa-user-slash mr-1"></i>Besteld als gast</p>
                @endif
            </div>
        </div>

        <div class="rounded-card border border-primary/15 bg-offwhite p-6">
            <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-truck-fast text-[.95rem] text-primary-deep"></i> Bezorgadres</h2>
            <p class="mt-3 text-[.9rem] leading-[1.7] font-light text-dark-soft">{{ $order->address }}<br>{{ $order->postcode }} {{ $order->city }}<br>{{ $landen[$order->country] ?? $order->country }}</p>
        </div>

        @if ($order->note)
            <div class="rounded-card border border-primary/15 bg-offwhite p-6">
                <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-message-lines text-[.95rem] text-primary-deep"></i> Opmerking van de klant</h2>
                <p class="mt-3 text-[.9rem] leading-[1.7] font-light text-dark-soft">{{ $order->note }}</p>
            </div>
        @endif

        <div class="rounded-card border border-primary/15 bg-offwhite p-6">
            <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-credit-card text-[.95rem] text-primary-deep"></i> Betaling</h2>
            <div class="mt-3 flex flex-col gap-1.5 text-[.9rem] leading-[1.7] font-light text-dark-soft">
                @if ($order->mollie_payment_id)
                    <p>Betaald via <b class="font-medium text-dark">Mollie</b></p>
                    <p class="text-[.8rem]">Betalings-ID: <code class="rounded bg-cream-deep px-1.5 py-0.5 text-[.75rem]">{{ $order->mollie_payment_id }}</code></p>
                @else
                    <p>Geen online betaling gekoppeld.</p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('admin.bestellingen.status', $order) }}" class="rounded-card border border-primary/15 bg-offwhite p-6">
            @csrf
            @method('PATCH')
            <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-arrows-rotate text-[.95rem] text-primary-deep"></i> Status wijzigen</h2>
            <div class="mt-4 flex items-center gap-3">
                <select name="status" class="w-full cursor-pointer rounded-full border border-primary/20 bg-offwhite px-5 py-3 text-[.9rem] outline-none transition-colors focus:border-primary">
                    @foreach ($statussen as $statusKey => $statusLabel)
                        <option value="{{ $statusKey }}" @selected($order->status === $statusKey)>{{ $statusLabel }}</option>
                    @endforeach
                </select>
                <button type="submit" class="shrink-0 rounded-full bg-primary px-6 py-3 text-[.88rem] font-semibold text-white transition-colors hover:bg-primary-deep">Opslaan</button>
            </div>
            <p class="mt-3 text-[.78rem] leading-[1.6] font-light text-dark-soft">Bij "Geannuleerd" gaat de voorraad automatisch terug naar de producten; maak je een annulering ongedaan, dan wordt de voorraad opnieuw gereserveerd.</p>
            @error('status')<p class="mt-2 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror
        </form>
    </div>
</div>

@endsection
