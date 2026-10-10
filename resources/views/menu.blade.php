@extends('layouts.app')

@section('title', __('site.menu.title'))
@section('description', __('site.menu.description'))
@section('header-overlay', true)

@php
    $foglie = collect($menu)->flatMap(fn ($sezione) => $sezione->leaves())->values();
    $numero = $foglie->mapWithKeys(fn ($sezione, $i) => [$sezione->slug => $i + 1]);
    $voci = $foglie->flatMap(fn ($sezione) => $sezione->items);
    // Highlighted items with their category, the most important highlight (pizza of the month) first.
    $evidenza = $foglie
        ->flatMap(fn ($sezione) => collect($sezione->items)->filter(fn ($voce) => $voce->highlights())->map(fn ($voce) => [$voce, $sezione]))
        ->sortBy(fn ($coppia) => array_search(array_key_first($coppia[0]->highlights()), \App\Menu\Item::HIGHLIGHTS, true))
        ->values();
    $accento = ['pizza-del-mese' => 'bg-pomodoro', 'la-piu-scelta' => 'bg-blu', 'novita' => 'bg-white', 'stagionale' => 'bg-crema-scuro'];
    $surgelati = $voci->contains(fn ($voce) => collect($voce->ingredients)->contains('frozen', true));
@endphp

@section('content')
    <x-hero name="ingredienti" :alt="__('site.menu.hero_alt')" height="min-h-[75svh]" position="object-[60%_50%]">
        <h1 class="type-page text-white">
            <x-parole :text="__('site.menu.h1')" />
        </h1>
        <p class="fade-in mt-6 max-w-lg text-lg text-white" style="--i: 4">{{ __('site.menu.lead') }}</p>
    </x-hero>

    <ul class="mx-auto grid max-w-6xl list-none p-0 px-4 md:grid-cols-3">
        @foreach (__('site.dough') as [$titolo, $testo])
            <li class="border-b border-sabbia py-6 md:border-b-0 md:border-l md:px-8 md:py-10 md:first:border-l-0 md:first:pl-0">
                <p class="font-display text-2xl font-extrabold tracking-tight">{{ $titolo }}</p>
                <p class="mt-1 text-ink-soft">{{ $testo }}</p>
            </li>
        @endforeach
    </ul>

    @if ($evidenza->isNotEmpty())
        <section class="bg-crema-scuro py-16 md:py-24">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="type-section">{{ __('site.menu.featured') }}</h2>
                <ul class="mt-10 grid list-none gap-4 p-0 md:grid-cols-2 md:gap-6">
                    @foreach ($evidenza as [$voce, $sezione])
                        @php($badge = $voce->highlights())
                        <li @class(['row-reveal group relative flex flex-col overflow-hidden rounded-2xl bg-ink p-7 text-white transition-transform duration-500 ease-brand hover:-translate-y-1 md:p-10', 'md:col-span-2' => $loop->first && $loop->count % 2 === 1])>
                            <span class="absolute inset-x-0 top-0 h-1.5 {{ $accento[array_key_first($badge)] }}" aria-hidden="true"></span>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($badge as $slug => $nome)
                                        <x-badge :slug="$slug" :nome="$nome" />
                                    @endforeach
                                </div>
                                <p class="text-xs font-bold uppercase tracking-widest text-stone-300">{{ $sezione->name }}</p>
                            </div>
                            <h3 class="mt-8 text-4xl text-white md:text-5xl">{{ $voce->name }}</h3>
                            <p class="mt-4 max-w-xl flex-1 text-lg italic text-stone-300">{{ collect($voce->ingredients)->map(fn ($ingrediente) => $ingrediente->name.($ingrediente->frozen ? '*' : ''))->implode(', ') }}</p>
                            <div class="mt-10 flex items-end justify-between gap-6 border-t border-white/15 pt-6">
                                @if ($voce->price !== null)
                                    <p class="font-display text-4xl font-extrabold">{{ \App\Menu\Price::format($voce->price) }}</p>
                                @endif
                                <a href="#{{ $sezione->slug }}" class="ml-auto inline-flex min-h-11 items-center gap-2 font-semibold text-white no-underline after:absolute after:inset-0">
                                    <span class="link-line">{{ __('site.menu.in_menu') }}</span>
                                    <svg class="size-4 transition-transform duration-500 ease-brand group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <div class="sticky top-16 z-20 border-b border-sabbia bg-crema/90 backdrop-blur md:top-[4.5rem]">
        <button type="button" popovertarget="categorie-menu" class="mx-auto flex min-h-14 w-full max-w-6xl items-center justify-between gap-4 px-4 text-left">
            <span class="flex items-center gap-3">
                <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10" /></svg>
                <span class="font-display text-lg font-bold" data-spy-label>{{ __('site.menu.all_categories') }}</span>
            </span>
            <span class="flex items-center gap-2 text-sm text-ink-soft">
                <span data-spy-count>{{ __('site.menu.categories_count', ['count' => $foglie->count()]) }}</span>
                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
            </span>
        </button>
    </div>

    <x-pannello id="categorie-menu" :etichetta="__('site.menu.categories_nav')">
        <div class="mx-auto flex min-h-full max-w-6xl flex-col px-4 pb-8">
            <div class="flex h-16 items-center justify-between">
                <p class="font-display text-lg font-bold">{{ __('site.menu.categories') }}</p>
                <button type="button" popovertarget="categorie-menu" popovertargetaction="hide" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full" aria-label="{{ __('site.menu.close_categories') }}">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 5 14 14M19 5 5 19" /></svg>
                </button>
            </div>
            <nav aria-label="{{ __('site.menu.categories_nav') }}" data-spy class="mt-4">
                <ul class="list-none border-t border-white/15 p-0">
                    @foreach ($foglie as $sezione)
                        <li class="border-b border-white/15" style="--i: {{ $loop->index }}">
                            <a href="#{{ $sezione->slug }}" data-name="{{ $sezione->name }}" class="group flex items-baseline justify-between gap-4 py-4 text-white no-underline aria-[current=true]:text-pomodoro md:py-5">
                                <span class="font-display type-list font-extrabold tracking-tight">{{ $sezione->name }}</span>
                                <span class="shrink-0 text-sm opacity-70">{{ count($sezione->items) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </x-pannello>

    @foreach ($menu as $sezione)
        @if ($sezione->children)
            <section id="{{ $sezione->slug }}" class="scroll-mt-36">
                <div class="bg-ink py-14 text-white md:py-20">
                    <div class="mx-auto max-w-6xl px-4">
                        <h2 class="type-page text-white">{{ $sezione->name }}</h2>
                        @if ($sezione->description)
                            <p class="mt-4 max-w-xl text-lg text-stone-300">{{ $sezione->description }}</p>
                        @endif
                    </div>
                </div>
                @if ($sezione->items)
                    @include('menu.sezione', ['sezione' => $sezione, 'livello' => 2, 'titolo' => false, 'numero' => $numero[$sezione->slug], 'totale' => $foglie->count()])
                @endif
                @foreach ($sezione->children as $figlia)
                    @include('menu.sezione', ['sezione' => $figlia, 'livello' => 3, 'titolo' => true, 'numero' => $numero[$figlia->slug], 'totale' => $foglie->count()])
                @endforeach
            </section>
        @else
            @include('menu.sezione', ['sezione' => $sezione, 'livello' => 2, 'titolo' => true, 'numero' => $numero[$sezione->slug], 'totale' => $foglie->count()])
        @endif
    @endforeach

    <section class="bg-ink py-16 text-white md:py-24">
        <div class="mx-auto flex max-w-6xl flex-col gap-8 px-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="font-display type-section font-extrabold tracking-tight">{{ __('site.menu.chosen') }}</p>
                @if ($surgelati)
                    <p class="mt-4 max-w-md text-stone-300">{{ __('site.menu.frozen') }}</p>
                @endif
            </div>
            <x-button :href="$tel">{{ __('site.menu.call_phone', ['phone' => config('site.phone')]) }}</x-button>
        </div>
    </section>
@endsection
