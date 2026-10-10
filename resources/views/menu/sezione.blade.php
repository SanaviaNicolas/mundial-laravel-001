{{--
    A category holding items, in two columns: number, title, description and photo on the left,
    items on the right. $livello: heading level of the title (items one level below);
    $titolo false = items of a macro category shown under its own heading (no anchor, no title).
    $aggiunteSopra: the macro category already shows the shared addons; $avvisoPagina: the page
    already shows the allergen notice once.
--}}
@php
    $condivise = $sezione->sharedAddons();
    $nonVerificata = $sezione->allergensUnverified();
    $tag = $titolo ? 'section' : 'div';
@endphp

<{{ $tag }}{!! $titolo ? ' id="'.e($sezione->slug).'"' : '' !!} class="scroll-mt-36 py-12 md:py-24 {{ $numero % 2 === 0 ? 'bg-crema-scuro' : '' }}">
    <div class="mx-auto grid max-w-6xl gap-6 px-4 md:grid-cols-12 md:gap-12">
        <div class="md:col-span-5">
            <div class="md:sticky md:top-40">
                <p class="font-display text-sm font-bold tracking-widest"><span class="text-pomodoro-scuro">{{ sprintf('%02d', $numero) }}</span> <span class="text-muted">/ {{ sprintf('%02d', $totale) }}</span></p>
                @if ($titolo)
                    <h{{ $livello }} class="mt-3 type-section">{{ $sezione->name }}</h{{ $livello }}>
                    @if ($sezione->description)
                        <p class="mt-4 text-lg text-ink-soft">{{ $sezione->description }}</p>
                    @endif
                @endif
                <x-foto :name="'categoria-'.$sezione->slug" :alt="$sezione->name" ratio="aspect-[4/3]" sizes="(min-width: 768px) 40vw, 1px" class="mt-8 rounded-2xl max-md:hidden" />
            </div>
        </div>
        <div class="md:col-span-7">
            <ul class="list-none space-y-8 p-0 md:space-y-12">
                @foreach ($sezione->items as $voce)
                    @include('menu.voce', ['livello' => $livello + 1, 'condivise' => $condivise !== [], 'verifica' => ! $nonVerificata])
                @endforeach
            </ul>
            @php($mostraAggiunte = $condivise && ! $aggiunteSopra)
            @php($mostraAvviso = $nonVerificata && ! $avvisoPagina)
            @if ($mostraAggiunte || $mostraAvviso)
                <div class="mt-10 rounded-2xl bg-ink/[0.04] p-5 md:mt-12 md:p-6">
                    @if ($mostraAggiunte)
                        @include('menu.aggiunte', ['aggiunte' => $condivise])
                    @endif
                    @if ($mostraAvviso)
                        <p @class(['text-sm italic text-ink-soft', 'mt-3' => $mostraAggiunte])>{{ __('site.menu.allergens_ask_section') }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</{{ $tag }}>
