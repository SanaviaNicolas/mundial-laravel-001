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
                    <x-button :href="'https://www.google.com/maps/search/?api=1&query='.urlencode(config('site.address'))" variant="secondary" rel="noopener">{{ __('site.contact.map') }}</x-button>
                </div>
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
@endsection
