{{-- Big statement whose words light up while it scrolls into view (CSS only, static without support). --}}
@props(['text'])

<p {{ $attributes->class('font-display text-[clamp(2rem,6vw,4.75rem)] font-extrabold leading-[1.08] tracking-tight') }}>
    @foreach (explode(' ', $text) as $parola)
        <span class="lit-word">{{ $parola }}</span>
    @endforeach
</p>
