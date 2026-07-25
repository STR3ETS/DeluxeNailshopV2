@extends('layouts.auth')

@section('title', 'Wachtwoord vergeten - ' . config('app.name'))

@php
    /*
    |--------------------------------------------------------------------------
    | Klantenportaal - wachtwoord vergeten (alleen styling)
    |--------------------------------------------------------------------------
    | Nog geen echte herstelmail: het formulier wisselt bij versturen naar
    | een bevestigingsweergave. Koppel later Laravel's password-reset aan
    | de action van dit formulier.
    */
@endphp

@section('content')

<section class="w-full px-6 py-16">
    <div class="mx-auto max-w-[520px]">
        <div class="load-reveal rounded-[calc(var(--radius)+10px)] bg-offwhite p-9 text-center shadow-card sm:p-12" x-data="{ sent: false }">

            <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-accent-soft text-primary-deep"><i class="fa-light fa-key text-[1.3rem]"></i></span>

            <template x-if="!sent">
                <div>
                    <h1 class="mt-6 font-serif text-[clamp(1.7rem,3vw,2.2rem)] leading-[1.15] font-normal">Wachtwoord <em class="text-primary italic">vergeten</em>?</h1>
                    <p class="mx-auto mt-3 max-w-[38ch] text-[.92rem] leading-[1.7] font-light text-dark-soft">Geen zorgen - vul je e-mailadres in en we sturen je een link om een nieuw wachtwoord in te stellen.</p>

                    <form @submit.prevent="sent = true" class="mt-8 flex flex-col gap-5">
                        <label class="block text-left">
                            <span class="mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase">E-mailadres</span>
                            <div class="relative">
                                <i class="fa-light fa-envelope pointer-events-none absolute top-1/2 left-5 -translate-y-1/2 text-[.9rem] text-dark-soft"></i>
                                <input type="email" required placeholder="jouw@email.nl" autocomplete="email"
                                       class="w-full rounded-full border border-primary/20 bg-white py-3.5 pr-6 pl-12 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary">
                            </div>
                        </label>

                        <button type="submit" class="flex w-full items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-4 text-[.95rem] font-semibold tracking-[.02em] text-white shadow-[0_14px_30px_-12px_color-mix(in_srgb,var(--color-primary)_70%,transparent)] transition-colors hover:bg-primary-deep">
                            Verstuur herstellink <i class="fa-light fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </template>

            <template x-if="sent">
                <div>
                    <h1 class="mt-6 font-serif text-[clamp(1.7rem,3vw,2.2rem)] leading-[1.15] font-normal">Check je <em class="text-primary italic">inbox</em></h1>
                    <p class="mx-auto mt-3 max-w-[38ch] text-[.92rem] leading-[1.7] font-light text-dark-soft">Als je e-mailadres bij ons bekend is, ontvang je binnen enkele minuten een link om je wachtwoord opnieuw in te stellen. Check ook je spamfolder.</p>
                    <p class="mt-6 rounded-2xl border border-primary/20 bg-accent-soft/60 px-5 py-3.5 text-[.85rem] leading-[1.6] text-dark">
                        <i class="fa-light fa-sparkles mr-1.5 text-primary-deep"></i>Het klantenportaal is bijna klaar - wachtwoordherstel werkt binnenkort!
                    </p>
                </div>
            </template>

            <a href="{{ url('/login') }}" class="mt-8 inline-flex items-center gap-2 text-[.88rem] font-medium text-primary-deep transition-colors hover:text-primary">
                <i class="fa-light fa-arrow-left text-[.8rem]"></i> Terug naar inloggen
            </a>
        </div>
    </div>
</section>

@endsection
