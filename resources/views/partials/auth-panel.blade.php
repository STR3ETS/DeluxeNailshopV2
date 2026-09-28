@php
    // Donker merkpaneel voor de klantenportaal-pagina's (inloggen/registreren)
    $benefits = [
        ['icon' => 'fa-box-open', 'text' => 'Bekijk je bestellingen en facturen op één plek'],
        ['icon' => 'fa-bolt',     'text' => 'Sneller afrekenen met opgeslagen gegevens'],
        ['icon' => 'fa-heart',    'text' => 'Bewaar je favoriete producten en kleuren'],
        ['icon' => 'fa-tag',      'text' => 'Als eerste toegang tot exclusieve acties'],
    ];
@endphp

{{-- Op mobiel compact (zonder voordelenlijst), zodat het formulier eronder snel in beeld is --}}
<div class="relative overflow-hidden bg-dark p-7 text-cream sm:p-12">
    <div class="pointer-events-none absolute -bottom-24 -left-16 h-[240px] w-[240px] rounded-full bg-primary/15"></div>

    <img src="{{ asset('logo/deluxenailshop_transp_goud_v1.png') }}" alt="{{ config('app.name') }}" class="relative h-11 w-auto sm:h-14">
    <h2 class="relative mt-5 font-serif text-[clamp(1.5rem,2.6vw,1.9rem)] leading-[1.2] font-normal sm:mt-8">Jouw <em class="text-gold italic">klantenportaal</em></h2>
    <p class="relative mt-2 max-w-[36ch] text-[.9rem] leading-[1.65] font-light opacity-80 sm:mt-3 sm:text-[.92rem] sm:leading-[1.7]">Alles rondom je bestellingen op één plek - voor nagelstylistes én thuis-artists.</p>

    <ul class="relative mt-8 hidden flex-col gap-4 sm:flex">
        @foreach ($benefits as $benefit)
            <li class="flex items-center gap-3.5">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-gold/15 text-gold"><i class="fa-light {{ $benefit['icon'] }} text-[.9rem]"></i></span>
                <span class="text-[.88rem] font-light opacity-90">{{ $benefit['text'] }}</span>
            </li>
        @endforeach
    </ul>
</div>
