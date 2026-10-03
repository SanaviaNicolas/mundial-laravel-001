@extends('layouts.app')

@section('title', 'Contatti, orari e dove siamo | '.config('app.name'))
@section('description', config('app.name').': '.config('site.address').', telefono '.config('site.phone').'. Orari di apertura e come arrivare.')
@section('header-overlay', true)

@section('content')
    <x-hero name="forno" alt="Una pizza napoletana appena uscita dal forno" height="min-h-[65svh]" :eager="true" position="object-[50%_60%]">
        <h1 class="type-page text-white">
            <span class="line"><span>Dove siamo</span></span>
        </h1>
        <p class="fade-in mt-6 text-xl text-white" style="--i: 2">{{ config('site.address') }}</p>
    </x-hero>

    <section class="py-20 md:py-28">
        <div class="mx-auto grid max-w-6xl gap-14 px-4 md:grid-cols-2 md:gap-16">
            <div>
                <h2 class="type-section">Chiamaci</h2>
                <p class="mt-4 max-w-md text-lg text-ink-soft">Per qualsiasi informazione: rispondiamo volentieri.</p>
                <a href="{{ $tel }}" class="mt-6 inline-block font-display type-phone font-extrabold tracking-tight text-ink no-underline hover:underline">{{ config('site.phone') }}</a>
                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button :href="$tel">Chiama</x-button>
                    <x-button :href="'https://www.google.com/maps/search/?api=1&query='.urlencode(config('site.address'))" variant="secondary" rel="noopener">Apri la mappa</x-button>
                </div>
            </div>
            <div>
                <h2 class="type-section">Orari</h2>
                <table class="mt-6 w-full border-collapse text-left">
                    <caption class="sr-only">Orari di apertura</caption>
                    @foreach (config('site.hours') as $giorni => $orario)
                        <tr class="border-b border-ink/15 first:border-t">
                            <th scope="row" class="py-5 pr-4 font-display text-xl font-bold md:text-2xl">{{ $giorni }}</th>
                            <td class="py-5 text-right text-xl md:text-2xl">{{ $orario }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
