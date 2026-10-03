@extends('layouts.app')

@section('title', config('app.name').' — Pizzeria e ristorante')
@section('description', config('app.name').', pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.')

@section('content')
    <section class="mx-auto grid max-w-6xl md:grid-cols-[1.2fr_1fr] md:items-center md:gap-10 md:px-4 md:py-12">
        <x-foto ratio="aspect-video" alt="Una pizza appena sfornata" :eager="true" class="md:rounded-2xl" />
        <div class="px-4 pb-12 pt-8 md:p-0">
            <p class="mb-2 font-display text-xs font-semibold uppercase tracking-[0.14em] text-blu-scuro">Pizzeria e ristorante</p>
            <h1 class="text-3xl font-bold md:text-5xl">{{ config('app.name') }} — pizzeria e ristorante a Città</h1>
            <p class="mb-6 mt-3 max-w-xl text-lg text-ink-soft">Pizza napoletana dall'impasto lavorato con cura e cucina di casa. Vieni a trovarci o chiamaci.</p>
            <div class="flex flex-wrap gap-3">
                <x-button :href="$tel">Chiama</x-button>
                <x-button href="#menu" variant="secondary">Guarda il menù</x-button>
            </div>
        </div>
    </section>

    <section class="bg-crema-scuro py-12">
        <div class="mx-auto max-w-6xl px-4">
            <p class="mb-2 font-display text-xs font-semibold uppercase tracking-[0.14em] text-blu-scuro">Le specialità</p>
            <h2 class="text-2xl font-bold md:text-3xl">Le nostre pizze</h2>
            <div class="mt-3 h-1 w-14 rounded bg-blu"></div>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ([
                    ['Pizza di esempio', 'Pomodoro, mozzarella, basilico.', 'A fine cottura: olio extravergine.'],
                    ['Pizza della casa*', 'Ingredienti della pizza, scritti per esteso.', 'Nota sulla pizza: testo di esempio.'],
                    ['Pizza speciale', 'Altra pizza con ingredienti di esempio.', 'Servito con: contorno di esempio.'],
                ] as [$nome, $ingredienti, $nota])
                    <article class="overflow-hidden rounded-2xl bg-white">
                        <x-foto :alt="$nome" />
                        <div class="p-4">
                            <div class="flex items-baseline justify-between gap-3">
                                <h3 class="text-xl font-semibold">{{ $nome }}</h3>
                                <span class="whitespace-nowrap font-display font-bold">€ 00,00</span>
                            </div>
                            <p class="mt-1.5 text-ink-soft">{{ $ingredienti }}</p>
                            <p class="mt-2 text-sm text-muted">{{ $nota }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="menu" class="py-12">
        <div class="mx-auto max-w-6xl px-4">
            <p class="mb-2 font-display text-xs font-semibold uppercase tracking-[0.14em] text-blu-scuro">Il menù</p>
            <h2 class="text-2xl font-bold md:text-3xl">Menù per categoria</h2>
            <div class="mt-3 h-1 w-14 rounded bg-blu"></div>
            <p class="mt-6 max-w-xl text-ink-soft">Il menù completo con tutte le categorie sarà disponibile in questa sezione. Testo di esempio.</p>
        </div>
    </section>

    <section id="ristorante" class="bg-crema-scuro py-12">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-2 md:items-center md:gap-10">
            <x-foto ratio="aspect-[3/2]" alt="Il locale" class="rounded-2xl" />
            <div>
                <p class="mb-2 font-display text-xs font-semibold uppercase tracking-[0.14em] text-blu-scuro">Il ristorante</p>
                <h2 class="text-2xl font-bold md:text-3xl">Il locale</h2>
                <div class="mt-3 h-1 w-14 rounded bg-blu"></div>
                <p class="mb-6 mt-6 text-ink-soft">Testo di presentazione del locale: la storia, l'impasto e la cucina. Contenuto di esempio da sostituire.</p>
            </div>
        </div>
    </section>

    <section id="contatti" class="py-12">
        <div class="mx-auto max-w-6xl px-4">
            <p class="mb-2 font-display text-xs font-semibold uppercase tracking-[0.14em] text-blu-scuro">Contatti</p>
            <h2 class="text-2xl font-bold md:text-3xl">Dove siamo e orari</h2>
            <div class="mt-3 h-1 w-14 rounded bg-blu"></div>
            <div class="mt-6 grid gap-8 md:grid-cols-2">
                <div>
                    <p><strong>Indirizzo</strong><br>{{ config('site.address') }}</p>
                    <p class="mb-4 mt-4"><strong>Telefono</strong><br><a href="{{ $tel }}" class="text-blu-scuro underline">{{ config('site.phone') }}</a></p>
                    <x-button :href="$tel">Chiama</x-button>
                </div>
                <table class="w-full border-collapse bg-white text-left">
                    <caption class="mb-1 text-left text-sm text-muted">Orari di apertura</caption>
                    @foreach (config('site.hours') as $giorni => $orario)
                        <tr class="border-b border-sabbia"><th scope="row" class="p-2.5 font-semibold">{{ $giorni }}</th><td class="p-2.5">{{ $orario }}</td></tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
