{{--
    Full-bleed photo section with parallax and a dark scrim; content goes at the bottom.
    `fade` blends the bottom into the next dark section, `unveil` opens the photo as it scrolls in.
    The eager (LCP) hero also reacts to the mouse. Pages using it set `header-overlay`.
--}}
@props(['name', 'alt', 'height' => 'min-h-[100svh]', 'eager' => false, 'fade' => false, 'unveil' => false, 'sizes' => '100vw', 'position' => 'object-center', 'labelClass' => 'self-start justify-self-end mr-4 mt-32 md:mt-28'])

<section {{ $attributes->class(['relative flex flex-col justify-end overflow-hidden bg-ink text-white', 'unveil' => $unveil, $height]) }} @if ($eager) data-mouse @endif>
    <div class="parallax absolute inset-x-0 -bottom-[9%] -top-[9%]">
        <div class="mouse absolute inset-0">
            <x-foto :name="$name" ratio="" :alt="$alt" :eager="$eager" :dark="true" :label-class="$labelClass" :sizes="$sizes" class="{{ $position }} {{ $eager ? 'kenburns' : '' }} absolute inset-0 h-full" />
        </div>
    </div>
    <div @class(['absolute inset-0 bg-gradient-to-b from-black/65 via-black/25', 'to-ink' => $fade, 'to-black/75' => ! $fade]) aria-hidden="true"></div>
    <div class="relative mx-auto w-full max-w-6xl px-4 pb-24 pt-40 md:pb-24">
        {{ $slot }}
    </div>
</section>
