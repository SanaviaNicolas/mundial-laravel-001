@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.')
@section('header-overlay', true)

@section('content')
    <section class="relative flex min-h-[100svh] flex-col justify-end overflow-hidden bg-ink text-white">
        <x-foto ratio="" alt="Una pizza napoletana appena sfornata" :eager="true" :dark="true" label-class="self-start justify-self-end mr-4 mt-32 md:mt-28" class="absolute inset-0 h-full" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/25 to-black/80" aria-hidden="true"></div>
        <div class="relative mx-auto w-full max-w-6xl px-4 pb-28 pt-40 md:pb-24">
            <h1 class="max-w-3xl text-5xl text-white sm:text-6xl md:text-7xl">Pizza napoletana, fatta come si deve.</h1>
            <p class="mb-8 mt-5 max-w-lg text-lg text-white">{{ config('app.name') }}, pizzeria e ristorante a Vigonovo. Impasto lavorato con cura, cucina di casa, forno acceso ogni sera.</p>
            <div class="flex flex-wrap gap-3">
                <x-button :href="$tel">Chiama</x-button>
                <x-button href="/menu" variant="outline-light">Guarda il menù</x-button>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-5xl">Il menù</h2>
            <p class="mt-4 max-w-xl text-lg text-ink-soft">Nove categorie, dalla tradizione napoletana ai tegamini. Scegli da dove iniziare.</p>
            <ul class="mt-10 grid list-none grid-cols-1 gap-3 p-0 sm:grid-cols-2 md:grid-cols-3 md:gap-4">
                @foreach (config('menu') as $slug => $categoria)
                    <li>
                        <a href="/menu#{{ $slug }}" class="group flex h-full min-h-32 flex-col justify-end rounded-2xl bg-white p-5 text-ink no-underline ring-1 ring-sabbia hover:bg-ink hover:text-white">
                            <span class="font-display text-2xl font-bold leading-tight tracking-tight">{{ $categoria['nome'] }}</span>
                            <span class="mt-1 text-ink-soft group-hover:text-stone-300">{{ $categoria['descrizione'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-crema-scuro py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-4 md:grid-cols-2 md:items-center md:gap-16">
            <x-foto alt="L'impasto lievitato, pronto da stendere" class="rounded-2xl" />
            <div>
                <h2 class="text-4xl md:text-5xl">Impasto lungo, pizza leggera.</h2>
                <ul class="mt-8 list-none divide-y divide-sabbia border-y border-sabbia p-0">
                    @foreach (['Almeno 2 giorni di lievitazione', 'Alta idratazione', 'Alta digeribilità'] as $punto)
                        <li class="py-4 font-display text-xl font-semibold md:text-2xl">{{ $punto }}</li>
                    @endforeach
                </ul>
                <p class="mb-8 mt-6 max-w-md text-lg text-ink-soft">Una famiglia di pizzaioli, la tradizione napoletana portata in Veneto.</p>
                <x-button href="/la-nostra-storia" variant="secondary">Leggi la nostra storia</x-button>
            </div>
        </div>
    </section>

    <section class="bg-ink py-20 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl text-white md:text-5xl">Vieni a trovarci</h2>
            <p class="mt-5 text-lg">{{ config('site.address') }}</p>
            <a href="{{ $tel }}" class="mt-1 inline-block font-display text-4xl font-extrabold tracking-tight text-white no-underline hover:underline md:text-6xl">{{ config('site.phone') }}</a>
            <div class="mt-8 flex flex-wrap gap-3">
                <x-button :href="$tel">Chiama</x-button>
                <x-button href="/contatti" variant="outline-light">Orari e come arrivare</x-button>
            </div>
        </div>
    </section>
@endsection
