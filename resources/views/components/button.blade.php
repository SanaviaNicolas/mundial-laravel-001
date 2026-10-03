@props(['href', 'variant' => 'primary'])

<a href="{{ $href }}" {{ $attributes->class([
    'inline-flex min-h-12 items-center justify-center rounded-full border-2 px-6 font-display font-semibold no-underline',
    'border-transparent bg-pomodoro text-white hover:bg-pomodoro-scuro' => $variant === 'primary',
    'border-ink text-ink hover:bg-ink hover:text-white' => $variant === 'secondary',
    'border-white text-white hover:bg-white hover:text-ink' => $variant === 'outline-light',
]) }}>{{ $slot }}</a>
