@extends('layouts.app')

@section('title', 'La nostra storia — pizza napoletana di famiglia | '.config('app.name'))
@section('description', config('app.name').': una famiglia di pizzaioli, la tradizione napoletana portata in Veneto, impasto con almeno due giorni di lievitazione e ingredienti originali.')
@section('header-overlay', true)

@section('content')
    <x-hero name="impasto" alt="La crosta alta e alveolata di una pizza napoletana" height="min-h-[80svh]" :eager="true" :fade="true">
        <h1 class="text-[clamp(3.25rem,11vw,9rem)] leading-[0.92] text-white">
            <span class="line"><span>La nostra</span></span>
            <span class="line"><span style="--i: 1">storia</span></span>
        </h1>
        <p class="fade-in mt-6 max-w-lg text-xl text-white" style="--i: 3">Una famiglia di pizzaioli, la tradizione napoletana portata in Veneto.</p>
    </x-hero>

    <section class="py-20 md:py-32">
        <div class="mx-auto max-w-6xl px-4">
            <x-frase class="max-w-5xl" text="Da noi la pizza è un mestiere di famiglia. Il titolare si è trasferito in Veneto da piccolo e ha aperto presto la pizzeria, portando con sé quello che conosceva meglio: la pizza di Napoli." />
        </div>
    </section>

    <section class="bg-crema-scuro py-20 md:py-28">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 md:grid-cols-2 md:gap-16">
            <x-foto name="forno" alt="Una pizza napoletana appena uscita dal forno" sizes="(min-width: 768px) 50vw, 100vw" class="rounded-2xl" />
            <div>
                <h2 class="text-4xl md:text-6xl">La tradizione, quella vera</h2>
                <div class="mt-6 space-y-5 text-lg text-ink-soft">
                    <p>Facciamo la pizza napoletana originale. Restiamo attaccati alla tradizione: niente scorciatoie, niente mode.</p>
                    <p>Gli ingredienti sono originali, ricercati e di qualità.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-ink py-20 text-white md:py-28">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="max-w-3xl text-4xl text-white md:text-6xl">La farina e il metodo giusti</h2>
            <p class="mt-5 max-w-xl text-lg text-stone-300">Ci sono voluti cinquant'anni per trovarli. Il risultato è un impasto che si sente nel piatto e anche dopo.</p>
        </div>
        <ul class="mx-auto mt-12 max-w-6xl list-none border-t border-white/20 p-0 md:mt-16">
            @foreach ([['Almeno 2 giorni', 'di lievitazione'], ['Alta idratazione', 'per un impasto morbido'], ['Alta digeribilità', 'una pizza che non pesa']] as [$titolo, $testo])
                <li class="flex flex-col gap-1 border-b border-white/20 px-4 py-6 md:flex-row md:items-baseline md:justify-between md:py-9">
                    <span class="font-display text-[clamp(2rem,6vw,4.5rem)] font-extrabold leading-none tracking-tight">{{ $titolo }}</span>
                    <span class="text-lg text-stone-300 md:text-xl">{{ $testo }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <x-hero name="ingredienti" alt="Basilico e pomodorini freschi su una pizza" height="min-h-[70svh]" position="object-[60%_50%]">
        <h2 class="max-w-3xl text-[clamp(2.5rem,7vw,5.5rem)] leading-[0.98] text-white">Assaggia la differenza.</h2>
        <div class="mt-8 flex flex-wrap gap-3">
            <x-button href="/menu">Guarda il menù</x-button>
            <x-button :href="$tel" variant="outline-light">Chiama</x-button>
        </div>
    </x-hero>
@endsection
