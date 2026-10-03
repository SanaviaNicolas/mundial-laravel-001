@extends('layouts.app')

@section('title', 'Menù — pizze, panuozzi e cucina | '.config('app.name'))
@section('description', 'Il menù di '.config('app.name').': pizze napoletane, classiche, speciali, bianche, calzoni, baguette, panuozzi e tegamini.')
@section('header-overlay', true)

@php
    $evidenza = collect(config('menu'))->flatMap(fn ($categoria) => $categoria['voci'])->filter(fn ($voce) => isset($voce['badge']));
@endphp

@section('content')
    <x-hero name="ingredienti" alt="Pomodorini e basilico su una pizza napoletana" height="min-h-[75svh]" position="object-[60%_50%]">
        <h1 class="text-[clamp(3.5rem,13vw,10rem)] leading-[0.92] text-white">
            <span class="line"><span>Il menù</span></span>
        </h1>
        <p class="fade-in mt-6 max-w-lg text-lg text-white" style="--i: 2">Nove categorie, dalla tradizione napoletana ai tegamini. Tutto fatto al momento, dal forno al tavolo.</p>
    </x-hero>

    @if ($evidenza->isNotEmpty())
        <section class="bg-crema-scuro py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-4xl md:text-6xl">In evidenza</h2>
                <ul class="mt-10 grid list-none gap-4 p-0 md:grid-cols-2 md:gap-6">
                    @foreach ($evidenza as $voce)
                        <li class="flex flex-col rounded-2xl bg-white p-7 md:p-10">
                            <x-badge :tipo="$voce['badge']" class="self-start" />
                            <h3 class="mt-6 text-3xl md:text-5xl">{{ $voce['nome'] }}</h3>
                            <p class="mt-3 flex-1 text-lg text-ink-soft">{{ $voce['ingredienti'] }}</p>
                            <p class="mt-6 font-display text-3xl font-extrabold text-pomodoro-scuro">{{ $voce['prezzo'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <nav aria-label="Categorie del menù" data-spy class="border-b border-sabbia bg-crema/90 md:sticky md:top-[4.5rem] md:z-20 md:backdrop-blur">
        <ul class="mx-auto flex max-w-6xl list-none flex-wrap gap-2 px-4 py-3">
            @foreach (config('menu') as $slug => $categoria)
                <li><a href="#{{ $slug }}" class="inline-flex min-h-11 items-center rounded-full border-2 border-ink px-4 font-display font-semibold text-ink no-underline hover:bg-ink hover:text-white aria-[current=true]:bg-ink aria-[current=true]:text-white">{{ $categoria['nome'] }}</a></li>
            @endforeach
        </ul>
    </nav>

    @foreach (config('menu') as $slug => $categoria)
        <section id="{{ $slug }}" class="scroll-mt-40 py-16 md:py-24 {{ $loop->even ? 'bg-crema-scuro' : '' }}">
            <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-12 md:gap-12">
                <div class="md:col-span-5">
                    <div class="md:sticky md:top-44">
                        <h2 class="text-4xl md:text-6xl">{{ $categoria['nome'] }}</h2>
                        @isset($categoria['descrizione'])
                            <p class="mt-4 text-lg text-ink-soft">{{ $categoria['descrizione'] }}</p>
                        @endisset
                    </div>
                </div>
                <ul class="list-none divide-y divide-ink/15 border-y border-ink/15 p-0 md:col-span-7">
                    @foreach ($categoria['voci'] as $voce)
                        <li class="py-6">
                            <div class="flex items-baseline justify-between gap-6">
                                <h3 class="text-2xl md:text-3xl">{{ $voce['nome'] }}</h3>
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
                <p class="font-display text-4xl font-extrabold leading-none tracking-tight md:text-6xl">Hai scelto?</p>
                <p class="mt-4 max-w-md text-stone-300">* Nota sull'asterisco: testo di esempio. Allergeni: informazioni disponibili in sala.</p>
            </div>
            <x-button :href="$tel">Chiama {{ config('site.phone') }}</x-button>
        </div>
    </section>
@endsection
