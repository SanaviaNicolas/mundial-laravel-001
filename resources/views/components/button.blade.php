@props(['href', 'variant' => 'primary'])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex min-h-12 items-center justify-center rounded-full border-2 px-6 font-display font-semibold no-underline',
    'border-transparent bg-pomodoro text-white hover:bg-pomodoro-scuro' => $variant === 'primary',
    'border-blu-scuro text-blu-scuro hover:bg-blu-scuro hover:text-white' => $variant === 'secondary',
]) }}>{{ $slot }}</a>
