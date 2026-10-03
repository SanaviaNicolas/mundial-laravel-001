@extends('layouts.app')

@section('title', 'La nostra storia — pizza napoletana di famiglia | '.config('app.name'))
@section('description', config('app.name').': una famiglia di pizzaioli, la tradizione napoletana portata in Veneto, impasto con almeno due giorni di lievitazione e ingredienti originali.')

@section('content')
    <section class="overflow-hidden bg-blu-scuro text-white">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 pb-16 pt-12 md:grid-cols-12 md:pb-24 md:pt-20">
            <div class="md:col-span-8">
                <h1 class="text-[4rem] leading-[0.9] sm:text-[6rem] md:text-[8.5rem]">La nostra storia</h1>
                <p class="mt-6 max-w-lg text-xl text-white">Una famiglia di pizzaioli, la tradizione napoletana portata in Veneto.</p>
            </div>
            <div class="mx-auto w-3/5 max-w-xs md:col-span-4 md:w-full">
                <x-foto ratio="aspect-square" alt="La famiglia al lavoro in pizzeria" :eager="true" class="rounded-full ring-8 ring-crema" />
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-4 md:grid-cols-2 md:gap-16">
            <div>
                <h2 class="text-4xl md:text-6xl">Una famiglia di pizzaioli</h2>
            </div>
            <div class="space-y-5 text-lg text-ink-soft">
                <p>Da noi la pizza è un mestiere di famiglia, tramandato da chi la fa da sempre.</p>
                <p>Il titolare si è trasferito in Veneto da piccolo e ha aperto presto la pizzeria, portando con sé quello che conosceva meglio: la pizza di Napoli.</p>
            </div>
        </div>
    </section>

    <section class="bg-crema-scuro py-20">
        <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 md:grid-cols-2 md:gap-16">
            <x-foto ratio="aspect-[3/2]" alt="Una pizza napoletana dal bordo alto" class="-rotate-2 rounded-[2rem]" />
            <div>
                <h2 class="text-4xl md:text-6xl">La tradizione, quella vera</h2>
                <div class="mt-6 space-y-5 text-lg text-ink-soft">
                    <p>Facciamo la pizza napoletana originale. Restiamo attaccati alla tradizione: niente scorciatoie, niente mode.</p>
                    <p>Gli ingredienti sono originali, ricercati e di qualità.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden py-20">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="max-w-3xl text-4xl md:text-6xl">La farina e il metodo giusti</h2>
            <p class="mt-6 max-w-xl text-lg text-ink-soft">Ci sono voluti cinquant'anni per trovarli. Il risultato è un impasto che si sente nel piatto e anche dopo.</p>
            <ul class="mt-12 grid list-none gap-4 p-0 md:grid-cols-3">
                <li class="rounded-[2rem] bg-pomodoro p-7 text-white">
                    <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-4xl">Almeno 2 giorni</p>
                    <p class="mt-3 text-lg">di lievitazione.</p>
                </li>
                <li class="rounded-[2rem] bg-blu p-7 text-ink">
                    <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-4xl">Alta idratazione</p>
                    <p class="mt-3 text-lg">per un impasto morbido.</p>
                </li>
                <li class="rounded-[2rem] bg-ink p-7 text-white">
                    <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-4xl">Alta digeribilità</p>
                    <p class="mt-3 text-lg">una pizza che non pesa.</p>
                </li>
            </ul>
        </div>
    </section>

    <section class="bg-pomodoro py-16 text-white">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 md:flex-row md:items-center md:justify-between">
            <p class="font-display text-3xl font-extrabold leading-none tracking-tight md:text-5xl">Assaggia la differenza.</p>
            <div class="flex flex-wrap gap-3">
                <x-button href="/menu" variant="light">Guarda il menù</x-button>
                <x-button :href="$tel" variant="outline-light">Chiama</x-button>
            </div>
        </div>
    </section>
@endsection
