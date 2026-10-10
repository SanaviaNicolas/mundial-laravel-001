{{--
    A category holding items, in two columns: number, title, description and photo on the left,
    items on the right. $livello: heading level of the title (items one level below);
    $titolo false = items of a macro category shown under its own heading (no anchor, no title).
--}}
@php
    $condivise = $sezione->sharedAddons();
    $nonVerificata = $sezione->allergensUnverified();
    $tag = $titolo ? 'section' : 'div';
@endphp

<{{ $tag }}{!! $titolo ? ' id="'.e($sezione->slug).'"' : '' !!} class="scroll-mt-36 py-16 md:py-24 {{ $numero % 2 === 0 ? 'bg-crema-scuro' : '' }}">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-12 md:gap-12">
        <div class="md:col-span-5">
            <div class="md:sticky md:top-40">
                <p class="font-display text-sm font-bold tracking-widest"><span class="text-pomodoro">{{ sprintf('%02d', $numero) }}</span> <span class="text-muted">/ {{ sprintf('%02d', $totale) }} · {{ trans_choice('site.home.items', count($sezione->items)) }}</span></p>
                @if ($titolo)
                    <h{{ $livello }} class="mt-3 type-section">{{ $sezione->name }}</h{{ $livello }}>
                    @if ($sezione->description)
                        <p class="mt-4 text-lg text-ink-soft">{{ $sezione->description }}</p>
                    @endif
                @endif
                <x-foto :name="'categoria-'.$sezione->slug" :alt="$sezione->name" ratio="aspect-[16/9] md:aspect-[4/3]" sizes="(min-width: 768px) 40vw, 100vw" class="mt-8 rounded-2xl" />
            </div>
        </div>
        <div class="md:col-span-7">
            <ul class="list-none divide-y divide-ink/15 border-y border-ink/15 p-0">
                @foreach ($sezione->items as $voce)
                    @include('menu.voce', ['livello' => $livello + 1, 'condivise' => $condivise !== [], 'verifica' => ! $nonVerificata])
                @endforeach
            </ul>
            @if ($condivise)
                @include('menu.aggiunte', ['aggiunte' => $condivise])
            @endif
            @if ($nonVerificata)
                <p class="mt-3 text-sm text-ink-soft">{{ __('site.menu.allergens_ask_section') }}</p>
            @endif
        </div>
    </div>
</{{ $tag }}>
