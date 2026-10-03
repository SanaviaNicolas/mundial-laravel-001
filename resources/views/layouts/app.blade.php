@php
    $pagine = ['Menù' => '/menu', 'La storia' => '/la-nostra-storia', 'Contatti' => '/contatti'];
    $overlay = View::hasSection('header-overlay');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <meta name="description" content="@yield('description')">
        @unless (app()->isProduction())
            <meta name="robots" content="noindex, nofollow">
        @endunless
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen">
        <header @class(['z-30', 'absolute inset-x-0 top-0 text-white transition-colors duration-300 js:fixed data-[scrolled]:bg-crema/90 data-[scrolled]:text-ink data-[scrolled]:shadow-sm data-[scrolled]:backdrop-blur' => $overlay, 'bg-crema text-ink' => ! $overlay]) @if ($overlay) data-overlay @endif>
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 md:h-[4.5rem]">
                <x-logo />
                <nav aria-label="Principale" class="max-md:hidden">
                    <ul class="flex list-none gap-1 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li><a href="{{ $url }}" @class(['inline-flex min-h-11 items-center px-3 font-medium text-current no-underline', 'underline decoration-pomodoro decoration-2 underline-offset-8' => request()->is(ltrim($url, '/'))]) @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif>{{ $nome }}</a></li>
                        @endforeach
                    </ul>
                </nav>
                <x-button :href="$tel" class="max-md:hidden">Chiama</x-button>
                <button type="button" popovertarget="menu-principale" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full md:hidden" aria-label="Apri il menu">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 8.5h16M4 15.5h16" /></svg>
                </button>
            </div>
        </header>

        <x-pannello id="menu-principale" etichetta="Menu">
            <div class="mx-auto flex min-h-full max-w-6xl flex-col px-4 pb-8">
                <div class="flex h-16 items-center justify-between">
                    <x-logo />
                    <button type="button" popovertarget="menu-principale" popovertargetaction="hide" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full" aria-label="Chiudi il menu">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 5 14 14M19 5 5 19" /></svg>
                    </button>
                </div>
                <nav aria-label="Menu" class="mt-6">
                    <ul class="list-none border-t border-white/15 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li class="border-b border-white/15"><a href="{{ $url }}" class="block py-5 font-display text-[2.5rem] font-extrabold leading-none tracking-tight text-white no-underline" @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif>{{ $nome }}</a></li>
                        @endforeach
                    </ul>
                </nav>
                <div class="mt-auto pt-10">
                    <p class="text-stone-300">{{ config('site.address') }}</p>
                    <a href="{{ $tel }}" class="mt-1 block font-display text-3xl font-extrabold tracking-tight text-white no-underline">{{ config('site.phone') }}</a>
                    <x-button :href="$tel" class="mt-6 w-full">Chiama ora</x-button>
                </div>
            </div>
        </x-pannello>

        <main>
            @yield('content')
        </main>

        <footer class="border-t border-white/10 bg-ink pb-24 pt-12 text-stone-200 md:pb-12">
            <div class="mx-auto max-w-6xl px-4">
                <p class="font-display text-xl font-bold text-white">{{ config('app.name') }}</p>
                <ul class="mt-4 flex list-none flex-wrap gap-x-5 p-0">
                    @foreach ($pagine as $nome => $url)
                        <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center text-white">{{ $nome }}</a></li>
                    @endforeach
                </ul>
                <p class="mt-2">{{ config('site.address') }} · Tel. {{ config('site.phone') }}@if (config('site.vat')) · P.IVA {{ config('site.vat') }}@endif</p>
            </div>
        </footer>

        <div class="fixed inset-x-4 bottom-4 z-10 md:hidden">
            <x-button :href="$tel" class="w-full shadow-xl">Chiama ora</x-button>
        </div>
    </body>
</html>
