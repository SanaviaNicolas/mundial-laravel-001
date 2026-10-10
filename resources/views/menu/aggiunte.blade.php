{{-- Addons of an item or of a whole category. Supplement 0 = "no supplement"; their allergens are never merged with the item's. --}}
<div class="text-sm text-ink-soft">
    <p><strong class="font-semibold text-ink">{{ __('site.menu.addons') }}:</strong>
        @foreach ($aggiunte as $aggiunta)
            {{ $aggiunta->name }} @if ($aggiunta->price > 0)<span class="font-semibold text-ink">+ {{ \App\Menu\Price::format($aggiunta->price) }}</span>@else<em>({{ __('site.menu.no_supplement') }})</em>@endif @if ($aggiunta->allergens)<em>({{ __('site.menu.addon_contains', ['list' => implode(', ', $aggiunta->allergens)]) }})</em>@endif{{ $loop->last ? '' : ' · ' }}
        @endforeach
    </p>
    <p class="mt-1 italic">{{ __('site.menu.addons_allergens') }}</p>
</div>
