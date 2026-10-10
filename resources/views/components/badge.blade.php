{{-- Highlight badge for a menu item, from its tag (slug picks colour and icon, the label is the tag name). --}}
@props(['slug', 'nome'])

@php
    $classi = [
        'pizza-del-mese' => 'bg-pomodoro text-white',
        'la-piu-scelta' => 'bg-blu text-ink',
        'novita' => 'bg-ink text-white',
        'stagionale' => 'bg-crema-scuro text-ink ring-1 ring-inset ring-ink/20',
    ][$slug];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold tracking-wide', $classi]) }}>
    <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        @switch($slug)
            @case('pizza-del-mese')
                <path d="m10 1.5 2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 14.8l-5.2 2.8 1-5.8L1.5 7.7l5.9-.9z" />
                @break
            @case('la-piu-scelta')
                <path d="M10 17.5 2.9 10.6a4.3 4.3 0 0 1 6.1-6.1l1 1 1-1a4.3 4.3 0 0 1 6.1 6.1z" />
                @break
            @case('novita')
                <path d="M10 1.5c.6 4.2 2.3 5.9 6.5 6.5v1c-4.2.6-5.9 2.3-6.5 6.5h-1C8.4 11.3 6.7 9.6 2.5 9V8c4.2-.6 5.9-2.3 6.5-6.5z" transform="translate(.5 1.5)" />
                @break
            @default
                <path d="M16.5 3.5c-7 0-12 3.5-12 9.5 0 1.2.3 2.4.8 3.5C7 12 10 9.5 13 8.5c-3 1.5-5.5 4-6.8 8.5 1 .3 1.9.5 2.8.5 6 0 7.5-6 7.5-14z" />
        @endswitch
    </svg>
    {{ $nome }}
</span>
