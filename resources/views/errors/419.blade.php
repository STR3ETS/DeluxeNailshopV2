@extends('layouts.error')

@section('title', 'Pagina verlopen - ' . config('app.name'))
@section('code', '419')
@section('icon', 'fa-hourglass-end')
@section('kop')
    Deze pagina is <em class="text-primary italic">verlopen</em>
@endsection
@section('tekst', 'Je sessie is verlopen, bijvoorbeeld omdat de pagina lang openstond. Ga terug, vernieuw de pagina en probeer het nog een keer.')
@section('acties')
    <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center gap-2.5 rounded-full bg-primary px-7 py-3.5 text-[.9rem] font-semibold text-white transition-colors hover:bg-primary-deep max-sm:w-full">
        <i class="fa-light fa-arrow-rotate-right"></i> Terug en opnieuw proberen
    </a>
    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2.5 rounded-full border-[1.5px] border-dark/25 px-7 py-3.5 text-[.9rem] font-semibold transition-colors hover:border-dark max-sm:w-full">
        Naar de homepage
    </a>
@endsection
