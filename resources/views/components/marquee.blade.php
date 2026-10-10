{{-- Decorative band of words that loops on its own (CSS only, static with reduced motion). --}}
@props(['words'])

<div {{ $attributes->class('overflow-hidden') }} aria-hidden="true">
    <div class="marquee-track flex w-max items-center whitespace-nowrap font-display font-bold leading-none tracking-tight">
        @foreach (range(1, 2) as $copia)
            @foreach ($words as $parola)
                <span class="px-5 md:px-8">{{ $parola }}</span>
                <span class="size-2 shrink-0 rounded-full bg-pomodoro md:size-2.5"></span>
            @endforeach
        @endforeach
    </div>
</div>
