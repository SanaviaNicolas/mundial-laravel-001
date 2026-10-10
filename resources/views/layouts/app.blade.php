@php
    $pagine = collect(['menu', 'story', 'contact'])->mapWithKeys(fn ($page) => [__('site.nav.'.$page) => $pageUrl($page)]);
    $overlay = View::hasSection('header-overlay');
    $alternates = \App\Support\Pages::alternates();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"@if (\App\Support\Pages::current() === 'home') data-home @endif>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('brand/logo.svg') }}">
        <meta name="description" content="@yield('description')">
        @if ($alternates)
            <link rel="canonical" href="{{ url($alternates[app()->getLocale()]) }}">
            @foreach ($alternates as $locale => $path)
                <link rel="alternate" hreflang="{{ $locale }}" href="{{ url($path) }}">
            @endforeach
            <link rel="alternate" hreflang="x-default" href="{{ url($alternates[config('app.locales')[0]]) }}">
        @endif
        @unless (app()->isProduction())
            <meta name="robots" content="noindex, nofollow">
        @endunless
        <script>try{if(document.documentElement.hasAttribute('data-home')&&!sessionStorage.getItem('intro')){document.documentElement.classList.add('intro');sessionStorage.setItem('intro','1')}}catch(e){}</script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen">
        <div class="progress" aria-hidden="true"></div>
        <div class="curtain" aria-hidden="true"><span><x-logo-mark class="h-[min(55svh,22rem)]" /></span></div>
        <a href="#contenuto" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-full focus:bg-white focus:px-4 focus:py-3 focus:text-ink">{{ __('site.ui.skip') }}</a>
        <header @class(['group/h z-30', 'absolute inset-x-0 top-0 text-white transition-colors duration-300 js:fixed data-[scrolled]:bg-crema/90 data-[scrolled]:text-ink data-[scrolled]:shadow-sm data-[scrolled]:backdrop-blur' => $overlay, 'bg-crema text-ink' => ! $overlay]) @if ($overlay) data-overlay @endif>
            <div class="mx-auto flex h-[5.25rem] max-w-6xl items-center justify-between gap-4 px-4 transition-[height] duration-300 group-data-[scrolled]/h:h-16 md:h-24 md:group-data-[scrolled]/h:h-[4.5rem]">
                <x-logo size="header" />
                <nav aria-label="{{ __('site.ui.main_nav') }}" class="max-md:hidden">
                    <ul class="flex list-none gap-1 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center px-3 font-medium text-current no-underline" @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif><span class="link-line">{{ $nome }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>
                <x-lingue class="max-md:hidden" />
                <x-button :href="$tel" class="max-md:hidden">{{ __('site.ui.call') }}</x-button>
                <button type="button" popovertarget="menu-principale" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full md:hidden" aria-label="{{ __('site.ui.open_menu') }}">
                    <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 8.5h16M4 15.5h16" /></svg>
                </button>
            </div>
        </header>

        <x-pannello id="menu-principale" :etichetta="__('site.ui.menu_panel')">
            <div class="mx-auto flex min-h-full max-w-6xl flex-col px-4 pb-8">
                <div class="flex h-16 items-center justify-between">
                    <x-logo />
                    <button type="button" popovertarget="menu-principale" popovertargetaction="hide" class="-mr-2 inline-flex size-12 items-center justify-center rounded-full" aria-label="{{ __('site.ui.close_menu') }}">
                        <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m5 5 14 14M19 5 5 19" /></svg>
                    </button>
                </div>
                <nav aria-label="{{ __('site.ui.menu_panel') }}" class="mt-6">
                    <ul class="list-none border-t border-white/15 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li class="border-b border-white/15" style="--i: {{ $loop->index }}"><a href="{{ $url }}" class="block py-5 font-display text-[2.5rem] font-extrabold leading-none tracking-tight text-white no-underline" @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif>{{ $nome }}</a></li>
                        @endforeach
                    </ul>
                </nav>
                <div class="mt-auto pt-10">
                    <p class="text-stone-300">{{ config('site.address') }}</p>
                    <a href="{{ $tel }}" class="mt-1 block font-display text-3xl font-extrabold tracking-tight text-white no-underline">{{ config('site.phone') }}</a>
                    <x-lingue class="mt-6" />
                    <x-button :href="$tel" class="mt-4 w-full">{{ __('site.ui.call_now') }}</x-button>
                </div>
            </div>
        </x-pannello>

        <main id="contenuto">
            @yield('content')
        </main>

        <footer class="overflow-hidden border-t border-white/10 bg-ink pb-24 pt-12 text-stone-200 md:pb-0">
            <div class="mx-auto max-w-6xl px-4">
                <x-logo-mark class="h-24 text-white" />
                <ul class="mt-4 flex list-none flex-wrap gap-x-5 p-0">
                    @foreach ($pagine as $nome => $url)
                        <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center text-white">{{ $nome }}</a></li>
                    @endforeach
                </ul>
                <p class="mt-2">{{ config('site.address') }} · {{ __('site.footer.tel') }} {{ config('site.phone') }}@if (config('site.vat')) · {{ __('site.footer.vat') }} {{ config('site.vat') }}@endif</p>
            </div>
            <p class="ghost-text mt-10 select-none whitespace-nowrap text-center font-display text-[15.5vw] font-extrabold leading-[0.78] tracking-tighter [--ghost:rgb(255_255_255/0.08)] md:text-[13rem]" aria-hidden="true">Visciano 82</p>
        </footer>

        <div class="fixed inset-x-4 bottom-4 z-10 md:hidden">
            <x-button :href="$tel" class="w-full shadow-xl">{{ __('site.ui.call_now') }}</x-button>
        </div>
    </body>
</html>
