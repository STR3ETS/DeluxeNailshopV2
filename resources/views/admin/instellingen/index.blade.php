@extends('layouts.admin')

@section('title', 'Instellingen - Beheer ' . config('app.name'))

@php
    $inputKlassen = 'w-full rounded-full border border-primary/20 bg-offwhite px-5 py-3 text-[.92rem] outline-none transition-colors placeholder:text-dark-soft/50 focus:border-primary';
    $labelKlassen = 'mb-2 block text-[.72rem] font-semibold tracking-[.16em] text-dark-soft uppercase';
    $foutKlassen  = 'mt-2 pl-4 text-[.8rem] font-medium text-red-600';
@endphp

@section('content')

<div class="load-reveal">
    <span class="text-[.72rem] font-semibold tracking-[.22em] text-primary-deep uppercase">Beheerpaneel</span>
    <h1 class="mt-2 font-serif text-[clamp(1.9rem,3.4vw,2.6rem)] leading-[1.15] font-normal">Instellingen</h1>
</div>

@if (session('status'))
    <p class="load-reveal mt-6 rounded-2xl border border-primary/20 bg-accent-soft/60 px-5 py-3.5 text-[.9rem] text-dark">
        <i class="fa-light fa-circle-check mr-1.5 text-primary-deep"></i>{{ session('status') }}
    </p>
@endif

{{-- Kortingscodes --}}
<div class="load-reveal mt-7 grid items-start gap-6 lg:grid-cols-[420px_minmax(0,1fr)]">

    {{-- Nieuwe code --}}
    <form method="POST" action="{{ route('admin.instellingen.kortingscodes.opslaan') }}" class="rounded-card border border-primary/15 bg-offwhite p-6 sm:p-7">
        @csrf
        <h2 class="flex items-center gap-2.5 font-serif text-[1.2rem] font-medium"><i class="fa-light fa-tag text-[.95rem] text-primary-deep"></i> Nieuwe kortingscode</h2>

        <div class="mt-5 flex flex-col gap-5">
            <label class="block">
                <span class="{{ $labelKlassen }}">Code</span>
                <input type="text" name="code" value="{{ old('code') }}" required placeholder="WELKOM10" class="{{ $inputKlassen }} tracking-[.06em] uppercase placeholder:normal-case placeholder:tracking-normal">
                @error('code')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
            </label>

            <div class="grid grid-cols-2 gap-4" x-data="{ type: @js(old('type', 'procent')) }">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Soort</span>
                    <select name="type" x-model="type" class="{{ $inputKlassen }} cursor-pointer">
                        <option value="procent">Percentage (%)</option>
                        <option value="bedrag">Vast bedrag (€)</option>
                    </select>
                    @error('type')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}" x-text="type === 'procent' ? 'Percentage' : 'Bedrag'">Percentage</span>
                    <input type="text" name="waarde" value="{{ old('waarde') }}" required :placeholder="type === 'procent' ? '10' : '5.00'" class="{{ $inputKlassen }}">
                    @error('waarde')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <label class="block">
                    <span class="{{ $labelKlassen }}">Min. bedrag <span class="font-normal normal-case">(optioneel)</span></span>
                    <input type="text" name="min_bedrag" value="{{ old('min_bedrag') }}" placeholder="50.00" class="{{ $inputKlassen }}">
                    @error('min_bedrag')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $labelKlassen }}">Geldig t/m <span class="font-normal normal-case">(optioneel)</span></span>
                    <input type="date" name="verloopt_op" value="{{ old('verloopt_op') }}" class="{{ $inputKlassen }}">
                    @error('verloopt_op')<p class="{{ $foutKlassen }}">{{ $message }}</p>@enderror
                </label>
            </div>

            <button type="submit" class="mt-1 inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-6 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep">
                <i class="fa-light fa-plus"></i> Code aanmaken
            </button>
        </div>
    </form>

    {{-- Bestaande codes --}}
    <div class="rounded-card border border-primary/15 bg-offwhite">
        <div class="border-b border-primary/10 px-6 py-5">
            <h2 class="font-serif text-[1.2rem] font-medium">Kortingscodes</h2>
            <p class="mt-1 text-[.82rem] font-light text-dark-soft">Klanten vullen de code in bij het afrekenen. Zet een code uit om hem tijdelijk te blokkeren.</p>
        </div>
        <div class="divide-y divide-primary/10">
            @forelse ($codes as $code)
                <div class="flex flex-wrap items-center gap-x-5 gap-y-3 px-6 py-4.5">
                    <span class="rounded-lg bg-cream-deep px-3 py-1.5 font-mono text-[.82rem] font-semibold tracking-[.06em] {{ $code->actief ? '' : 'opacity-50 line-through' }}">{{ $code->code }}</span>

                    <span class="min-w-0 flex-1 text-[.85rem] font-light text-dark-soft">
                        {{ $code->omschrijving() }}@if ($code->min_bedrag) · vanaf €{{ number_format((float) $code->min_bedrag, 2, ',', '.') }}@endif @if ($code->verloopt_op) · t/m {{ $code->verloopt_op->translatedFormat('j M Y') }}@endif
                        <span class="ml-1 text-[.78rem]">· {{ $code->gebruikt }}× gebruikt</span>
                    </span>

                    <span class="flex items-center gap-1.5">
                        <form method="POST" action="{{ route('admin.instellingen.kortingscodes.toggle', $code) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-full border px-4 py-2 text-[.75rem] font-semibold transition-colors {{ $code->actief ? 'border-primary/25 text-primary-deep hover:border-primary' : 'border-dark/15 text-dark-soft hover:border-dark/40' }}">
                                {{ $code->actief ? 'Actief' : 'Uit' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.instellingen.kortingscodes.verwijderen', $code) }}" x-data="{ bevestig: false }" @click.outside="bevestig = false">
                            @csrf
                            @method('DELETE')
                            <button type="button" x-show="!bevestig" @click="bevestig = true" class="grid h-9 w-9 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Verwijderen" title="Verwijderen">
                                <i class="fa-light fa-trash-can text-[.85rem]"></i>
                            </button>
                            <span x-cloak x-show="bevestig" class="flex items-center gap-1">
                                <button type="submit" class="rounded-full bg-red-600 px-3.5 py-1.5 text-[.72rem] font-semibold text-white transition-colors hover:bg-red-700">Zeker?</button>
                                <button type="button" @click="bevestig = false" class="grid h-8 w-8 place-items-center rounded-full text-dark-soft transition-colors hover:bg-cream-deep hover:text-dark" aria-label="Annuleren">
                                    <i class="fa-light fa-xmark text-[.85rem]"></i>
                                </button>
                            </span>
                        </form>
                    </span>
                </div>
            @empty
                <p class="px-6 py-10 text-center font-light text-dark-soft">Nog geen kortingscodes - maak er links een aan.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection
