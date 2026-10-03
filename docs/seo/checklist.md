# Checklist SEO, AI-friendly e mobile

Stato di ogni voce. Si aggiorna insieme al codice. Legenda: ✅ fatto · 🟡 parziale · ⬜ da fare (con le pagine vere). Pagine e contenuti: [sitemap e schede pagina](../contenuti/sitemap-e-pagine.md). Target di performance: [mobile e performance](../sviluppo/mobile-e-performance.md).

## SEO tecnica

| Voce | Stato | Note |
|---|---|---|
| HTML semantico (`header`, `nav`, `main`, `footer`, ecc.) | ✅ | Layout con header, nav, main, footer; testato |
| Un solo H1 per pagina, gerarchia heading corretta | 🟡 | Home ok, testato; da testare su ogni nuova pagina |
| Rendering lato server (contenuto leggibile senza JS) | ✅ | Pagine Blade, nessun rendering client |
| `<title>` per pagina, univoco | 🟡 | Home ok, testato; layout con `@yield('title')` |
| Meta description per pagina, univoca (≤ 155 caratteri) | 🟡 | Home ok, testata |
| Canonical per pagina (URL assoluto, senza parametri) | ⬜ | |
| Open Graph / Twitter card (`og:title`, `og:description`, `og:image` 1200×630, `og:type`, `og:locale`) | ⬜ | |
| `sitemap.xml` (solo URL canonici, `lastmod`) | ⬜ | Già referenziata da `robots.txt` in production |
| `robots.txt` | ✅ | Dinamico (`RobotsController`): in production `Sitemap:` senza esporre il percorso admin; fuori production `Disallow: /` |
| Ambienti non production `noindex` | ✅ | Header `X-Robots-Tag` + meta robots, testati |
| Panel admin non indicizzabile, URL non di default | ✅ | `X-Robots-Tag` sempre attivo; percorso segreto da `ADMIN_PATH`, non in `robots.txt` né nel repo |
| Dati strutturati JSON-LD (Restaurant/Pizzeria, orari) | ⬜ | Orari e contatti arriveranno con la pagina Impostazioni |
| Dati strutturati JSON-LD del menù (Menu, MenuSection, MenuItem) | ⬜ | Il modello dati è pronto; si genera dagli stessi dati della pagina. Vedi [guida frontend](../frontend/dati-menu.md) |
| URL puliti (minuscoli, italiani, senza parametri/slash finale) | ✅ | Route Laravel; da rispettare nelle nuove pagine |
| HTTPS e reindirizzamento unico (http→https, www/non-www) | ⬜ | Sistemistica (Nicolas) |
| Pagina 404 personalizzata con status 404 | ⬜ | |
| Favicon e icone (da logo SVG) | ⬜ | Dipende dal file vettoriale |
| Core Web Vitals sotto soglia | ⬜ | Target e metodo in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| Lingua del documento (`lang="it"`) | ✅ | Testato |
| `hreflang` | ⬜ | Non necessario: sito monolingua |

## SEO on-page

| Voce | Stato | Note |
|---|---|---|
| Title e description scritti per pagina (con città/zona dove pertinente) | ⬜ | Bozze nelle [schede pagina](../contenuti/sitemap-e-pagine.md), con `TODO-DATO` |
| Immagini con `alt` descrittivo in italiano (vuoto se decorative) | ⬜ | |
| Immagini ottimizzate (AVIF/WebP, `srcset`, `width`/`height`, lazy tranne LCP) | ⬜ | Regole in [direzione grafica](../design/direzione-grafica.md) |
| Link interni con testo descrittivo, navigazione coerente | ⬜ | |
| Nessuna foto provvisoria o dato segnaposto in produzione | ⬜ | Blocco al rilascio: `config/site.php`, "Foto provvisoria", testi e prezzi di esempio |
| Contenuto testuale originale (non solo immagini) per ogni pagina | ⬜ | Testi `TODO-DATO` |

## SEO locale

| Voce | Stato | Note |
|---|---|---|
| NAP (nome, indirizzo, telefono) identico in footer, contatti e JSON-LD | ⬜ | Dati `TODO-DATO` |
| Città/zona in H1/title/primo paragrafo della home | ⬜ | |
| Orari in HTML (tabella) e in `openingHoursSpecification` | ⬜ | |
| Mappa/indicazioni (link a mappe) nella pagina Contatti | ⬜ | Embed di terze parti da decidere (cookie) |
| Profilo Google Business collegato al sito e coerente | ⬜ | Azione del committente |
| Link `tel:` per il telefono | ⬜ | |

## Dati strutturati (JSON-LD)

| Voce | Stato | Note |
|---|---|---|
| `Pizzeria`/`Restaurant` (`LocalBusiness`) con `address`, `geo`, `telephone`, `priceRange`, `sameAs`, `hasMenu` | ⬜ | Un'unica entità con `@id` stabile, riusata dalle pagine |
| `openingHoursSpecification` | ⬜ | |
| `Menu` → `MenuSection` → `MenuItem` generati dai dati Filament | ⬜ | Solo contenuto visibile in pagina |
| `BreadcrumbList` (se utile) | ⬜ | Sito piatto: probabilmente non serve |
| Validazione (Rich Results Test / Schema.org validator) | ⬜ | Dopo la prima implementazione |
| Test automatico: JSON-LD presente e valido JSON | ⬜ | Con la prima pagina che lo emette |

## AI-friendly

| Voce | Stato | Note |
|---|---|---|
| Menù, orari e indirizzo in HTML (mai solo in immagini) | ⬜ | Principio nelle schede pagina; dati del menù pronti nel backend, pagine pubbliche da collegare |
| Allergeni mostrati solo se verificati dal ristorante | 🟡 | Regola e metodi nel dominio, con test; la visualizzazione arriverà con il frontend. Vedi [guida frontend](../frontend/dati-menu.md) |
| Dati strutturati leggibili dai motori generativi | ⬜ | Come JSON-LD sopra |
| Heading descrittivi e testi chiari, senza dipendere dal JS | ⬜ | |
| `llms.txt` | ⬜ | Da valutare |
| Politica esplicita sui crawler AI in `robots.txt` | ⬜ | Da decidere con il committente |

## Mobile

| Voce | Stato | Note |
|---|---|---|
| Mobile-first | 🟡 | Layout base fluido (`max-w` + padding); stile vero con le prime pagine |
| Viewport meta corretto | ✅ | Testato |
| Target touch adeguati (≥ 44×44 px, distanza ≥ 8 px) | ⬜ | |
| Nessuno scroll orizzontale (320–768 px) | ⬜ | Da verificare con le pagine vere |
| Test del layout a viewport mobile (320, 360, 390, 768) | ⬜ | Metodo in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| Pulsante "Chiama" sempre raggiungibile | ✅ | Nell'header (desktop) e fisso in basso su mobile; testato |
| Contrasti WCAG AA | 🟡 | Palette verificata in [direzione grafica](../design/direzione-grafica.md); da riverificare sulle pagine reali |

## Performance

| Voce | Stato | Note |
|---|---|---|
| Font self-hosted, nessuna richiesta a CDN di terzi | ✅ | Montserrat e Inter locali in `resources/fonts/`; `bunny` rimosso |
| Lighthouse mobile: Performance ≥ 90, SEO 100, A11y ≥ 95 | ⬜ | Target in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| LCP ≤ 2,5 s · INP ≤ 200 ms · CLS ≤ 0,1 | ⬜ | |
| Peso pagina e JS entro i budget | ⬜ | |
| Cache HTTP e compressione | ⬜ | Sistemistica (Nicolas) |
