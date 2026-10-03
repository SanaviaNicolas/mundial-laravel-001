@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.')

@section('content')
    <section class="overflow-hidden">
        <div class="mx-auto grid max-w-6xl items-center gap-12 px-4 pb-16 pt-6 md:grid-cols-2 md:gap-8 md:pb-24 md:pt-12">
            <div>
                <h1 class="text-[2.75rem] md:text-7xl">Pizza napoletana, fatta come si deve.</h1>
                <p class="mb-8 mt-5 max-w-md text-lg text-ink-soft">{{ config('app.name') }}, pizzeria e ristorante a Città. Impasto lavorato con cura, cucina di casa, forno acceso ogni sera.</p>
                <div class="flex flex-wrap gap-3">
                    <x-button :href="$tel">Chiama</x-button>
                    <x-button href="#menu" variant="secondary">Guarda il menù</x-button>
                </div>
            </div>
            <div class="relative mx-auto w-[82%] max-w-md motion-safe:animate-pop md:w-full md:max-w-none">
                <div class="absolute inset-0 translate-x-[14%] translate-y-[10%] rounded-full bg-blu" aria-hidden="true"></div>
                <x-foto ratio="aspect-square" alt="Una pizza appena sfornata" :eager="true" class="relative rounded-full ring-8 ring-crema" />
            </div>
        </div>
    </section>

    <section class="bg-blu-scuro pb-16 pt-14 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-5xl">Le nostre pizze</h2>
            <div class="mt-20 grid gap-x-6 gap-y-24 md:grid-cols-3">
                @foreach ([
                    ['Pizza di esempio', 'Pomodoro, mozzarella, basilico.', 'A fine cottura: olio extravergine.'],
                    ['Pizza della casa*', 'Ingredienti della pizza, scritti per esteso.', 'Nota sulla pizza: testo di esempio.'],
                    ['Pizza speciale', 'Altra pizza con ingredienti di esempio.', 'Servito con: contorno di esempio.'],
                ] as [$nome, $ingredienti, $nota])
                    <article class="relative rounded-[2rem] bg-white px-6 pb-7 pt-36 text-ink">
                        <div class="absolute -top-14 left-1/2 w-44 -translate-x-1/2">
                            <x-foto ratio="aspect-square" :alt="$nome" class="rounded-full ring-8 ring-blu-scuro" />
                        </div>
                        <div class="flex items-baseline justify-between gap-3">
                            <h3 class="text-2xl">{{ $nome }}</h3>
                            <span class="whitespace-nowrap font-display text-xl font-bold text-pomodoro-scuro">€ 00,00</span>
                        </div>
                        <p class="mt-2 text-ink-soft">{{ $ingredienti }}</p>
                        <p class="mt-3 text-sm text-muted">{{ $nota }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="menu" class="py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-5xl">Il menù</h2>
            <p class="mb-8 mt-4 max-w-xl text-ink-soft">Nove categorie, dalla tradizione napoletana ai tegamini. Il menù completo, con ingredienti e prezzi, arriva presto.</p>
            <ul class="flex list-none flex-wrap gap-2 p-0">
                @foreach (['Tradizione napoletana', 'Le classiche', 'Le speciali', 'Fiorfritta', 'Le bianche', 'Le chiuse', 'Baguette', 'Panuozzi', 'Tegamini'] as $categoria)
                    <li class="rounded-full border-2 border-blu-scuro px-4 py-2 font-display font-semibold text-blu-scuro">{{ $categoria }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section id="ristorante" class="bg-pomodoro py-16 text-white">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 md:grid-cols-2 md:items-center md:gap-14">
            <x-foto ratio="aspect-[3/2]" alt="Il locale" class="rounded-[2rem]" />
            <div>
                <h2 class="text-4xl md:text-5xl">Il ristorante</h2>
                <p class="mb-8 mt-4 max-w-md text-lg">Testo di presentazione del locale: la storia, l'impasto e la cucina. Contenuto di esempio da sostituire.</p>
                <x-button href="#contatti" variant="light">Come arrivare</x-button>
            </div>
        </div>
    </section>

    <section id="contatti" class="py-16">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-5xl">Dove siamo</h2>
            <div class="mt-8 grid gap-10 md:grid-cols-2">
                <div>
                    <p><strong>Indirizzo</strong><br>{{ config('site.address') }}</p>
                    <p class="mb-6 mt-4"><strong>Telefono</strong><br><a href="{{ $tel }}" class="text-blu-scuro underline">{{ config('site.phone') }}</a></p>
                    <x-button :href="$tel">Chiama</x-button>
                </div>
                <table class="w-full border-collapse overflow-hidden rounded-2xl bg-white text-left">
                    <caption class="mb-2 text-left text-sm text-muted">Orari di apertura</caption>
                    @foreach (config('site.hours') as $giorni => $orario)
                        <tr class="border-b border-sabbia last:border-0"><th scope="row" class="p-3 font-semibold">{{ $giorni }}</th><td class="p-3">{{ $orario }}</td></tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
