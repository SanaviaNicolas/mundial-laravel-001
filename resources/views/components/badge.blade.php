{{-- Static highlight badge for a menu item: `mese` (pizza of the month) or `scelta` (most chosen). --}}
@props(['tipo'])

@php
    $tipi = [
        'mese' => ['Pizza del mese', 'bg-pomodoro text-white'],
        'scelta' => ['La più scelta', 'bg-blu text-ink'],
    ];
    [$etichetta, $classi] = $tipi[$tipo];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold tracking-wide', $classi]) }}>
    @if ($tipo === 'mese')
        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="m10 1.5 2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 14.8l-5.2 2.8 1-5.8L1.5 7.7l5.9-.9z" /></svg>
    @else
        <svg class="size-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 17.5 2.9 10.6a4.3 4.3 0 0 1 6.1-6.1l1 1 1-1a4.3 4.3 0 0 1 6.1 6.1z" /></svg>
    @endif
    {{ $etichetta }}
</span>
