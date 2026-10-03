@extends('layouts.app')

@section('title', __('site.menu.title'))
@section('description', __('site.menu.description'))
@section('header-overlay', true)

@php
    $evidenza = collect($menu)->flatMap(fn ($categoria) => $categoria['voci'])->filter(fn ($voce) => isset($voce['badge']));
@endphp

@section('content')
    <x-hero name="ingredienti" :alt="__('site.menu.hero_alt')" height="min-h-[75svh]" position="object-[60%_50%]">
        <h1 class="type-page text-white">
            <x-parole :text="__('site.menu.h1')" />
        </h1>
        <p class="fade-in mt-6 max-w-lg text-lg text-white" style="--i: 4">{{ __('site.menu.lead') }}</p>
    </x-hero>

    @if ($evidenza->isNotEmpty())
        <section class="bg-crema-scuro py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="type-section">{{ __('site.menu.featured') }}</h2>
                <ul class="mt-10 grid list-none gap-4 p-0 md:grid-cols-2 md:gap-6">
                    @foreach ($evidenza as $voce)
                        <li class="row-reveal flex flex-col rounded-2xl bg-white p-7 md:p-10" data-tilt>
                            <x-badge :tipo="$voce['badge']" class="self-start" />
                            <h3 class="mt-6 text-3xl md:text-4xl">{{ $voce['nome'] }}</h3>
                            <p class="mt-3 flex-1 text-lg text-ink-soft">{{ $voce['ingredienti'] }}</p>
                            <p class="mt-6 font-display text-3xl font-extrabold text-pomodoro-scuro">{{ $voce['prezzo'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <div class="sticky top-16 z-20 border-b border-sabbia bg-crema/90 backdrop-blur md:top-[4.5rem]">
        <button type="button" popovertarget="categorie-menu" class="mx-auto flex min-h-14 w-full max-w-6xl items-center justify-between gap-4 px-4 text-left">
            <span class="flex items-center gap-3">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10" /></svg>
                <span class="font-display text-lg font-bold" data-spy-label>{{ __('site.menu.all_categories') }}</span>
            </span>
            <span class="flex items-center gap-2 text-sm text-ink-soft">
                <span data-spy-count>{{ __('site.menu.categories_count', ['count' => count($menu)]) }}</span>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
            </span>
        </button>
    </div>

    <x-pannello id="categorie-menu" :etichetta="__('site.menu.categories_nav')">
        <div class="mx-auto flex min-h-full max-w-6xl flex-col px-4 pb-8">
            <div class="flex h-16 items-center justify-between">
                <p class="font-display text-lg font-bold">{{ __('site.menu.categories') }}</p>
                <button type="button" popovertarget="categorie-menu" popovertargetaction="hide" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full" aria-label="{{ __('site.menu.close_categories') }}">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 5 14 14M19 5 5 19" /></svg>
                </button>
            </div>
            <nav aria-label="{{ __('site.menu.categories_nav') }}" data-spy class="mt-4">
                <ul class="list-none border-t border-white/15 p-0">
                    @foreach ($menu as $slug => $categoria)
                        <li class="border-b border-white/15" style="--i: {{ $loop->index }}">
                            <a href="#{{ $slug }}" data-name="{{ $categoria['nome'] }}" class="group flex items-baseline justify-between gap-4 py-4 text-white no-underline aria-[current=true]:text-pomodoro md:py-5">
                                <span class="font-display type-list font-extrabold tracking-tight">{{ $categoria['nome'] }}</span>
                                <span class="shrink-0 text-sm opacity-70">{{ count($categoria['voci']) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </x-pannello>

    @foreach ($menu as $slug => $categoria)
        <section id="{{ $slug }}" class="scroll-mt-36 py-16 md:py-24 {{ $loop->even ? 'bg-crema-scuro' : '' }}">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-12 md:gap-12">
                <div class="md:col-span-5">
                    <div class="md:sticky md:top-48">
                        <h2 class="type-section">{{ $categoria['nome'] }}</h2>
                        @if ($categoria['descrizione'])
                            <p class="mt-4 text-lg text-ink-soft">{{ $categoria['descrizione'] }}</p>
                        @endif
                    </div>
                </div>
                <ul class="list-none divide-y divide-ink/15 border-y border-ink/15 p-0 md:col-span-7">
                    @foreach ($categoria['voci'] as $voce)
                        <li class="row-reveal py-6">
                            <div class="flex items-baseline justify-between gap-6">
                                <h3 class="text-2xl">{{ $voce['nome'] }}</h3>
                                <span class="shrink-0 font-display text-xl font-extrabold text-pomodoro-scuro md:text-2xl">{{ $voce['prezzo'] }}</span>
                            </div>
                            @if ($voce['badge'])
                                <x-badge :tipo="$voce['badge']" class="mt-3" />
                            @endif
                            <p class="mt-2 max-w-lg text-ink-soft">{{ $voce['ingredienti'] }}</p>
                            @if ($voce['nota'])
                                <p class="mt-3 text-sm font-medium text-ink">{{ $voce['nota'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

    <section class="bg-ink py-16 text-white md:py-24">
        <div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="font-display type-section font-extrabold tracking-tight">{{ __('site.menu.chosen') }}</p>
                <p class="mt-4 max-w-md text-stone-300">{{ __('site.menu.footnote') }}</p>
            </div>
            <x-button :href="$tel">{{ __('site.menu.call_phone', ['phone' => config('site.phone')]) }}</x-button>
        </div>
    </section>
@endsection
