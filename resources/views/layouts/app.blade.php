@php
    $pagine = collect(['menu', 'story', 'contact'])->mapWithKeys(fn ($page) => [__('site.nav.'.$page) => $pageUrl($page)]);
    $overlay = View::hasSection('header-overlay');
    $alternates = \App\Support\Pages::alternates();
    $ogLocales = ['it' => 'it_IT', 'en' => 'en_GB'];
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
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:title" content="@yield('title')">
        <meta property="og:description" content="@yield('description')">
        @if ($alternates)
            <meta property="og:url" content="{{ url($alternates[app()->getLocale()]) }}">
        @endif
        <meta property="og:locale" content="{{ $ogLocales[app()->getLocale()] }}">
        @foreach (array_diff_key($ogLocales, [app()->getLocale() => true]) as $ogLocale)
            <meta property="og:locale:alternate" content="{{ $ogLocale }}">
        @endforeach
        <meta property="og:image" content="{{ asset('brand/og-image.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ config('app.name') }} — Pizza • Cucina">
        <meta name="twitter:card" content="summary_large_image">
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
        <header @class(['group/h z-30', 'absolute inset-x-0 top-0 text-white transition-colors duration-300 js:fixed data-[scrolled]:bg-crema/90 data-[scrolled]:text-ink border-b border-transparent data-[scrolled]:border-ink/10 data-[scrolled]:backdrop-blur' => $overlay, 'bg-crema text-ink' => ! $overlay]) @if ($overlay) data-overlay @endif>
            @if ($chiusura)
                <p class="bg-oro px-4 py-2 text-center text-sm font-semibold text-ink" role="status">{{ $chiusura }}</p>
            @endif
            <div class="mx-auto flex h-[5.25rem] max-w-6xl items-center justify-between gap-4 px-4 transition-[height] duration-300 group-data-[scrolled]/h:h-16 md:h-24 md:group-data-[scrolled]/h:h-[4.5rem]">
                <x-logo size="header" />
                <nav aria-label="{{ __('site.ui.main_nav') }}" class="max-md:hidden">
                    <ul class="flex list-none gap-1 p-0">
                        @foreach ($pagine as $nome => $url)
                            <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center rounded-full px-4 font-medium text-current no-underline transition-colors duration-300 ease-brand hover:bg-current/10 aria-[current=page]:bg-current/15" @if (request()->is(ltrim($url, '/'))) aria-current="page" @endif>{{ $nome }}</a></li>
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

        <footer class="border-t border-white/10 bg-ink pb-24 pt-12 text-stone-300 md:pb-0 md:pt-16">
            <div class="mx-auto max-w-6xl px-4">
                <div class="grid gap-10 md:grid-cols-12 md:gap-8">
                    <div class="md:col-span-3">
                        <x-logo-mark class="h-16 text-white md:h-20" />
                        <x-social class="mt-6 text-white" />
                    </div>
                    <div class="md:col-span-3">
                        <p class="font-display text-sm font-bold uppercase tracking-widest text-white">{{ __('site.footer.where') }}</p>
                        <p class="mt-4">{{ config('site.address') }}</p>
                        <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="mt-1 inline-flex min-h-11 items-center text-white no-underline transition-opacity duration-300 ease-brand hover:opacity-70">{{ __('site.contact.map') }}<span class="sr-only"> ({{ __('site.ui.new_tab') }})</span></a>
                        <p class="mt-6 font-display text-sm font-bold uppercase tracking-widest text-white">{{ __('site.footer.call') }}</p>
                        <a href="{{ $tel }}" class="mt-2 inline-flex min-h-11 items-center font-display text-2xl font-extrabold tracking-tight text-white no-underline transition-opacity duration-300 ease-brand hover:opacity-70">{{ config('site.phone') }}</a>
                    </div>
                    <div class="md:col-span-4">
                        <p class="font-display text-sm font-bold uppercase tracking-widest text-white">{{ __('site.contact.hours_title') }}</p>
                        @if ($chiusura)
                            <p class="mt-4 text-sm font-semibold text-oro">{{ $chiusura }}</p>
                        @endif
                        <dl class="mt-4 space-y-2">
                            @foreach (config('site.hours') as $riga)
                                <div class="flex justify-between gap-4 border-b border-white/10 pb-2">
                                    <dt>{{ __('site.days.'.$riga['days']) }}</dt>
                                    <dd class="whitespace-nowrap text-white">{{ $riga['open'] ?? __('site.closed') }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                    <nav aria-label="{{ __('site.footer.pages') }}" class="md:col-span-2 md:justify-self-end">
                        <p class="font-display text-sm font-bold uppercase tracking-widest text-white">{{ __('site.footer.pages') }}</p>
                        <ul class="mt-2 list-none p-0">
                            @foreach ($pagine as $nome => $url)
                                <li><a href="{{ $url }}" class="inline-flex min-h-11 items-center text-white no-underline transition-opacity duration-300 ease-brand hover:opacity-70">{{ $nome }}</a></li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div class="mt-12 flex flex-col gap-2 border-t border-white/10 py-5 text-sm md:flex-row md:items-center md:justify-between">
                    <p>© {{ now()->year }} {{ config('site.company') ?? config('app.name') }}@if (config('site.vat')) · {{ __('site.footer.vat') }} {{ config('site.vat') }}@endif · {{ __('site.footer.rights') }}</p>
                    <ul class="flex list-none flex-wrap gap-x-6 p-0">
                        @foreach (['privacy', 'cookie'] as $legale)
                            <li><a href="{{ $pageUrl($legale) }}" class="inline-flex min-h-11 items-center text-white no-underline transition-opacity duration-300 ease-brand hover:opacity-70">{{ __("site.legal.$legale.h1") }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </footer>

        <div class="fixed inset-x-4 bottom-4 z-10 md:hidden">
            <x-button :href="$tel" class="w-full outline-2 outline-white">{{ __('site.ui.call_now') }}</x-button>
        </div>
    </body>
</html>
