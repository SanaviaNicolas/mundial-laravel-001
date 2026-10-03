{{-- Provisional photo: shows a labelled placeholder until a real `src` is passed. --}}
@props(['ratio' => 'aspect-[4/3]', 'src' => null, 'alt' => '', 'eager' => false])

@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" loading="{{ $eager ? 'eager' : 'lazy' }}" {{ $attributes->class([$ratio, 'w-full object-cover']) }}>
@else
    <div {{ $attributes->class([$ratio, 'grid w-full place-items-center bg-gradient-to-br from-[#e8dfca] to-[#d9cdb2]']) }} role="img" aria-label="{{ $alt }}">
        <span class="rounded-full bg-ink px-3 py-1 text-xs font-semibold text-white">Foto provvisoria</span>
    </div>
@endif
