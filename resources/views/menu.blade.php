@extends('layouts.app')

@section('title', 'Menù — pizze, panuozzi e cucina | '.config('app.name'))
@section('description', 'Il menù di '.config('app.name').': pizze napoletane, classiche, speciali, bianche, calzoni, baguette, panuozzi e tegamini.')

@section('content')
    <section class="overflow-hidden bg-blu-scuro text-white">
        <div class="mx-auto max-w-6xl px-4 pb-14 pt-12 md:pb-20 md:pt-20">
            <h1 class="text-[4.5rem] md:text-[9rem]">Il menù</h1>
            <p class="mt-4 max-w-md text-lg text-white">Nove categorie, dalla tradizione napoletana ai tegamini. Tutto fatto al momento, dal forno al tavolo.</p>
        </div>
    </section>

    <nav aria-label="Categorie del menù" class="sticky top-0 z-20 border-b border-sabbia bg-crema/90 backdrop-blur">
        <ul class="mx-auto flex max-w-6xl list-none gap-2 overflow-x-auto px-4 py-3 [scrollbar-width:none]">
            @foreach (config('menu') as $slug => $categoria)
                <li class="shrink-0"><a href="#{{ $slug }}" class="inline-flex min-h-11 items-center rounded-full border-2 border-blu-scuro px-4 font-display font-semibold text-blu-scuro no-underline hover:bg-blu-scuro hover:text-white">{{ $categoria['nome'] }}</a></li>
            @endforeach
        </ul>
    </nav>

    @foreach (config('menu') as $slug => $categoria)
        <section id="{{ $slug }}" class="scroll-mt-16 py-14 {{ $loop->even ? 'bg-crema-scuro' : '' }}">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-4xl md:text-6xl">{{ $categoria['nome'] }}</h2>
                @isset($categoria['descrizione'])
                    <p class="mt-3 text-lg text-ink-soft">{{ $categoria['descrizione'] }}</p>
                @endisset
                <ul class="mt-8 grid list-none gap-x-14 p-0 md:grid-cols-2">
                    @foreach ($categoria['voci'] as $voce)
                        <li class="border-b border-sabbia py-5">
                            <div class="flex items-baseline justify-between gap-4">
                                <h3 class="text-2xl">{{ $voce['nome'] }}</h3>
                                <span class="whitespace-nowrap font-display text-xl font-bold text-pomodoro-scuro">{{ $voce['prezzo'] }}</span>
                            </div>
                            <p class="mt-1.5 text-ink-soft">{{ $voce['ingredienti'] }}</p>
                            @isset($voce['nota'])
                                <p class="mt-2 inline-block rounded-full bg-white px-3 py-1 text-sm font-medium text-pomodoro-scuro">{{ $voce['nota'] }}</p>
                            @endisset
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

    <section class="bg-pomodoro py-14 text-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-5xl">Hai scelto?</p>
                <p class="mt-2 text-lg">* Nota sull'asterisco: testo di esempio. Allergeni: informazioni disponibili in sala.</p>
            </div>
            <x-button :href="$tel" variant="light">Chiama {{ config('site.phone') }}</x-button>
        </div>
    </section>
@endsection
