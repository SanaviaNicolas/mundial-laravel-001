{{--
    Pill button. On hover the label rolls up and a copy (CSS generated, hidden from assistive tech) rolls in.
    `external` opens the link in a new tab and says so to screen readers.
--}}
@props(['href', 'variant' => 'primary', 'external' => false])

<a href="{{ $href }}" @if ($external) target="_blank" rel="noopener" @endif {{ $attributes->class([
    'btn inline-flex min-h-12 items-center justify-center rounded-full border-2 px-6 font-display font-semibold no-underline transition-colors duration-300 ease-brand',
    'border-transparent bg-pomodoro text-white hover:bg-pomodoro-scuro' => $variant === 'primary',
    'border-ink text-ink hover:bg-ink hover:text-white' => $variant === 'secondary',
    'border-white text-white hover:bg-white hover:text-ink' => $variant === 'outline-light',
    'border-transparent bg-white text-pomodoro-scuro hover:bg-crema-scuro' => $variant === 'light',
]) }}><span class="btn-text" data-label="{{ trim($slot) }}"><span>{{ $slot }}</span></span>@if ($external)<span class="sr-only"> ({{ __('site.ui.new_tab') }})</span>@endif</a>
