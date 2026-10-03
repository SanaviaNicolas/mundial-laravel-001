{{-- Provisional photo: shows a labelled placeholder until a real `src` is passed. --}}
@props(['ratio' => 'aspect-[4/3]', 'src' => null, 'alt' => '', 'eager' => false, 'dark' => false, 'labelClass' => ''])

@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" loading="{{ $eager ? 'eager' : 'lazy' }}" @if ($eager) fetchpriority="high" @endif {{ $attributes->class([$ratio, 'w-full object-cover']) }}>
@else
    <div {{ $attributes->class([$ratio, 'grid w-full place-items-center bg-gradient-to-br', 'from-[#e8dfca] to-[#d9cdb2]' => ! $dark, 'from-[#5a3720] via-[#2b1b12] to-[#14100d]' => $dark]) }} role="img" aria-label="{{ $alt }}">
        <span class="{{ $labelClass }} rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/30">Foto provvisoria</span>
    </div>
@endif
