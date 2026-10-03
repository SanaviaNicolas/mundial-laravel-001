{{-- Decorative band of big words moving with the scroll. `ghost` draws the letters as a faint fill, `reverse` flips the direction. --}}
@props(['words', 'ghost' => false, 'reverse' => false])

<div {{ $attributes->class(['marquee overflow-hidden', 'marquee-rev' => $reverse]) }} aria-hidden="true">
    <div class="marquee-track flex w-max items-center gap-8 whitespace-nowrap font-display font-extrabold leading-none tracking-tight md:gap-12">
        @foreach (range(1, 2) as $copia)
            @foreach ($words as $parola)
                <span @class(['ghost-text' => $ghost])>{{ $parola }}</span>
                <span class="size-3 shrink-0 rounded-full bg-current md:size-5"></span>
            @endforeach
        @endforeach
    </div>
</div>
