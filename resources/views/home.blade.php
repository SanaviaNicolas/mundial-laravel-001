@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante. Menù, orari e contatti a breve su questo sito.')

@section('content')
    <h1 class="text-3xl font-semibold">{{ config('app.name') }}</h1>
    <p class="mt-4">Pizzeria e ristorante. Il sito è in costruzione: presto qui trovi menù, orari e contatti.</p>
@endsection
