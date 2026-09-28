@extends('layouts.error')

@section('title', 'Geen toegang - ' . config('app.name'))
@section('code', '403')
@section('icon', 'fa-lock')
@section('kop')
    Hier heb je <em class="text-primary italic">geen toegang</em>
@endsection
@section('tekst', 'Deze pagina is alleen bereikbaar met de juiste rechten. Denk je dat dit niet klopt? Log opnieuw in of neem contact met ons op.')
