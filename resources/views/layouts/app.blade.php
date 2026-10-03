@php
    $pagine = ['Menù' => '/menu', 'La storia' => '/la-nostra-storia', 'Contatti' => '/contatti'];
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
        <header class="bg-crema">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-x-4 px-4 py-3">
                <x-logo />
                <x-button :href="$tel" class="hidden md:order-3 md:inline-flex">Chiama</x-button>
                <nav aria-label="Principale" class="order-last w-full md:order-2 md:w-auto">
                    <ul class="-mx-3 flex list-none gap-1 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li><a href="{{ $url }}" @class(['inline-flex min-h-11 items-center px-3 font-medium no-underline', 'text-ink' => ! request()->is(ltrim($url, '/')), 'text-pomodoro-scuro underline decoration-2 underline-offset-8' => request()->is(ltrim($url, '/'))]) @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif>{{ $nome }}</a></li>
                        @endforeach
                    </ul>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="overflow-hidden bg-ink pb-20 pt-12 text-stone-200 md:pb-0">
            <div class="mx-auto max-w-6xl px-4">
                <p class="font-display text-lg font-bold text-white">{{ config('app.name') }}</p>
                <ul class="mt-4 flex list-none flex-wrap gap-x-5 p-0">
                    @foreach ($pagine as $nome => $url)
                        <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center text-white">{{ $nome }}</a></li>
                    @endforeach
                </ul>
                <p class="mt-2">{{ config('site.address') }} · Tel. {{ config('site.phone') }}@if (config('site.vat')) · P.IVA {{ config('site.vat') }}@endif</p>
            </div>
            <p class="mt-6 select-none whitespace-nowrap text-center font-display text-[15.5vw] font-extrabold leading-[0.8] tracking-tighter text-white/10 md:text-[14rem]" aria-hidden="true">Visciano 82</p>
        </footer>

        <div class="fixed inset-x-4 bottom-4 z-10 md:hidden">
            <x-button :href="$tel" class="w-full shadow-xl">Chiama ora</x-button>
        </div>
    </body>
</html>
