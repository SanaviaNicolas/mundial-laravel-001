# Checklist SEO, AI-friendly e mobile

Stato di ogni voce. Si aggiorna insieme al codice. Legenda: ✅ fatto · 🟡 parziale · ⬜ da fare (con le pagine vere).

## SEO tecnica

| Voce | Stato | Note |
|---|---|---|
| HTML semantico (`main`, heading, ecc.) | 🟡 | Layout base con `<main>`; da completare con header/nav/footer |
| Un solo H1 per pagina, gerarchia heading corretta | 🟡 | Home ok, testato; da testare su ogni nuova pagina |
| Rendering lato server (contenuto leggibile senza JS) | ✅ | Pagine Blade, nessun rendering client |
| `<title>` per pagina | 🟡 | Home ok, testato; layout con `@yield('title')` |
| Meta description per pagina | 🟡 | Home ok, testata |
| Canonical per pagina | ⬜ | |
| Open Graph / Twitter card | ⬜ | |
| `sitemap.xml` | ⬜ | Già referenziata da `robots.txt` in production |
| `robots.txt` | ✅ | Dinamico (`RobotsController`): in production `Disallow: /admin` + `Sitemap:`; fuori production `Disallow: /` |
| Ambienti non production `noindex` | ✅ | Header `X-Robots-Tag` + meta robots, testati |
| Panel `/admin` non indicizzabile | ✅ | `X-Robots-Tag` sempre, `Disallow: /admin` in production |
| Dati strutturati JSON-LD (Restaurant/Pizzeria, Menu, orari) | ⬜ | |
| URL puliti | ✅ | Route Laravel senza parametri inutili |
| Immagini ottimizzate con `alt` | ⬜ | |
| Core Web Vitals | ⬜ | Da misurare (Lighthouse) con le pagine vere |
| Lingua del documento (`lang="it"`) | ✅ | Testato |

## AI-friendly

| Voce | Stato | Note |
|---|---|---|
| Menù, orari e indirizzo in HTML (mai solo in immagini) | ⬜ | |
| Dati strutturati leggibili dai motori generativi | ⬜ | Come JSON-LD sopra |
| `llms.txt` | ⬜ | Da valutare |
| Politica esplicita sui crawler AI in `robots.txt` | ⬜ | Da decidere con il committente |

## Mobile

| Voce | Stato | Note |
|---|---|---|
| Mobile-first | 🟡 | Layout base fluido (`max-w` + padding); framework frontend da scegliere allo step 2 |
| Viewport meta corretto | ✅ | Testato |
| Target touch adeguati (≥ 44×44 px) | ⬜ | |
| Nessuno scroll orizzontale | ⬜ | Da verificare con le pagine vere |
| Test del layout a viewport mobile | ⬜ | Da fare con le pagine vere |
