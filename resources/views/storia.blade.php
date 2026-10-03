@extends('layouts.app')

@section('title', 'La nostra storia — pizza napoletana di famiglia | '.config('app.name'))
@section('description', config('app.name').': una famiglia di pizzaioli, la tradizione napoletana portata in Veneto, impasto con almeno due giorni di lievitazione e ingredienti originali.')

@section('content')
    <section class="pb-12 pt-8 md:pb-16 md:pt-14">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 md:grid-cols-2 md:gap-16">
            <div>
                <h1 class="text-5xl md:text-7xl">La nostra storia</h1>
                <p class="mt-5 max-w-lg text-xl text-ink-soft">Una famiglia di pizzaioli, la tradizione napoletana portata in Veneto.</p>
            </div>
            <x-foto alt="La famiglia al lavoro in pizzeria" :eager="true" :dark="true" class="rounded-2xl" />
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-2 md:gap-16">
            <h2 class="text-3xl md:text-5xl">Una famiglia di pizzaioli</h2>
            <div class="space-y-5 text-lg text-ink-soft">
                <p>Da noi la pizza è un mestiere di famiglia, tramandato da chi la fa da sempre.</p>
                <p>Il titolare si è trasferito in Veneto da piccolo e ha aperto presto la pizzeria, portando con sé quello che conosceva meglio: la pizza di Napoli.</p>
            </div>
        </div>
    </section>

    <section class="bg-crema-scuro py-16">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 md:grid-cols-2 md:gap-16">
            <x-foto alt="Una pizza napoletana dal bordo alto" class="rounded-2xl" />
            <div>
                <h2 class="text-3xl md:text-5xl">La tradizione, quella vera</h2>
                <div class="mt-6 space-y-5 text-lg text-ink-soft">
                    <p>Facciamo la pizza napoletana originale. Restiamo attaccati alla tradizione: niente scorciatoie, niente mode.</p>
                    <p>Gli ingredienti sono originali, ricercati e di qualità.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="max-w-3xl text-3xl md:text-5xl">La farina e il metodo giusti</h2>
            <p class="mt-5 max-w-xl text-lg text-ink-soft">Ci sono voluti cinquant'anni per trovarli. Il risultato è un impasto che si sente nel piatto e anche dopo.</p>
            <ul class="mt-10 grid list-none gap-4 p-0 md:grid-cols-3">
                @foreach ([['Almeno 2 giorni', 'di lievitazione.'], ['Alta idratazione', 'per un impasto morbido.'], ['Alta digeribilità', 'una pizza che non pesa.']] as [$titolo, $testo])
                    <li class="rounded-2xl bg-ink p-7 text-white">
                        <p class="font-display text-2xl font-extrabold leading-tight tracking-tight md:text-3xl">{{ $titolo }}</p>
                        <p class="mt-2 text-lg text-stone-300">{{ $testo }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-ink py-14 text-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 md:flex-row md:items-center md:justify-between">
            <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-4xl">Assaggia la differenza.</p>
            <div class="flex flex-wrap gap-3">
                <x-button href="/menu">Guarda il menù</x-button>
                <x-button :href="$tel" variant="outline-light">Chiama</x-button>
            </div>
        </div>
    </section>
@endsection
