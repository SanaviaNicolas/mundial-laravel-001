@extends('layouts.app')

@section('title', 'Menù — pizze, panuozzi e cucina | '.config('app.name'))
@section('description', 'Il menù di '.config('app.name').': pizze napoletane, classiche, speciali, bianche, calzoni, baguette, panuozzi e tegamini.')

@section('content')
    <section class="pb-10 pt-8 md:pb-14 md:pt-14">
        <div class="mx-auto max-w-6xl px-4">
            <h1 class="text-5xl md:text-7xl">Il menù</h1>
            <p class="mt-4 max-w-lg text-lg text-ink-soft">Nove categorie, dalla tradizione napoletana ai tegamini. Tutto fatto al momento, dal forno al tavolo.</p>
        </div>
    </section>

    <nav aria-label="Categorie del menù" data-spy class="border-b border-sabbia bg-crema/90 md:sticky md:top-0 md:z-20 md:backdrop-blur">
        <ul class="mx-auto flex max-w-6xl list-none flex-wrap gap-2 px-4 py-3">
            @foreach (config('menu') as $slug => $categoria)
                <li><a href="#{{ $slug }}" class="inline-flex min-h-11 items-center rounded-full border-2 border-ink px-4 font-display font-semibold text-ink no-underline hover:bg-ink hover:text-white aria-[current=true]:bg-ink aria-[current=true]:text-white">{{ $categoria['nome'] }}</a></li>
            @endforeach
        </ul>
    </nav>

    @foreach (config('menu') as $slug => $categoria)
        <section id="{{ $slug }}" class="scroll-mt-16 py-14 {{ $loop->even ? 'bg-crema-scuro' : '' }}">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl md:text-5xl">{{ $categoria['nome'] }}</h2>
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
                                <p class="mt-2 inline-block rounded-full bg-white px-3 py-1 text-sm font-medium text-ink-soft">{{ $voce['nota'] }}</p>
                            @endisset
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endforeach

    <section class="bg-ink py-14 text-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-4xl">Hai scelto?</p>
                <p class="mt-2 text-lg">* Nota sull'asterisco: testo di esempio. Allergeni: informazioni disponibili in sala.</p>
            </div>
            <x-button :href="$tel">Chiama {{ config('site.phone') }}</x-button>
        </div>
    </section>
@endsection
