@extends('layouts.app')

@section('title', __('site.contact.title'))
@section('description', __('site.contact.description', ['address' => config('site.address'), 'phone' => config('site.phone')]))
@section('header-overlay', true)

@section('content')
    <x-hero name="forno" :alt="__('site.contact.hero_alt')" height="min-h-[65svh]" :eager="true" position="object-[50%_60%]">
        <h1 class="type-page text-white">
            <x-parole :text="__('site.contact.h1')" />
        </h1>
        <p class="fade-in mt-6 text-xl text-white" style="--i: 4">{{ config('site.address') }}</p>
    </x-hero>

    <section class="py-20 md:py-28">
        <div class="mx-auto grid max-w-6xl gap-14 px-4 md:grid-cols-2 md:gap-16">
            <div>
                <h2 class="type-section">{{ __('site.contact.call_title') }}</h2>
                <p class="mt-4 max-w-md text-lg text-ink-soft">{{ __('site.contact.call_text') }}</p>
                <a href="{{ $tel }}" class="mt-6 inline-block font-display type-phone font-extrabold tracking-tight text-ink no-underline"><span class="link-line">{{ config('site.phone') }}</span></a>
                <div class="mt-8 flex flex-wrap gap-3">
                    <x-button :href="$tel">{{ __('site.ui.call') }}</x-button>
                    <x-button :href="$mapUrl" variant="secondary" :external="true">{{ __('site.contact.map') }}</x-button>
                </div>
                <h2 class="mt-14 text-2xl md:text-3xl">{{ __('site.contact.follow') }}</h2>
                <x-social class="mt-4" />
            </div>
            <div>
                <h2 class="type-section">{{ __('site.contact.hours_title') }}</h2>
                <table class="mt-6 w-full border-collapse text-left">
                    <caption class="sr-only">{{ __('site.contact.hours_caption') }}</caption>
                    @foreach (config('site.hours') as $riga)
                        <tr class="border-b border-ink/15 first:border-t">
                            <th scope="row" class="py-5 pr-4 font-display text-xl font-bold md:text-2xl">{{ __('site.days.'.$riga['days']) }}</th>
                            <td class="py-5 text-right text-xl md:text-2xl">{{ $riga['open'] ?? __('site.closed') }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>

    {{-- Static map made once from OpenStreetMap (public/mappa, see docs): no third-party request, no cookies. --}}
    <div class="mx-auto max-w-6xl px-4 pb-20 md:pb-28">
        <figure class="relative overflow-hidden rounded-2xl ring-1 ring-ink/10">
            <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="group block text-ink no-underline">
                <span class="relative block overflow-hidden">
                <picture>
                    <source type="image/avif" srcset="{{ asset('mappa/mappa-800.avif') }} 800w, {{ asset('mappa/mappa-1600.avif') }} 1600w" sizes="(min-width: 1152px) 1120px, 100vw">
                    <img src="{{ asset('mappa/mappa-1600.webp') }}" srcset="{{ asset('mappa/mappa-800.webp') }} 800w, {{ asset('mappa/mappa-1600.webp') }} 1600w" sizes="(min-width: 1152px) 1120px, 100vw" width="1600" height="900" alt="{{ __('site.contact.map_alt') }}" loading="lazy" decoding="async" class="aspect-[4/3] w-full object-cover transition-transform duration-[900ms] ease-brand group-hover:scale-[1.03] md:aspect-[16/9]">
                </picture>
                <svg class="absolute left-1/2 top-1/2 size-12 -translate-x-1/2 -translate-y-full text-pomodoro" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="currentColor" d="M12 1.5a8 8 0 0 0-8 8c0 5.6 6.6 11.9 7.3 12.6a1 1 0 0 0 1.4 0c.7-.7 7.3-7 7.3-12.6a8 8 0 0 0-8-8Z" />
                    <circle cx="12" cy="9.5" r="3" fill="#fff" />
                </svg>
                </span>
                <span class="flex flex-col bg-white px-5 py-4 sm:absolute sm:bottom-4 sm:left-4 sm:rounded-xl sm:ring-1 sm:ring-ink/10">
                    <span class="font-display text-lg font-extrabold">{{ config('app.name') }}</span>
                    <span class="text-sm text-ink-soft">{{ config('site.address') }}</span>
                    <span class="mt-2 inline-flex items-center gap-2 text-sm font-semibold"><span class="link-line">{{ __('site.contact.map_open') }}</span><svg class="size-4 transition-transform duration-500 ease-brand group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></span>
                    <span class="sr-only"> ({{ __('site.ui.new_tab') }})</span>
                </span>
            </a>
            <figcaption class="absolute right-2 top-2 rounded bg-crema/85 px-2 py-0.5 text-xs text-ink-soft">© <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" class="text-ink-soft underline">OpenStreetMap</a></figcaption>
        </figure>
    </div>
@endsection