{{-- Not found page (status 404, so never indexed). Unknown URLs under /en get the English version. --}}
@php
    if (request()->is('en', 'en/*')) {
        app()->setLocale('en');
    }
@endphp

@extends('layouts.app')

@section('title', __('site.not_found.title'))
@section('description', __('site.not_found.text'))

@section('content')
    <section class="mx-auto max-w-6xl px-4 pb-24 pt-16 md:pb-32 md:pt-24">
        <p class="font-display text-[clamp(6rem,20vw,12rem)] font-extrabold leading-none tracking-tighter text-blu" aria-hidden="true">404</p>
        <h1 class="mt-4 type-page">{{ __('site.not_found.h1') }}</h1>
        <p class="mt-6 max-w-xl text-lg text-ink-soft">{{ __('site.not_found.text') }}</p>
        <div class="mt-10 flex flex-wrap gap-3">
            <x-button :href="\App\Support\Pages::url('home')">{{ __('site.not_found.home') }}</x-button>
            <x-button :href="\App\Support\Pages::url('menu')" variant="secondary">{{ __('site.home.see_menu') }}</x-button>
        </div>
    </section>
@endsection
