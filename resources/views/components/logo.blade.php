{{-- Logo linking to the home page; inherits the text color (white over photos, black on light). `header` size shrinks once the page is scrolled. --}}
@props(['size' => 'panel'])

<a href="{{ \App\Support\Pages::url('home') }}" class="inline-flex text-current" aria-label="{{ config('app.name') }} — Home">
    <x-logo-mark @class(['h-14' => $size === 'panel', 'h-16 transition-[height] duration-300 md:h-20 group-data-[scrolled]/h:h-12 md:group-data-[scrolled]/h:h-14' => $size === 'header']) aria-hidden="true" role="presentation" />
</a>
