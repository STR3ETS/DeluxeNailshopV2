@extends('layouts.error')

@section('title', 'Even onderhoud - ' . config('app.name'))
@section('code', '503')
@section('icon', 'fa-sparkles')
@section('kop')
    We zijn even aan het <em class="text-primary italic">opfrissen</em>
@endsection
@section('tekst', 'De webshop is kort offline voor onderhoud. Kom over een paar minuten terug, dan staat alles weer voor je klaar.')
@section('acties')
    <a href="https://wa.me/{{ config('shop.contact.whatsapp') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep max-sm:w-full">
        <i class="fa-brands fa-whatsapp text-[1.05rem]"></i> Vraag? Stuur een WhatsApp
    </a>
    <a href="mailto:{{ config('shop.bedrijf.email') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-3.5 text-[.9rem] font-semibold transition-colors hover:border-dark max-sm:w-full">
        <i class="fa-light fa-envelope"></i> Stuur een e-mail
    </a>
@endsection
