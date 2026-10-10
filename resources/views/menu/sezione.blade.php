{{--
    A category holding items: number, title and description on top, items below in two columns from
    tablet up. $livello: heading level of the title (items one level below); $titolo false = items
    of a macro category shown right under its band (no anchor, no title).
    $aggiunteSopra: the macro category already shows the shared addons; $avvisoPagina: the page
    already shows the allergen notice once.
--}}
@php
    $condivise = $sezione->sharedAddons();
    $nonVerificata = $sezione->allergensUnverified();
    $tag = $titolo ? 'section' : 'div';
@endphp

<{{ $tag }}{!! $titolo ? ' id="'.e($sezione->slug).'"' : '' !!} class="scroll-mt-36 py-10 md:py-12 {{ $numero % 2 === 0 ? 'bg-crema-scuro' : '' }}">
    <div class="mx-auto max-w-6xl px-4">
        @if ($titolo)
            <p class="font-display text-sm font-bold tracking-widest"><span class="text-pomodoro-scuro">{{ sprintf('%02d', $numero) }}</span> <span class="text-muted">/ {{ sprintf('%02d', $totale) }}</span></p>
            <h{{ $livello }} class="mt-2 type-section">{{ $sezione->name }}</h{{ $livello }}>
            @if ($sezione->description)
                <p class="mt-3 max-w-2xl text-lg text-ink-soft">{{ $sezione->description }}</p>
            @endif
        @endif
        <ul @class(['grid list-none gap-x-12 gap-y-8 p-0 md:grid-cols-2 lg:grid-cols-3', 'mt-6 md:mt-8' => $titolo])>
            @foreach ($sezione->items as $voce)
                @include('menu.voce', ['livello' => $livello + 1, 'condivise' => $condivise !== [], 'verifica' => ! $nonVerificata])
            @endforeach
        </ul>
        @php($mostraAggiunte = $condivise && ! $aggiunteSopra)
        @php($mostraAvviso = $nonVerificata && ! $avvisoPagina)
        @if ($mostraAggiunte || $mostraAvviso)
            <div class="mt-8 rounded-2xl bg-ink/[0.04] p-5 md:p-6">
                @if ($mostraAggiunte)
                    @include('menu.aggiunte', ['aggiunte' => $condivise])
                @endif
                @if ($mostraAvviso)
                    <p @class(['text-sm italic text-ink-soft', 'mt-3' => $mostraAggiunte])>{{ __('site.menu.allergens_ask_section') }}</p>
                @endif
            </div>
        @endif
    </div>
</{{ $tag }}>
