@extends('layouts.shop')

@section('title', 'Bedankt voor je bestelling - ' . config('app.name'))

@php
    $subtotaal = (float) $order->total - (float) $order->shipping + (float) $order->discount;
    $landen = ['NL' => 'Nederland', 'BE' => 'België'];
    $afhalen = $order->levering === 'afhalen';
@endphp

@section('content')

{{-- x-init leegt de winkelwagen: de bestelling is geplaatst --}}
<section class="px-6 pt-12 pb-16" x-data x-init="$store.cart.clear()">
    <div class="mx-auto max-w-[720px]">

        <div class="load-reveal text-center">
            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light {{ $order->status === 'betaald' ? 'fa-circle-check' : 'fa-hourglass-half' }} text-[1.7rem]"></i></span>
            <h1 class="mt-6 font-serif text-[clamp(2rem,3.6vw,2.8rem)] leading-[1.12] font-normal">Bedankt voor je <em class="text-primary italic">bestelling</em>!</h1>
            @if ($order->status === 'betaald')
                <p class="mx-auto mt-3 max-w-[46ch] leading-[1.7] font-light text-dark-soft">Je betaling is ontvangen en we gaan direct voor je aan de slag. Je ontvangt de bevestiging en verzendupdates op <b class="font-medium text-dark">{{ $order->email }}</b>.</p>
            @else
                <p class="mx-auto mt-3 max-w-[46ch] leading-[1.7] font-light text-dark-soft">Je betaling wordt nog verwerkt door de bank. Zodra deze binnen is, ontvang je de bevestiging op <b class="font-medium text-dark">{{ $order->email }}</b>.</p>
            @endif
            <span class="mt-5 inline-block rounded-full border border-primary/20 bg-offwhite px-5 py-2 text-[.85rem] font-semibold tracking-[.06em]">Bestelnummer: {{ $order->nummer() }}</span>
        </div>

        {{-- Overzicht --}}
        <div class="load-reveal mt-10 rounded-card border border-primary/15 bg-offwhite p-6 sm:p-8">
            <h2 class="font-serif text-[1.25rem] font-medium">Jouw <em class="text-primary italic">bestelling</em></h2>

            <div class="mt-2 divide-y divide-primary/10">
                @foreach ($order->items as $regel)
                    <div class="flex items-center justify-between gap-4 py-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-[.92rem] font-medium">{{ $regel->name }}</p>
                            <p class="mt-0.5 text-[.78rem] text-dark-soft">{{ $regel->qty }} × €{{ number_format($regel->price, 2, ',', '.') }}</p>
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
                @if ((float) $order->discount > 0)
                    <div class="mt-2 flex items-center justify-between text-primary-deep">
                        <span>Korting ({{ $order->discount_code }})</span>
                        <span class="font-medium">- €{{ number_format($order->discount, 2, ',', '.') }}</span>
                    </div>
                @endif
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-dark-soft">{{ $afhalen ? 'Afhalen' : 'Verzendkosten' }}</span>
                    <span class="font-medium">{{ (float) $order->shipping === 0.0 ? 'Gratis' : '€'.number_format($order->shipping, 2, ',', '.') }}</span>
                </div>
                <div class="mt-4 flex items-center justify-between border-t border-primary/15 pt-4">
                    <span class="font-medium">Totaal</span>
                    <b class="font-serif text-[1.35rem]">€{{ number_format($order->total, 2, ',', '.') }}</b>
                </div>
            </div>
        </div>

        {{-- Bezorging of afhalen --}}
        <div class="load-reveal mt-5 flex items-start gap-4 rounded-card border border-primary/15 bg-offwhite p-6">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light {{ $afhalen ? 'fa-shop' : 'fa-truck-fast' }} text-[1rem]"></i></span>
            <div>
                @if ($afhalen)
                    <p class="text-[.95rem] font-medium">Afhalen</p>
                    <p class="mt-1 text-[.9rem] leading-[1.7] font-light text-dark-soft">Je ontvangt een e-mail op <b class="font-medium text-dark">{{ $order->email }}</b> zodra je bestelling klaarligt om af te halen.</p>
                @else
                    <p class="text-[.95rem] font-medium">Bezorgadres</p>
                    <p class="mt-1 text-[.9rem] leading-[1.7] font-light text-dark-soft">{{ $order->name }}<br>{{ $order->address }}<br>{{ $order->postcode }} {{ $order->city }}, {{ $landen[$order->country] ?? $order->country }}</p>
                @endif
            </div>
        </div>

        <div class="load-reveal mt-8 text-center">
            <a href="{{ route('producten') }}" class="inline-flex items-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">
                Verder winkelen <i class="fa-light fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection
