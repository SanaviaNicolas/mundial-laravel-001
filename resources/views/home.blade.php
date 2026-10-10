@extends('layouts.app')

@section('title', __('site.home.title'))
@section('description', __('site.home.description'))
@section('header-overlay', true)

@section('content')
    <x-hero name="hero" :alt="__('site.home.hero_alt')" :eager="true" :fade="true" position="object-[55%_50%]" sizes="(orientation: portrait) 178vh, 100vw">
        <h1 class="max-w-4xl type-hero text-white">
            <span class="block"><x-parole :text="__('site.home.h1_a')" /></span>
            <span class="block"><x-parole :text="__('site.home.h1_b')" :start="count(explode(' ', __('site.home.h1_a')))" /></span>
        </h1>
        <p class="fade-in mb-8 mt-6 max-w-lg text-lg text-white" style="--i: 6">{{ __('site.home.lead') }}</p>
        <div class="fade-in flex flex-wrap gap-3" style="--i: 7">
            <x-button :href="$tel">{{ __('site.ui.call') }}</x-button>
            <x-button :href="$pageUrl('menu')" variant="outline-light">{{ __('site.home.see_menu') }}</x-button>
        </div>
    </x-hero>

    <section class="overflow-hidden bg-ink pt-12 text-white md:pt-20">
        <div class="mx-auto max-w-6xl px-4">
            <x-frase class="max-w-5xl" :text="__('site.home.statement')" />
        </div>
        <x-marquee :words="__('site.home.marquee')" class="mt-16 border-t border-white/15 py-5 text-[clamp(1.25rem,2.4vw,1.875rem)] text-white/85 md:mt-24 md:py-7" />
    </section>

    <section class="py-20 md:py-28">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="type-section">{{ __('site.home.menu_title') }}</h2>
            <p class="mt-4 max-w-xl text-lg text-ink-soft">{{ __('site.home.menu_text') }}</p>
        </div>
        <ul class="mx-auto mt-10 max-w-6xl list-none border-t border-ink/15 p-0 md:mt-14" data-preview>
            @foreach (collect($menu)->flatMap(fn ($sezione) => $sezione->leaves()) as $categoria)
                <li class="border-b border-ink/15" @if (file_exists(public_path("images/categoria-{$categoria->slug}-640.webp"))) data-img="{{ asset("images/categoria-{$categoria->slug}-640.webp") }}" @endif>
                    <a href="{{ $pageUrl('menu') }}#{{ $categoria->slug }}" class="group relative block overflow-hidden px-4 py-5 text-ink no-underline transition-colors duration-500 ease-brand hover:text-white focus-visible:text-white md:py-7">
                        <span class="absolute inset-0 -translate-x-full bg-pomodoro transition-transform duration-500 ease-brand group-hover:translate-x-0 group-focus-visible:translate-x-0" aria-hidden="true"></span>
                        <span class="relative flex items-baseline justify-between gap-6">
                            <span class="font-display type-list font-extrabold tracking-tight">{{ $categoria->name }}</span>
                            @if ($categoria->description)
                                <span class="max-w-xs text-right text-sm italic text-ink-soft group-hover:text-white group-focus-visible:text-white max-md:hidden md:text-base">{{ $categoria->description }}</span>
                            @endif
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <x-hero name="impasto" :alt="__('site.home.band_alt')" height="min-h-[85svh]" :unveil="true">
        <h2 class="max-w-3xl type-band text-white">{{ __('site.home.band_title') }}</h2>
        <p class="mb-8 mt-5 max-w-md text-lg text-white">{{ __('site.home.band_text') }}</p>
        <x-button :href="$pageUrl('story')" variant="outline-light">{{ __('site.home.read_story') }}</x-button>
    </x-hero>

    {{-- Reviews: real quotes chosen by the client (config/site.php), never invented; no rating without real data. --}}
    <section class="pt-20 md:pt-28">
        <div class="mx-auto max-w-6xl px-4">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="type-section">{{ __('site.home.reviews_title') }}</h2>
                    <p class="mt-4 max-w-xl text-lg text-ink-soft">{{ __('site.home.reviews_text') }}</p>
                </div>
                <x-button :href="config('site.map_url')" variant="secondary" :external="true" class="self-start md:self-auto">{{ __('site.home.reviews_link') }}</x-button>
            </div>
            @if (config('site.reviews'))
                <ul class="-mx-4 mt-10 flex list-none snap-x snap-mandatory scroll-px-4 gap-4 overflow-x-auto px-4 pb-2 md:mx-0 md:grid md:grid-cols-3 md:gap-6 md:overflow-visible md:p-0">
                    @foreach (config('site.reviews') as $recensione)
                        <li class="row-reveal w-[85%] shrink-0 snap-start md:w-auto">
                            <figure class="flex h-full flex-col rounded-2xl bg-white p-7 md:p-8">
                                <span class="block h-8 font-display text-6xl font-extrabold leading-[0.9] text-pomodoro" aria-hidden="true">&ldquo;</span>
                                <blockquote class="mt-3 flex-1 text-lg italic text-ink">{{ is_array($recensione['testo']) ? ($recensione['testo'][app()->getLocale()] ?? $recensione['testo']['it']) : $recensione['testo'] }}</blockquote>
                                <figcaption class="mt-6 text-sm"><span class="font-semibold">{{ $recensione['autore'] }}</span> <span class="text-muted">· {{ $recensione['fonte'] }}</span></figcaption>
                            </figure>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>

    <section class="py-20 md:py-28">
        <div class="mx-auto max-w-6xl px-4">
            <div class="grid items-center gap-10 rounded-2xl bg-crema-scuro p-6 sm:p-10 md:grid-cols-2 md:gap-12 md:p-12">
                <div>
                    <h2 class="type-section">{{ __('site.home.visit') }}</h2>
                    <p class="mt-5 text-lg text-ink-soft">{{ config('site.address') }}</p>
                    <a href="{{ $tel }}" class="mt-2 inline-block font-display type-phone font-extrabold tracking-tight text-ink no-underline transition-opacity duration-300 ease-brand hover:opacity-70">{{ config('site.phone') }}</a>
                    <p class="mt-4 max-w-md text-ink-soft">{{ __('site.service.book_takeaway') }} {{ __('site.service.no_delivery') }}</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <x-button :href="$tel">{{ __('site.ui.call') }}</x-button>
                        <x-button :href="$pageUrl('contact')" variant="secondary">{{ __('site.home.hours_how') }}</x-button>
                    </div>
                </div>
                <x-mappa :scheda="false" sizes="(min-width: 768px) 520px, 100vw" />
            </div>
        </div>
    </section>
@endsection
