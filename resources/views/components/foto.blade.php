{{-- Provisional photo: shows a labelled placeholder until a real `src` is passed. --}}
@props(['ratio' => 'aspect-[4/3]', 'src' => null, 'alt' => '', 'eager' => false])

@if ($src)
    <img src="{{ $src }}" alt="{{ $alt }}" loading="{{ $eager ? 'eager' : 'lazy' }}" {{ $attributes->class([$ratio, 'w-full object-cover']) }}>
@else
    <div {{ $attributes->class([$ratio, 'relative grid w-full place-items-center bg-gradient-to-br from-[#e8dfca] to-[#d9cdb2]']) }} role="img" aria-label="{{ $alt }}">
        <span class="absolute left-2 top-2 rounded bg-ink px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-widest text-white">Foto provvisoria</span>
    </div>
@endif
