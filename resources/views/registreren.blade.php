@extends('layouts.auth')

@section('title', 'Account aanmaken - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Klantenportaal - registreren (alleen styling)
    |--------------------------------------------------------------------------
    | Nog geen echte registratie: het formulier toont bij versturen een
    | nette melding. Koppel later Laravel's auth (Fortify/Breeze) aan de
    | action van dit formulier.
    */
@endphp

@section('content')

<section class="w-full px-6 py-16">
    <div class="mx-auto max-w-[980px]">
        <div class="load-reveal grid overflow-hidden rounded-[calc(var(--radius)+10px)] shadow-card lg:grid-cols-[.92fr_1.08fr]">

            {{-- Merkpaneel --}}
            @include('partials.auth-panel')

            {{-- Formulier --}}
            <div class="bg-offwhite p-9 sm:p-12" x-data="{ show: false }">
                <h1 class="font-serif text-[clamp(1.7rem,3vw,2.2rem)] leading-[1.15] font-normal">Maak een <em class="text-primary italic">account</em></h1>
                <p class="mt-2.5 text-[.92rem] leading-[1.65] font-light text-dark-soft">Binnen een minuutje geregeld - en helemaal gratis.</p>

                <form method="POST" action="{{ route('registreren.attempt') }}" class="mt-8 flex flex-col gap-5">
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Voornaam</span>
                            <input type="text" name="voornaam" value="{{ old('voornaam') }}" required placeholder="Sophie" autocomplete="given-name"
                                   class="w-full rounded-full border {{ $errors->has('voornaam') ? 'border-red-400' : 'border-primary/20' }} bg-white px-6 py-3.5 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                            @error('voornaam')<p class="mt-2 pl-5 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Achternaam</span>
                            <input type="text" name="achternaam" value="{{ old('achternaam') }}" required placeholder="Jansen" autocomplete="family-name"
                                   class="w-full rounded-full border {{ $errors->has('achternaam') ? 'border-red-400' : 'border-primary/20' }} bg-white px-6 py-3.5 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                            @error('achternaam')<p class="mt-2 pl-5 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror
                        </label>
                    </div>

                    <label class="block">
                        <span class="mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">E-mailadres</span>
                        <div class="relative">
                            <i class="fa-light fa-envelope pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="jouw@email.nl" autocomplete="email"
                                   class="w-full rounded-full border {{ $errors->has('email') ? 'border-red-400' : 'border-primary/20' }} bg-white py-3.5 pr-6 pl-12 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                        </div>
                        @error('email')<p class="mt-2 pl-5 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">Wachtwoord</span>
                        <div class="relative">
                            <i class="fa-light fa-lock-keyhole pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
                            <input :type="show ? 'text' : 'password'" name="password" required placeholder="Minimaal 8 tekens" minlength="8" autocomplete="new-password"
                                   class="w-full rounded-full border {{ $errors->has('password') ? 'border-red-400' : 'border-primary/20' }} bg-white py-3.5 pr-14 pl-12 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                            <button type="button" @click="show = !show" class="absolute top-1/2 right-2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" :aria-label="show ? 'Verberg wachtwoord' : 'Toon wachtwoord'">
                                <i class="fa-light text-[.9rem]" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')<p class="mt-2 pl-5 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 text-[.85rem] leading-[1.6] text-dark-soft">
                        <input type="checkbox" name="voorwaarden" required class="mt-0.5 h-4 w-4 shrink-0 accent-primary">
                        <span>Ik ga akkoord met de <a href="{{ url('/algemene-voorwaarden') }}" target="_blank" class="font-medium text-primary-deep transition-colors hover:text-primary">algemene voorwaarden</a> en het <a href="{{ url('/privacybeleid') }}" target="_blank" class="font-medium text-primary-deep transition-colors hover:text-primary">privacybeleid</a>.</span>
                    </label>
                    @error('voorwaarden')<p class="pl-7 text-[.8rem] font-medium text-red-600">{{ $message }}</p>@enderror

                    <button type="submit" class="mt-1 flex w-full items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.95rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep">
                        Account aanmaken <i class="fa-light fa-arrow-right"></i>
                    </button>
                </form>

                <div class="mt-8 flex items-center gap-4 text-[.78rem] tracking-[.14em] text-dark-soft uppercase before:h-px before:flex-1 before:bg-primary/15 before:content-[''] after:h-px after:flex-1 after:bg-primary/15 after:content-['']">of</div>

                <p class="mt-6 text-center text-[.9rem] font-light text-dark-soft">
                    Al een account? <a href="{{ url('/login') }}" class="font-semibold text-primary-deep transition-colors hover:text-primary">Log hier in</a>
                </p>
            </div>
        </div>
    </div>
</section>

@endsection
