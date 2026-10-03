{{-- Language switcher: links to the same page in every locale. --}}
@php($alternates = \App\Support\Pages::alternates())

<ul {{ $attributes->class('flex list-none items-center gap-1 p-0') }} aria-label="{{ __('site.ui.language') }}">
    @foreach (config('site.locales') as $locale)
        <li>
            <a href="{{ $alternates[$locale] ?? '/' }}" hreflang="{{ $locale }}" lang="{{ $locale }}" @if ($locale === app()->getLocale()) aria-current="true" @endif class="inline-flex min-h-11 min-w-11 items-center justify-center rounded-full px-2 text-sm font-bold tracking-wide text-current no-underline aria-[current=true]:bg-current/15 hover:bg-current/10">{{ strtoupper($locale) }}</a>
        </li>
    @endforeach
</ul>
