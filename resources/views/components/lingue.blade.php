{{-- Language switcher: links to the same page in every locale. --}}
@php($alternates = \App\Support\Pages::alternates())

<ul {{ $attributes->class('flex list-none items-center gap-1 p-0') }} aria-label="{{ __('site.ui.language') }}">
    @foreach (config('app.locales') as $locale)
        <li>
            <a href="{{ $alternates[$locale] ?? '/' }}" hreflang="{{ $locale }}" lang="{{ $locale }}" @if ($locale === app()->getLocale()) aria-current="true" @endif class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full px-3 text-sm font-bold tracking-wide text-current no-underline aria-[current=true]:bg-current/15 hover:bg-current/10"><x-bandiera :lingua="$locale" />{{ strtoupper($locale) }}</a>
        </li>
    @endforeach
</ul>
