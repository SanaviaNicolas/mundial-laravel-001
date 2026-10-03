@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.')
@section('header-overlay', true)

@section('content')
    <x-hero name="hero" alt="Primo piano di una pizza napoletana con basilico e pomodorini" :eager="true" :fade="true" position="object-[55%_50%]" sizes="(orientation: portrait) 178vh, 100vw">
        <h1 class="max-w-4xl text-[clamp(3rem,9vw,7.5rem)] leading-[0.95] text-white">
            <span class="line"><span>Pizza napoletana,</span></span>
            <span class="line"><span style="--i: 1">fatta come si deve.</span></span>
        </h1>
        <p class="fade-in mb-8 mt-6 max-w-lg text-lg text-white" style="--i: 3">{{ config('app.name') }}, pizzeria e ristorante a Vigonovo. Impasto lavorato con cura, cucina di casa, forno acceso ogni sera.</p>
        <div class="fade-in flex flex-wrap gap-3" style="--i: 4">
            <x-button :href="$tel">Chiama</x-button>
            <x-button href="/menu" variant="outline-light">Guarda il menù</x-button>
        </div>
    </x-hero>

    <section class="bg-ink pb-28 pt-12 text-white md:pb-40 md:pt-20">
        <div class="mx-auto max-w-6xl px-4">
            <x-frase class="max-w-5xl" text="Almeno 2 giorni di lievitazione. Alta idratazione. Alta digeribilità. Una pizza napoletana che pesa poco e sa di tutto." />
        </div>
    </section>

    <section class="py-20 md:py-28">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-6xl">Il menù</h2>
            <p class="mt-4 max-w-xl text-lg text-ink-soft">Nove categorie, dalla tradizione napoletana ai tegamini. Scegli da dove iniziare.</p>
        </div>
        <ul class="mx-auto mt-10 max-w-6xl list-none border-t border-ink/15 p-0 md:mt-14">
            @foreach (config('menu') as $slug => $categoria)
                <li class="border-b border-ink/15">
                    <a href="/menu#{{ $slug }}" class="group relative block overflow-hidden px-4 py-5 text-ink no-underline transition-colors duration-300 hover:text-white focus-visible:text-white md:py-7">
                        <span class="absolute inset-0 -translate-x-full bg-pomodoro transition-transform duration-500 ease-out group-hover:translate-x-0 group-focus-visible:translate-x-0" aria-hidden="true"></span>
                        <span class="relative flex items-baseline justify-between gap-6">
                            <span class="font-display text-[clamp(1.9rem,5.5vw,4.5rem)] font-extrabold leading-none tracking-tight">{{ $categoria['nome'] }}</span>
                            <span class="shrink-0 text-sm opacity-70 md:text-base">{{ count($categoria['voci']) }} {{ count($categoria['voci']) === 1 ? 'voce' : 'voci' }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <x-hero name="impasto" alt="La crosta alta e alveolata di una pizza napoletana" height="min-h-[85svh]">
        <h2 class="max-w-3xl text-[clamp(2.5rem,7vw,5.5rem)] leading-[0.98] text-white">Una famiglia di pizzaioli.</h2>
        <p class="mb-8 mt-5 max-w-md text-lg text-white">La tradizione napoletana portata in Veneto, con ingredienti originali, ricercati e di qualità.</p>
        <x-button href="/la-nostra-storia" variant="outline-light">Leggi la nostra storia</x-button>
    </x-hero>

    <section class="bg-ink py-24 text-white md:py-36">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl text-white md:text-6xl">Vieni a trovarci</h2>
            <p class="mt-5 text-lg">{{ config('site.address') }}</p>
            <a href="{{ $tel }}" class="mt-2 inline-block font-display text-[clamp(2.5rem,9vw,7rem)] font-extrabold leading-none tracking-tight text-white no-underline hover:underline">{{ config('site.phone') }}</a>
            <div class="mt-10 flex flex-wrap gap-3">
                <x-button :href="$tel">Chiama</x-button>
                <x-button href="/contatti" variant="outline-light">Orari e come arrivare</x-button>
            </div>
        </div>
    </section>
@endsection
