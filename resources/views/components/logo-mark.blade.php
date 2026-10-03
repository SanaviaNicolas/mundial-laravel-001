{{-- Logo (vector, public/brand/logo.svg): the black ink follows the current text color, the blue is fixed. --}}
<svg viewBox="0 0 1130 855" role="img" aria-label="{{ config('app.name') }} — Pizza • Cucina" {{ $attributes->class('aspect-[1130/855] w-auto') }}>
    <use href="{{ asset('brand/logo.svg') }}#logo" width="1130" height="855" />
</svg>
