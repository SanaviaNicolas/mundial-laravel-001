{{-- Pill button. On hover the label rolls up and a copy (CSS generated, hidden from assistive tech) rolls in. --}}
@props(['href', 'variant' => 'primary'])

<a href="{{ $href }}" {{ $attributes->class([
    'btn inline-flex min-h-12 items-center justify-center rounded-full border-2 px-6 font-display font-semibold no-underline transition-colors duration-300 ease-brand',
    'border-transparent bg-pomodoro text-white hover:bg-pomodoro-scuro' => $variant === 'primary',
    'border-ink text-ink hover:bg-ink hover:text-white' => $variant === 'secondary',
    'border-white text-white hover:bg-white hover:text-ink' => $variant === 'outline-light',
]) }}><span class="btn-text" data-label="{{ trim($slot) }}"><span>{{ $slot }}</span></span></a>
