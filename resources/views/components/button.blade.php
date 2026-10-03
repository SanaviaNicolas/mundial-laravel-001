@props(['href', 'variant' => 'primary'])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex min-h-12 items-center justify-center rounded-full border-2 px-6 font-display font-semibold no-underline',
    'border-transparent bg-pomodoro text-white hover:bg-pomodoro-scuro' => $variant === 'primary',
    'border-blu-scuro text-blu-scuro hover:bg-blu-scuro hover:text-white' => $variant === 'secondary',
    'border-transparent bg-crema text-pomodoro-scuro hover:bg-white' => $variant === 'light',
    'border-white text-white hover:bg-white hover:text-blu-scuro' => $variant === 'outline-light',
]) }}>{{ $slot }}</a>
