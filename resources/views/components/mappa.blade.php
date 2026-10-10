{{--
    Static map made once from OpenStreetMap (public/mappa, see docs/design): no third-party request,
    no cookies. The whole map opens the restaurant's Google Maps place in a new tab.
    `scheda` adds the card with name, address and "Open in Google Maps".
--}}
@props(['scheda' => true, 'sizes' => '(min-width: 1152px) 1120px, 100vw'])

<figure {{ $attributes->class('relative overflow-hidden rounded-2xl ring-1 ring-ink/10') }}>
    <a href="{{ config('site.map_url') }}" target="_blank" rel="noopener" class="group block text-ink no-underline">
        <span class="relative block overflow-hidden">
            <picture>
                <source type="image/avif" srcset="{{ asset('mappa/mappa-800.avif') }} 800w, {{ asset('mappa/mappa-1600.avif') }} 1600w" sizes="{{ $sizes }}">
                <img src="{{ asset('mappa/mappa-1600.webp') }}" srcset="{{ asset('mappa/mappa-800.webp') }} 800w, {{ asset('mappa/mappa-1600.webp') }} 1600w" sizes="{{ $sizes }}" width="1600" height="900" alt="{{ __('site.contact.map_alt') }}" loading="lazy" decoding="async" class="aspect-[4/3] w-full object-cover transition-transform duration-[900ms] ease-brand group-hover:scale-[1.03] md:aspect-[16/9]">
            </picture>
            <svg class="absolute left-1/2 top-1/2 size-12 -translate-x-1/2 -translate-y-full text-pomodoro" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M12 1.5a8 8 0 0 0-8 8c0 5.6 6.6 11.9 7.3 12.6a1 1 0 0 0 1.4 0c.7-.7 7.3-7 7.3-12.6a8 8 0 0 0-8-8Z" />
                <circle cx="12" cy="9.5" r="3" fill="#fff" />
            </svg>
        </span>
        @if ($scheda)
            <span class="flex flex-col bg-white px-5 py-4 sm:absolute sm:bottom-4 sm:left-4 sm:rounded-xl sm:ring-1 sm:ring-ink/10">
                <span class="font-display text-lg font-extrabold">{{ config('app.name') }}</span>
                <span class="text-sm text-ink-soft">{{ config('site.address') }}</span>
                <span class="mt-2 inline-flex items-center gap-2 text-sm font-semibold"><span class="link-line">{{ __('site.contact.map_open') }}</span><svg class="size-4 transition-transform duration-500 ease-brand group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></span>
                <span class="sr-only"> ({{ __('site.ui.new_tab') }})</span>
            </span>
        @else
            <span class="sr-only">{{ __('site.contact.map_open') }} ({{ __('site.ui.new_tab') }})</span>
        @endif
    </a>
    <figcaption class="absolute right-2 top-2 rounded bg-crema/85 px-2 py-0.5 text-xs text-ink-soft">© <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" class="text-ink-soft underline">OpenStreetMap</a></figcaption>
</figure>
