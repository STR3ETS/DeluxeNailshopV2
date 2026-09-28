@extends('layouts.error')

@section('title', 'Er ging iets mis - ' . config('app.name'))
@section('code', '500')
@section('icon', 'fa-screwdriver-wrench')
@section('kop')
    Oeps, er ging <em class="text-primary italic">iets mis</em>
@endsection
@section('tekst', 'Er is aan onze kant iets fout gegaan. We zijn op de hoogte en lossen het zo snel mogelijk op. Probeer het over een paar minuten opnieuw.')
