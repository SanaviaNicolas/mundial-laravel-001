{{-- Legal pages (privacy, cookie): texts in lang/{locale}/site.php under legal.{page}, with the business data from config/site.php. --}}
@php
    $pagina = \App\Support\Pages::current();
    $mancante = __('site.legal.missing');
    $dati = [
        ':company' => config('site.company') ?? $mancante,
        ':vat' => config('site.vat') ?? $mancante,
        ':email' => config('site.email') ?? $mancante,
        ':address' => config('site.address'),
        ':phone' => config('site.phone'),
        ':session' => config('session.cookie'),
        ':lifetime' => \Carbon\CarbonInterval::minutes(config('session.lifetime'))->cascade()->forHumans(),
    ];
@endphp

@extends('layouts.app')

@section('title', __("site.legal.$pagina.title"))
@section('description', __("site.legal.$pagina.description"))

@section('content')
    <article class="mx-auto max-w-3xl px-4 pb-24 pt-16 md:pb-32 md:pt-24">
        <h1 class="type-page">{{ __("site.legal.$pagina.h1") }}</h1>
        <p class="mt-6 text-sm text-muted">{{ __('site.legal.updated') }}</p>
        @foreach (__("site.legal.$pagina.sections") as [$titolo, $paragrafi])
            <section class="mt-12">
                <h2 class="text-2xl md:text-3xl">{{ $titolo }}</h2>
                @foreach ($paragrafi as $paragrafo)
                    <p class="mt-4 text-lg leading-relaxed text-ink-soft">{{ strtr($paragrafo, $dati) }}</p>
                @endforeach
            </section>
        @endforeach
    </article>
@endsection
