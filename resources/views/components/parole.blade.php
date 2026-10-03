{{-- Splits a text into words that rise one after the other (CSS only); the text stays plain for crawlers and screen readers. --}}
@props(['text', 'start' => 0])

@foreach (explode(' ', $text) as $i => $parola)<span class="parola"><span style="--i: {{ $start + $i }}">{{ $parola }}</span></span> @endforeach
