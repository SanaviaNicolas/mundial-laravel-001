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

    <section class="bg-pomodoro-chiaro text-ink py-24 md:py-36">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="type-section">{{ __('site.home.visit') }}</h2>
            <p class="mt-5 text-lg">{{ config('site.address') }}</p>
            <a href="{{ $tel }}" class="mt-2 inline-block font-display type-phone font-extrabold tracking-tight text-ink no-underline"><span class="link-line">{{ config('site.phone') }}</span></a>
            <div class="mt-10 flex flex-wrap gap-3">
                <x-button :href="$tel">{{ __('site.ui.call') }}</x-button>
                <x-button :href="$pageUrl('contact')" variant="secondary">{{ __('site.home.hours_how') }}</x-button>
            </div>
        </div>
    </section>
@endsection
