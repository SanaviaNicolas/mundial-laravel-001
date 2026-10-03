@extends('layouts.app')

@section('title', 'Menù — pizze, panuozzi e cucina | '.config('app.name'))
@section('description', 'Il menù di '.config('app.name').': pizze napoletane, classiche, speciali, bianche, calzoni, baguette, panuozzi e tegamini.')
@section('header-overlay', true)

@php
    $evidenza = collect(config('menu'))->flatMap(fn ($categoria) => $categoria['voci'])->filter(fn ($voce) => isset($voce['badge']));
@endphp

@section('content')
    <x-hero name="ingredienti" alt="Pomodorini e basilico su una pizza napoletana" height="min-h-[75svh]" position="object-[60%_50%]">
        <h1 class="type-page text-white">
            <span class="line"><span>Il menù</span></span>
        </h1>
        <p class="fade-in mt-6 max-w-lg text-lg text-white" style="--i: 2">Nove categorie, dalla tradizione napoletana ai tegamini. Tutto fatto al momento, dal forno al tavolo.</p>
    </x-hero>

    @if ($evidenza->isNotEmpty())
        <section class="bg-crema-scuro py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="type-section">In evidenza</h2>
                <ul class="mt-10 grid list-none gap-4 p-0 md:grid-cols-2 md:gap-6">
                    @foreach ($evidenza as $voce)
                        <li class="flex flex-col rounded-2xl bg-white p-7 md:p-10">
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
                <span class="font-display text-lg font-bold" data-spy-label>Tutte le categorie</span>
            </span>
            <span class="flex items-center gap-2 text-sm text-ink-soft">
                <span data-spy-count>{{ count(config('menu')) }} categorie</span>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
            </span>
        </button>
    </div>

    <x-pannello id="categorie-menu" etichetta="Categorie del menù">
        <div class="mx-auto flex min-h-full max-w-6xl flex-col px-4 pb-8">
            <div class="flex h-16 items-center justify-between">
                <p class="font-display text-lg font-bold">Categorie</p>
                <button type="button" popovertarget="categorie-menu" popovertargetaction="hide" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full" aria-label="Chiudi le categorie">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 5 14 14M19 5 5 19" /></svg>
                </button>
            </div>
            <nav aria-label="Categorie del menù" data-spy class="mt-4">
                <ul class="list-none border-t border-white/15 p-0">
                    @foreach (config('menu') as $slug => $categoria)
                        <li class="border-b border-white/15">
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

    @foreach (config('menu') as $slug => $categoria)
        <section id="{{ $slug }}" class="scroll-mt-36 py-16 md:py-24 {{ $loop->even ? 'bg-crema-scuro' : '' }}">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-12 md:gap-12">
                <div class="md:col-span-5">
                    <div class="md:sticky md:top-48">
                        <h2 class="type-section">{{ $categoria['nome'] }}</h2>
                        @isset($categoria['descrizione'])
                            <p class="mt-4 text-lg text-ink-soft">{{ $categoria['descrizione'] }}</p>
                        @endisset
                    </div>
                </div>
                <ul class="list-none divide-y divide-ink/15 border-y border-ink/15 p-0 md:col-span-7">
                    @foreach ($categoria['voci'] as $voce)
                        <li class="py-6">
                            <div class="flex items-baseline justify-between gap-6">
                                <h3 class="text-2xl">{{ $voce['nome'] }}</h3>
                                <span class="shrink-0 font-display text-xl font-extrabold text-pomodoro-scuro md:text-2xl">{{ $voce['prezzo'] }}</span>
                            </div>
                            @isset($voce['badge'])
                                <x-badge :tipo="$voce['badge']" class="mt-3" />
                            @endisset
                            <p class="mt-2 max-w-lg text-ink-soft">{{ $voce['ingredienti'] }}</p>
                            @isset($voce['nota'])
                                <p class="mt-3 text-sm font-medium text-ink">{{ $voce['nota'] }}</p>
                            @endisset
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

    <section class="bg-ink py-16 text-white md:py-24">
        <div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="font-display type-section font-extrabold tracking-tight">Hai scelto?</p>
                <p class="mt-4 max-w-md text-stone-300">* Nota sull'asterisco: testo di esempio. Allergeni: informazioni disponibili in sala.</p>
            </div>
            <x-button :href="$tel">Chiama {{ config('site.phone') }}</x-button>
        </div>
    </section>
@endsection
