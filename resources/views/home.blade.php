@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.')

@php
    $categorie = config('menu');
    // Bento tile styles, in menu order (first is the featured 2x2 tile).
    $stili = [
        'col-span-2 row-span-2 bg-blu-scuro text-white md:min-h-[22rem]',
        'bg-pomodoro text-white',
        'bg-white text-ink ring-1 ring-sabbia',
        'bg-blu text-ink',
        'bg-crema-scuro text-ink',
        'bg-ink text-white',
        'bg-white text-ink ring-1 ring-sabbia',
        'bg-pomodoro text-white',
        'bg-blu text-ink',
    ];
@endphp

@section('content')
    <section class="overflow-hidden">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 pb-24 pt-8 md:grid-cols-12 md:gap-4 md:pb-32 md:pt-16">
            <div class="relative z-10 md:col-span-7">
                <h1>
                    <span class="block text-[6rem] leading-[0.82] sm:text-[8rem] md:text-[11rem]">Pizza</span>
                    <span class="mt-3 block text-[2rem] leading-[1.05] md:mt-5 md:text-5xl">napoletana, fatta<br>come si deve.</span>
                </h1>
                <p class="mb-8 mt-6 max-w-md text-lg text-ink-soft">{{ config('app.name') }}, pizzeria e ristorante a Vigonovo. Impasto lavorato con cura, cucina di casa, forno acceso ogni sera.</p>
                <div class="flex flex-wrap gap-3">
                    <x-button :href="$tel">Chiama</x-button>
                    <x-button href="/menu" variant="secondary">Guarda il menù</x-button>
                </div>
            </div>
            <div class="relative mx-auto w-[88%] max-w-md motion-safe:animate-pop md:col-span-5 md:w-[120%] md:max-w-none md:-translate-x-0">
                <div class="absolute inset-0 -translate-x-[10%] translate-y-[12%] rounded-full bg-blu" aria-hidden="true"></div>
                <x-foto ratio="aspect-square" alt="Una pizza appena sfornata" :eager="true" class="relative rounded-full ring-8 ring-crema" />
                <x-sticker class="absolute -bottom-6 -left-2 -rotate-12 md:-bottom-8 md:-left-10" />
            </div>
        </div>
    </section>

    <div class="-mt-10 overflow-hidden py-6" aria-hidden="true">
        <div class="-mx-6 -rotate-2 overflow-hidden bg-pomodoro py-4 text-white">
        <div class="flex w-max gap-8 font-display text-3xl font-extrabold tracking-tight motion-safe:animate-marquee md:text-5xl">
            @foreach (range(1, 2) as $_)
                @foreach ($categorie as $categoria)
                    <span class="flex items-center gap-8 whitespace-nowrap">{{ $categoria['nome'] }}<span class="size-3 rounded-full bg-white md:size-4"></span></span>
                @endforeach
            @endforeach
        </div>
        </div>
    </div>

    <section class="py-20">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-5xl md:text-7xl">Cosa mangiamo<br>stasera?</h2>
            <p class="mt-4 max-w-md text-lg text-ink-soft">Nove categorie, un forno solo. Scegli da dove iniziare.</p>
            <ul class="mt-10 grid list-none auto-rows-[9.5rem] grid-cols-2 gap-3 p-0 md:auto-rows-[11rem] md:grid-cols-4 md:gap-4">
                @foreach ($categorie as $slug => $categoria)
                    <li class="contents">
                        <a href="/menu#{{ $slug }}" class="group relative flex flex-col justify-end overflow-hidden rounded-[2rem] p-5 no-underline motion-safe:transition-transform motion-safe:duration-300 motion-safe:hover:-rotate-1 motion-safe:hover:scale-[1.02] {{ $stili[$loop->index] }}">
                            @if ($loop->first)
                                <div class="absolute -right-8 -top-8 w-44 md:w-64">
                                    <x-foto ratio="aspect-square" alt="" class="rounded-full ring-8 ring-blu-scuro" />
                                </div>
                            @endif
                            <span class="font-display font-extrabold leading-none tracking-tight {{ $loop->first ? 'text-4xl md:text-6xl' : 'text-xl md:text-3xl' }}">{{ $categoria['nome'] }}</span>
                            @if ($loop->first)
                                <span class="mt-2 max-w-[14rem] text-white">{{ $categoria['descrizione'] }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="overflow-hidden bg-blu-scuro py-20 text-white">
        <div class="mx-auto grid max-w-6xl gap-12 px-4 md:grid-cols-2 md:items-center md:gap-16">
            <div class="relative mx-auto w-4/5 max-w-sm md:w-full">
                <x-foto ratio="aspect-square" alt="L'impasto lievitato, pronto da stendere" class="rounded-full ring-8 ring-blu-scuro" />
            </div>
            <div>
                <h2 class="text-5xl md:text-7xl">Impasto lungo, pizza leggera.</h2>
                <ul class="my-8 flex list-none flex-wrap gap-2 p-0">
                    @foreach (['Almeno 2 giorni di lievitazione', 'Alta idratazione', 'Alta digeribilità'] as $punto)
                        <li class="rounded-full bg-white px-4 py-2 font-display font-semibold text-blu-scuro">{{ $punto }}</li>
                    @endforeach
                </ul>
                <p class="mb-8 max-w-md text-lg">Una famiglia di pizzaioli, la tradizione napoletana portata in Veneto.</p>
                <x-button href="/la-nostra-storia" variant="light">Leggi la nostra storia</x-button>
            </div>
        </div>
    </section>

    <section class="bg-pomodoro py-20 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-5xl md:text-7xl">Vieni a trovarci</h2>
            <p class="mt-6 text-lg">{{ config('site.address') }}</p>
            <a href="{{ $tel }}" class="mt-2 inline-block font-display text-[2.6rem] font-extrabold leading-none tracking-tight text-white no-underline hover:underline md:text-8xl">{{ config('site.phone') }}</a>
            <div class="mt-8 flex flex-wrap gap-3">
                <x-button :href="$tel" variant="light">Chiama</x-button>
                <x-button href="/contatti" variant="outline-light">Orari e come arrivare</x-button>
            </div>
        </div>
    </section>
@endsection
