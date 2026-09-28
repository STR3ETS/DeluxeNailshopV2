@extends('layouts.error')

@section('title', 'Dat lukte niet - ' . config('app.name'))
@section('code', $exception->getStatusCode())
@section('icon', 'fa-circle-exclamation')
@section('kop')
    Dat <em class="text-primary italic">lukte niet</em>
@endsection
@section('tekst', 'Deze pagina kon niet worden geladen. Controleer het adres of ga terug naar de homepage.')
