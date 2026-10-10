# Checklist SEO, AI-friendly e mobile

Stato di ogni voce. Si aggiorna insieme al codice. Legenda: ✅ fatto · 🟡 parziale · ⬜ da fare (con le pagine vere). Pagine e contenuti: [sitemap e schede pagina](../contenuti/sitemap-e-pagine.md). Target di performance: [mobile e performance](../sviluppo/mobile-e-performance.md).

## SEO tecnica

| Voce | Stato | Note |
|---|---|---|
| HTML semantico (`header`, `nav`, `main`, `footer`, ecc.) | ✅ | Layout con header, nav, main, footer; testato |
| Un solo H1 per pagina, gerarchia heading corretta | ✅ | Testato su home, menù, storia, contatti, pagine legali, 404 e versioni inglesi; nel menù macro H2, sottocategorie H3, voci un livello sotto |
| Rendering lato server (contenuto leggibile senza JS) | ✅ | Pagine Blade, nessun rendering client |
| `<title>` per pagina, univoco | ✅ | Ogni pagina ha il suo, testato (anche in inglese) |
| Meta description per pagina, univoca (≤ 155 caratteri) | ✅ | Ogni pagina ha la sua, testato |
| Canonical per pagina (URL assoluto, senza parametri) | ✅ | Testato, per ogni lingua |
| Open Graph / Twitter card (`og:title`, `og:description`, `og:image` 1200×630, `og:type`, `og:locale`) | ✅ | Nel layout, da title/description/canonical della pagina; `og:locale` it_IT/en_GB con l'alternativa; immagine `public/brand/og-image.png` (logo bianco su nero con barra pomodoro, generata dal logo vettoriale: da rifare col logo ufficiale o con una foto vera); testato |
| `sitemap.xml` (solo URL canonici, `lastmod`) | ✅ | `/sitemap.xml` (`SitemapController`): ogni pagina in ogni lingua con le alternative `hreflang` e `x-default`, dagli stessi dati delle rotte; referenziata da `robots.txt` in production; testata. Senza `lastmod` (facoltativo, si aggiungerà quando i contenuti avranno una data di modifica) |
| `robots.txt` | ✅ | Dinamico (`RobotsController`): in production `Sitemap:` senza esporre il percorso admin; fuori production `Disallow: /` |
| Ambienti non production `noindex` | ✅ | Header `X-Robots-Tag` + meta robots, testati |
| Panel admin non indicizzabile, URL non di default | ✅ | `X-Robots-Tag` sempre attivo; percorso segreto da `ADMIN_PATH`, non in `robots.txt` né nel repo |
| Dati strutturati JSON-LD (Restaurant/Pizzeria, orari) | ✅ | `Restaurant` su ogni pagina (`App\Support\StructuredData`), dai dati di `config/site.php`; testato |
| Dati strutturati JSON-LD del menù (Menu, MenuSection, MenuItem) | ✅ | Sulla pagina menù, dallo stesso contratto dati della pagina (quindi dal pannello quando `MENU_SOURCE=database`); testato. Vedi [guida frontend](../frontend/dati-menu.md) |
| URL puliti (minuscoli, italiani, senza parametri/slash finale) | ✅ | `/menu`, `/la-nostra-storia`, `/contatti`; da rispettare nelle nuove pagine |
| Link interni tra le pagine (nav header/footer, rimandi dalla home) | ✅ | Testati |
| HTTPS e reindirizzamento unico (http→https, www/non-www) | ⬜ | Sistemistica (Nicolas) |
| Pagina 404 personalizzata con status 404 | ✅ | `resources/views/errors/404.blade.php`, in inglese sotto `/en/`, con link a home e menù; testata |
| Favicon e icone (da logo SVG) | 🟡 | Favicon SVG dal logo vettorizzato; icone PNG (Apple, PWA) da fare con il logo ufficiale |
| Core Web Vitals sotto soglia | ⬜ | Target e metodo in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| Lingua del documento (`lang="it"`) | ✅ | Testato |
| Nome precedente "Mundial 82" visibile nei contenuti, nella forma "ex Mundial 82" | 🟡 | Molti cercano ancora il vecchio nome. Fatto in title, meta description e prima frase della home ("Visciano 82 (ex Mundial 82)…", in inglese "formerly"), testato; nei dati strutturati come `alternateName`. Vedi [contesto](../progetto/contesto-e-decisioni.md#il-progetto) |
| `hreflang` e alternate tra le lingue (`it`, `en`, `x-default`) | ✅ | Testato; vedi [ADR 0009](../decisioni/0009-multilingua.md) |

## SEO on-page

| Voce | Stato | Note |
|---|---|---|
| Title e description scritti per pagina (con città/zona dove pertinente) | 🟡 | Scritti per ogni pagina (home con Vigonovo ed "ex Mundial 82"); da rivedere con i testi definitivi |
| Immagini con `alt` descrittivo in italiano (vuoto se decorative) | ✅ | Alt tradotti per ogni foto; elementi decorativi `aria-hidden`; da riscrivere sulle foto vere |
| Immagini ottimizzate (AVIF/WebP, `srcset`, `width`/`height`, lazy tranne LCP) | ✅ | Componente `x-foto` e mappa statica; testato. Regole in [direzione grafica](../design/direzione-grafica.md) |
| Link interni con testo descrittivo, navigazione coerente | ✅ | Navigazione uguale in header e footer, link alle categorie del menù dalla home |
| Nessuna foto provvisoria o dato segnaposto in produzione | ⬜ | Blocco al rilascio: `config/site.php`, "Foto provvisoria", testi e prezzi di esempio |
| Contenuto testuale originale (non solo immagini) per ogni pagina | 🟡 | Ogni pagina ha testo in HTML; testi in bozza da confermare con il cliente, menù con voci di esempio |

## SEO locale

| Voce | Stato | Note |
|---|---|---|
| NAP (nome, indirizzo, telefono) identico in footer, contatti e JSON-LD | ✅ | Tutto da `config/site.php` |
| Città/zona in H1/title/primo paragrafo della home | ✅ | "Vigonovo" nel title e nel primo paragrafo; l'H1 resta uno slogan |
| Orari in HTML (tabella) e in `openingHoursSpecification` | ✅ | Tabella HTML e JSON-LD dagli stessi dati |
| Mappa/indicazioni (link a mappe) nella pagina Contatti | ✅ | Mappa statica ospitata da noi (nessuna terza parte, nessun cookie) che apre il luogo esatto su Google Maps; testata |
| Profilo Google Business collegato al sito e coerente | ⬜ | Azione del committente |
| Link `tel:` per il telefono | ✅ | Testato |

## Dati strutturati (JSON-LD)

| Voce | Stato | Note |
|---|---|---|
| `Pizzeria`/`Restaurant` (`LocalBusiness`) con `address`, `geo`, `telephone`, `priceRange`, `sameAs`, `hasMenu` | 🟡 | Fatto con `@id` unico, `alternateName` "Mundial 82", `servesCuisine`, `acceptsReservations`, `hasMap`; manca `priceRange` (dato del cliente). schema.org non ha un tipo "Pizzeria": si usa `Restaurant`. Un'unica entità con `@id` stabile, riusata dalle pagine |
| `openingHoursSpecification` | ✅ | Dagli orari strutturati (`day_of_week`, `opens`, `closes`; la chiusura a mezzanotte diventa 23:59); le chiusure straordinarie annunciate sono `specialOpeningHoursSpecification` |
| `Menu` → `MenuSection` → `MenuItem` generati dai dati Filament | ✅ | Solo contenuto visibile: nome, descrizione (o ingredienti), prezzo solo se presente, `suitableForDiet` dai tag vegetariano/vegano/senza-glutine; allergeni solo in HTML |
| `BreadcrumbList` (se utile) | ⬜ | Sito piatto: probabilmente non serve |
| Validazione (Rich Results Test / Schema.org validator) | ⬜ | Da fare sull'URL pubblico (servizi esterni di Google/schema.org) |
| Test automatico: JSON-LD presente e valido JSON | ✅ | `StructuredDataTest` |

## AI-friendly

| Voce | Stato | Note |
|---|---|---|
| Menù, orari e indirizzo in HTML (mai solo in immagini) | ✅ | HTML server-side; le voci del menù sono ancora di esempio |
| Allergeni mostrati solo se verificati dal ristorante | ✅ | Regola nel dominio e nella pagina (non verificati: "chiedi al personale"), testata. Vedi [guida frontend](../frontend/dati-menu.md) |
| Dati strutturati leggibili dai motori generativi | ✅ | Come JSON-LD sopra |
| Heading descrittivi e testi chiari, senza dipendere dal JS | ✅ | Tutto il contenuto è HTML; il JS aggiunge solo effetti |
| `llms.txt` | ⬜ | Da valutare |
| Politica esplicita sui crawler AI in `robots.txt` | ⬜ | Da decidere con il committente |

## Mobile

| Voce | Stato | Note |
|---|---|---|
| Mobile-first | ✅ | Ogni pagina rivista prima su mobile (menù senza ripetizioni, foto più basse, recensioni scorrevoli, avvisi compatti) |
| Viewport meta corretto | ✅ | Testato |
| Target touch adeguati (≥ 44×44 px, distanza ≥ 8 px) | ✅ | Link e pulsanti con `min-h-11`/`min-h-12` o `size-11`; da ricontrollare su ogni nuovo elemento |
| Nessuno scroll orizzontale (320–1920 px) | ✅ | Verificato con Playwright su tutte le pagine (it/en) a 320, 360, 390, 768, 1024 e 1440 px; da rifare per ogni nuova pagina |
| Test del layout a viewport mobile (320, 360, 390, 768) | 🟡 | Screenshot Playwright a pagina intera su ogni pagina durante lo sviluppo; non ancora automatizzato in CI. Metodo in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| Pulsante "Chiama" sempre raggiungibile | ✅ | Nell'header (desktop) e fisso in basso su mobile; testato |
| Contrasti WCAG AA | ✅ | Ogni coppia testo/sfondo usata è misurata in [direzione grafica](../design/direzione-grafica.md); il testo piccolo rosso è sempre pomodoro scuro |

## Performance

| Voce | Stato | Note |
|---|---|---|
| Font self-hosted, nessuna richiesta a CDN di terzi | ✅ | Bricolage Grotesque e Inter locali in `resources/fonts/`; `bunny` rimosso |
| Lighthouse mobile: Performance ≥ 90, SEO 100, A11y ≥ 95 | ⬜ | Target in [mobile e performance](../sviluppo/mobile-e-performance.md) |
| LCP ≤ 2,5 s · INP ≤ 200 ms · CLS ≤ 0,1 | ⬜ | |
| Peso pagina e JS entro i budget | ⬜ | |
| Cache HTTP e compressione | ⬜ | Sistemistica (Nicolas) |
