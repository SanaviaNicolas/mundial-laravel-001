{{--
    A menu item. $livello: heading level of the item name; $condivise: the category shows the
    addons once; $verifica: show the item's allergen state (false when the whole category is unverified).
--}}
@php
    $nomi = fn (array $ingredienti) => collect($ingredienti)->map(fn ($ingrediente) => $ingrediente->name.($ingrediente->frozen ? '*' : ''))->implode(', ');
    $evidenza = $voce->highlights();
    $altri = array_diff_key($voce->tags, $evidenza);
@endphp

<li class="row-reveal">
    <div class="flex items-baseline justify-between gap-6">
        <h{{ $livello }} class="text-2xl">{{ $voce->name }}</h{{ $livello }}>
        @if ($voce->price !== null)
            <span class="shrink-0 font-display text-xl font-extrabold text-pomodoro-scuro md:text-2xl">{{ \App\Menu\Price::format($voce->price) }}</span>
        @endif
    </div>
    @if ($voce->tags)
        <ul class="mt-2 flex list-none flex-wrap items-center gap-2 p-0">
            @foreach ($evidenza as $slug => $nome)
                <li><x-badge :slug="$slug" :nome="$nome" /></li>
            @endforeach
            @foreach ($altri as $nome)
                <li class="rounded-full border border-ink/20 px-2.5 py-0.5 text-xs font-semibold text-ink-soft">{{ $nome }}</li>
            @endforeach
        </ul>
    @endif
    @if ($voce->description)
        <p class="mt-2 max-w-lg font-medium text-ink">{{ $voce->description }}</p>
    @endif
    @foreach ($voce->ingredientGroups() as $gruppo)
        <div class="mt-2 max-w-lg text-ink-soft">
            @if ($gruppo['section'])
                <p class="mt-3 text-xs font-bold uppercase tracking-widest text-ink">{{ $gruppo['section'] }}</p>
            @endif
            @if ($gruppo['cooked'])
                <p>{{ $nomi($gruppo['cooked']) }}</p>
            @endif
            @if ($gruppo['after'])
                <p><em class="text-ink">{{ __('site.menu.after_cooking') }}:</em> {{ $nomi($gruppo['after']) }}</p>
            @endif
        </div>
    @endforeach
    @if ($voce->notes)
        <p class="mt-2 max-w-lg text-sm italic text-ink">{{ $voce->notes }}</p>
    @endif
    @if (! $condivise && $voce->addons)
        <div class="mt-3">@include('menu.aggiunte', ['aggiunte' => $voce->addons])</div>
    @endif
    @if ($verifica)
        <p class="mt-3 text-sm text-ink-soft">
            @if ($voce->allergens === null)
                <em>{{ __('site.menu.allergens_ask') }}</em>
            @elseif ($voce->allergens === [])
                <strong class="font-semibold text-ink">{{ __('site.menu.no_allergens') }}</strong>
            @else
                <strong class="font-semibold text-ink">{{ __('site.menu.allergens_label') }}:</strong> {{ implode(', ', $voce->allergens) }}
            @endif
        </p>
    @endif
</li>
