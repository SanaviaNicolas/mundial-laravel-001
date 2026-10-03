# 0004 — Indicizzazione e ambienti non production

**Stato:** accettata

## Decisione
- Gli ambienti diversi da `production` non devono mai essere indicizzati, con due difese: header `X-Robots-Tag: noindex, nofollow` (middleware `NoIndexOutsideProduction`, nel gruppo `web`) e `<meta name="robots">` nel layout.
- L'ambiente è valutato a ogni richiesta (`app()->isProduction()`), così i test possono cambiarlo.
- `robots.txt` è generato da `RobotsController` (non un file statico) perché dipende dall'ambiente e dall'URL del sito: fuori production blocca tutto (`Disallow: /`), in production esclude `/admin` e indica la sitemap.
- Il layout Blade `layouts/app.blade.php` espone `title` e `description` per pagina; la scelta del framework frontend è rimandata allo step 2.
- Il `TestCase` base usa `withoutVite()`: i test non richiedono la build degli asset.
- Lo stato delle altre voci SEO è in [checklist](../seo/checklist.md).
