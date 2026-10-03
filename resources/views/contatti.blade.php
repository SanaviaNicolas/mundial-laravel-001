@extends('layouts.app')

@section('title', 'Contatti, orari e dove siamo | '.config('app.name'))
@section('description', config('app.name').': '.config('site.address').', telefono '.config('site.phone').'. Orari di apertura e come arrivare.')

@section('content')
    <section class="py-14 md:py-20">
        <div class="mx-auto max-w-6xl px-4">
            <h1 class="text-[4rem] leading-[0.9] sm:text-[6rem] md:text-[9rem]">Dove siamo</h1>
            <p class="mt-8 text-xl">{{ config('site.address') }}</p>
            <a href="{{ $tel }}" class="mt-2 inline-block font-display text-[2.6rem] font-extrabold leading-none tracking-tight text-pomodoro-scuro no-underline hover:underline md:text-8xl">{{ config('site.phone') }}</a>
            <div class="mt-8 flex flex-wrap gap-3">
                <x-button :href="$tel">Chiama</x-button>
                <x-button :href="'https://www.google.com/maps/search/?api=1&query='.urlencode(config('site.address'))" variant="secondary" rel="noopener">Apri la mappa</x-button>
            </div>
        </div>
    </section>

    <section class="bg-crema-scuro py-14 md:py-20">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-4xl md:text-6xl">Orari</h2>
            <table class="mt-8 w-full max-w-2xl border-collapse overflow-hidden rounded-2xl bg-white text-left text-lg">
                <caption class="sr-only">Orari di apertura</caption>
                @foreach (config('site.hours') as $giorni => $orario)
                    <tr class="border-b border-sabbia last:border-0"><th scope="row" class="p-4 font-semibold">{{ $giorni }}</th><td class="p-4">{{ $orario }}</td></tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
