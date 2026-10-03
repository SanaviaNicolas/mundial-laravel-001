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
    <body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
        <main class="mx-auto max-w-3xl px-4 py-12">
            @yield('content')
        </main>
    </body>
</html>
