@php
    /*
    | Statuschip voor bestellingen. Verwacht $status (key) en $statussen
    | (key => label, uit BestellingController::STATUSSEN).
    */
    $statusStijlen = [
        'open'        => 'border border-dark/15 text-dark-soft',
        'betaald'     => 'bg-emerald-100 text-emerald-700',
        'verzonden'   => 'bg-sky-100 text-sky-700',
        'afgerond'    => 'bg-accent-soft text-primary-deep',
        'geannuleerd' => 'bg-red-100 text-red-700',
        'intern'      => 'bg-violet-100 text-violet-700',
    ];
@endphp
<span class="inline-block rounded-full px-3 py-1.5 text-[.68rem] font-semibold tracking-[.1em] uppercase {{ $statusStijlen[$status] ?? $statusStijlen['open'] }}">{{ $statussen[$status] ?? $status }}</span>
