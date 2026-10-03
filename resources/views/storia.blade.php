@extends('layouts.app')

@section('title', __('site.story.title'))
@section('description', __('site.story.description'))
@section('header-overlay', true)

@section('content')
    <x-hero name="impasto" :alt="__('site.story.hero_alt')" height="min-h-[80svh]" :eager="true" :fade="true">
        <h1 class="type-page text-white">
            <span class="block"><x-parole :text="__('site.story.h1_a')" /></span>
            <span class="block"><x-parole :text="__('site.story.h1_b')" :start="count(explode(' ', __('site.story.h1_a')))" /></span>
        </h1>
        <p class="fade-in mt-6 max-w-lg text-xl text-white" style="--i: 5">{{ __('site.story.lead') }}</p>
    </x-hero>

    <section class="py-20 md:py-32">
        <div class="mx-auto max-w-6xl px-4">
            <x-frase class="max-w-5xl" :text="__('site.story.statement')" />
        </div>
    </section>

    <section class="bg-crema-scuro py-20 md:py-28">
        <div class="mx-auto grid max-w-6xl items-center gap-10 px-4 md:grid-cols-2 md:gap-16">
            <x-foto name="forno" :alt="__('site.story.tradition_alt')" sizes="(min-width: 768px) 50vw, 100vw" class="rounded-2xl" />
            <div>
                <h2 class="type-section">{{ __('site.story.tradition_title') }}</h2>
                <div class="mt-6 space-y-5 text-lg text-ink-soft">
                    <p>{{ __('site.story.tradition_1') }}</p>
                    <p>{{ __('site.story.tradition_2') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="pin-track bg-ink text-white">
        <div class="pin-stage py-20 md:py-28">
            <div class="mx-auto w-full max-w-6xl px-4">
                <h2 class="max-w-3xl type-section text-white">{{ __('site.story.dough_title') }}</h2>
                <p class="mt-5 max-w-xl text-lg text-stone-300">{{ __('site.story.dough_text') }}</p>
                <ul class="pin-facts mt-12 list-none border-t border-white/20 p-0 md:mt-16">
                    @foreach (__('site.story.facts') as [$titolo, $testo])
                        <li class="pin-fact flex flex-col gap-1 border-b border-white/20 py-6 md:flex-row md:items-baseline md:justify-between md:py-9">
                            <span class="pin-title font-display type-list font-extrabold tracking-tight">{{ $titolo }}</span>
                            <span class="text-lg text-stone-300 md:text-xl">{{ $testo }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="pin-bar mt-8 h-1 origin-left bg-pomodoro" aria-hidden="true"></div>
            </div>
        </div>
    </section>

    <x-hero name="ingredienti" :alt="__('site.story.end_alt')" height="min-h-[70svh]" position="object-[60%_50%]" :unveil="true">
        <h2 class="max-w-3xl type-band text-white">{{ __('site.story.end_title') }}</h2>
        <div class="mt-8 flex flex-wrap gap-3">
            <x-button :href="$pageUrl('menu')">{{ __('site.story.see_menu') }}</x-button>
            <x-button :href="$tel" variant="outline-light">{{ __('site.ui.call') }}</x-button>
        </div>
    </x-hero>
@endsection
