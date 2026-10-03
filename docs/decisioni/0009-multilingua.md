# 0009 — Sito multilingua (italiano e inglese)

**Stato:** accettata (frontend); da condividere con Nicolas perché tocca rotte, config e menù.

## Contesto
Il sito avrà una versione in inglese oltre all'italiano. Serve una soluzione semplice, indicizzabile (SEO) e che regga il futuro menù dinamico di Filament.

## Decisione
- **URL**: italiano senza prefisso (lingua predefinita), inglese con prefisso `/en` e **slug tradotti**: `/menu` ↔ `/en/menu`, `/la-nostra-storia` ↔ `/en/our-story`, `/contatti` ↔ `/en/contact`, `/` ↔ `/en`. La mappa delle pagine è in `config/site.php` (`pages`; le lingue sono in `config('app.locales')`): da lì nascono rotte (`routes/web.php`, nomi `it.menu`, `en.menu`, …), navigazione e alternate.
- **Lingua della richiesta**: middleware `locale:{it|en}` (`App\Http\Middleware\SetLocale`) che imposta `app()->setLocale()`. Nessun reindirizzamento automatico basato sul browser (evita problemi con i crawler e con la cache); la scelta è esplicita con il selettore **IT / EN** nell'header e nel menu mobile, che porta alla stessa pagina nell'altra lingua.
- **SEO**: `<html lang>` corretto, `<link rel="canonical">` per pagina, `<link rel="alternate" hreflang="it|en">` reciproci e `x-default` (italiano), title e meta description tradotti.
- **Testi dell'interfaccia**: file di traduzione Laravel `lang/it/site.php` e `lang/en/site.php` (chiavi `site.*`), usati nelle viste con `__()`. Le traduzioni inglesi sono **bozze da far rivedere al cliente**.
- **Orari**: `config/site.php` contiene gli orari una volta sola (giorni come chiavi traducibili, `open` nullo = chiuso).
- **Menù**: `config/menu.php` ha testi come stringa semplice (uguale nelle due lingue) oppure `['it' => …, 'en' => …]`, e prezzi numerici; `App\Support\Menu::categories()` restituisce il menù già localizzato (prezzi `€ 7,00` in italiano, `€ 7.00` in inglese). Quando il menù passerà a Filament, i campi di testo di categorie e voci dovranno essere traducibili (es. campi JSON per lingua o `spatie/laravel-translatable`): da decidere con Nicolas, la forma attesa dalle viste resta quella di `Menu::categories()`.

## Conseguenze
- Aggiungere una lingua: voce in `config('app.locales')`, URI nelle `pages`, file `lang/{xx}/site.php`, testi `xx` nel menù.
- La `sitemap.xml` (da fare) dovrà elencare tutte le pagine in entrambe le lingue con le alternate.
- Il JSON-LD (da fare) dovrà usare `inLanguage` e i testi localizzati.
