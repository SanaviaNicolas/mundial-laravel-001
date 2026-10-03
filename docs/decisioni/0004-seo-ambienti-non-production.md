# 0004 — Indicizzazione e ambienti non production

**Stato:** accettata

## Decisione
- Gli ambienti diversi da `production` non devono mai essere indicizzati, con due difese: header `X-Robots-Tag: noindex, nofollow` (middleware `NoIndexOutsideProduction`, nel gruppo `web`) e `<meta name="robots">` nel layout.
- L'ambiente è valutato a ogni richiesta (`app()->isProduction()`), così i test possono cambiarlo.
- `robots.txt` è generato da `RobotsController` (non un file statico) perché dipende dall'ambiente e dall'URL del sito: fuori production blocca tutto (`Disallow: /`), in production non blocca nulla e indica la sitemap. **Non** contiene `Disallow` per il pannello admin: elencare il percorso in un file pubblico lo rivelerebbe (vedi [ADR 0003](0003-pannello-admin.md)).
- Il layout Blade `layouts/app.blade.php` espone `title` e `description` per pagina; la scelta del framework frontend è in [ADR 0008](0008-framework-frontend.md).
- Il `TestCase` base usa `withoutVite()`: i test non richiedono la build degli asset.
- Lo stato delle altre voci SEO è in [checklist](../seo/checklist.md).
- Il pannello admin è escluso dall'indicizzazione solo tramite `X-Robots-Tag: noindex, nofollow` (middleware `NoIndex`), sempre attivo in ogni ambiente e testato.
