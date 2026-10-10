{{-- Addons of an item or of a whole category. Supplement 0 = "no supplement"; their allergens are never merged with the item's. --}}
<div class="mt-3 text-sm text-ink-soft">
    <p><span class="font-semibold text-ink">{{ __('site.menu.addons') }}:</span>
        @foreach ($aggiunte as $aggiunta)
            {{ $aggiunta->name }} {{ $aggiunta->price > 0 ? '+ '.\App\Menu\Price::format($aggiunta->price) : '('.__('site.menu.no_supplement').')' }}@if ($aggiunta->allergens) ({{ __('site.menu.addon_contains', ['list' => implode(', ', $aggiunta->allergens)]) }})@endif{{ $loop->last ? '' : ' · ' }}
        @endforeach
    </p>
    <p class="mt-1">{{ __('site.menu.addons_allergens') }}</p>
</div>
