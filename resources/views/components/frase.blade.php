{{-- Big statement whose words light up while it scrolls into view (CSS only, static without support). --}}
@props(['text'])

<p {{ $attributes->class('font-display type-statement font-extrabold tracking-tight') }}>
    @foreach (explode(' ', $text) as $parola)
        <span class="lit-word">{{ $parola }}</span>
    @endforeach
</p>
