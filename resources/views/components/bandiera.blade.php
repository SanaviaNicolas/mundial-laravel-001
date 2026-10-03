{{-- Round flag icon (inline SVG) for the language switcher. --}}
@props(['lingua'])

<svg {{ $attributes->class('size-5 shrink-0 rounded-full') }} viewBox="0 0 24 24" aria-hidden="true">
    @if ($lingua === 'it')
        <rect width="8" height="24" fill="#009246" />
        <rect x="8" width="8" height="24" fill="#fff" />
        <rect x="16" width="8" height="24" fill="#ce2b37" />
    @else
        <rect width="24" height="24" fill="#012169" />
        <path d="M0 0l24 24M24 0L0 24" stroke="#fff" stroke-width="5" />
        <path d="M0 0l24 24M24 0L0 24" stroke="#c8102e" stroke-width="2" />
        <path d="M12 0v24M0 12h24" stroke="#fff" stroke-width="8" />
        <path d="M12 0v24M0 12h24" stroke="#c8102e" stroke-width="4.5" />
    @endif
</svg>
