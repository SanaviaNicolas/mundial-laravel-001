{{--
    Photo. Real images are looked up as public/images/{name}-{640,1280,1920,2560}.{avif,webp};
    until they exist, a labelled "Foto provvisoria" placeholder is shown. Replace a photo by
    dropping files with the same name: no code changes needed.
--}}
@props(['name' => null, 'ratio' => 'aspect-[4/3]', 'alt' => '', 'eager' => false, 'dark' => false, 'labelClass' => '', 'sizes' => '100vw'])

@php
    $widths = [640, 1280, 1920, 2560];
    $file = fn (int $width, string $ext) => "images/{$name}-{$width}.{$ext}";
    $exists = fn (string $ext) => $name && file_exists(public_path($file(640, $ext)));
    $srcset = fn (string $ext) => collect($widths)->map(fn ($w) => asset($file($w, $ext))." {$w}w")->implode(', ');
    $size = $exists('webp') && file_exists(public_path($file(1280, 'webp'))) ? @getimagesize(public_path($file(1280, 'webp'))) : false;
@endphp

@if ($exists('webp'))
    <picture>
        @if ($exists('avif'))
            <source type="image/avif" srcset="{{ $srcset('avif') }}" sizes="{{ $sizes }}">
        @endif
        <source type="image/webp" srcset="{{ $srcset('webp') }}" sizes="{{ $sizes }}">
        <img src="{{ asset($file(1280, 'webp')) }}" alt="{{ $alt }}" @if ($size) width="{{ $size[0] }}" height="{{ $size[1] }}" @endif loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async" @if ($eager) fetchpriority="high" @endif {{ $attributes->class([$ratio, 'w-full object-cover']) }}>
    </picture>
@else
    <div {{ $attributes->class([$ratio, 'grid w-full place-items-center bg-gradient-to-br', 'from-[#e8dfca] to-[#d9cdb2]' => ! $dark, 'from-[#5a3720] via-[#2b1b12] to-[#14100d]' => $dark]) }} @if ($alt !== '') role="img" aria-label="{{ $alt }}" @else aria-hidden="true" @endif>
        <span class="{{ $labelClass }} rounded-full bg-black/60 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/30">Foto provvisoria</span>
    </div>
@endif
