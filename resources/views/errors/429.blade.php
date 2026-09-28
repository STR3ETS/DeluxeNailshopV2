@extends('layouts.error')

@section('title', 'Even rustig aan - ' . config('app.name'))
@section('code', '429')
@section('icon', 'fa-gauge-high')
@section('kop')
    Even <em class="text-primary italic">rustig aan</em>
@endsection
@section('tekst', 'We kregen in korte tijd erg veel verzoeken van je binnen. Wacht een minuutje en probeer het dan opnieuw.')
