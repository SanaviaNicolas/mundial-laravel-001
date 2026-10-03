# 0005 — Traduzioni dei contenuti (it/en)

**Stato:** accettata

## Contesto
Il sito è in italiano (lingua di riferimento) e inglese. Tutti i testi pubblici del menù devono essere traducibili: nomi, descrizioni, note, categorie, ingredienti, tag, allergeni, aggiunte.

## Opzioni valutate
- **`spatie/laravel-translatable` + plugin Filament ufficiale**: il plugin ufficiale non ha release per Filament 5 (solo ≤ 3.x); esiste un plugin di terze parti (Lara Zeus), ma sarebbe una dipendenza in più legata a un autore esterno. Senza il plugin, il pacchetto Spatie restituisce al form solo la stringa della lingua corrente, in conflitto con i form Filament.
- **Colonne `jsonb` native** con cast `array` e un piccolo trait: nessuna dipendenza, e i form Filament leggono e scrivono direttamente `name.it` / `name.en`.

## Decisione
- Ogni testo traducibile è una colonna `jsonb` `{"it": "...", "en": "..."}`.
- Trait `App\Models\Concerns\HasTranslations`: i modelli dichiarano `$translatable`; `translate('name', $locale = null)` restituisce il testo nella lingua richiesta (default: lingua corrente) con **fallback sull'italiano** se manca o è vuoto, altrimenti `null`.
- Scope `whereTranslationLike()` e `orderByTranslation()` per ricerca (ILIKE) e ordinamento sul testo nella lingua corrente con lo stesso fallback (usati dalle tabelle Filament).
- Lingue in `config('app.locales')` (`it`, `en`). L'italiano è obbligatorio, l'inglese opzionale.
- Gli **slug non sono tradotti** per ora (solo italiano). Da rivedere allo step frontend/i18n, quando serviranno URL in inglese (slug tradotti).

## Conseguenze
Il frontend usa sempre `translate()`: non legge mai direttamente l'array. Aggiungere una lingua richiede solo di aggiungerla a `app.locales` e di compilare i testi.
