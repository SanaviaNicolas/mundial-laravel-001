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
| `robots.txt` | ✅ | Dinamico (`RobotsController`): in production `Sitemap:` senza esporre il percorso admin; fuori production `Disallow: /` |
| Ambienti non production `noindex` | ✅ | Header `X-Robots-Tag` + meta robots, testati |
| Panel admin non indicizzabile, URL non di default | ✅ | `X-Robots-Tag` sempre attivo; percorso segreto da `ADMIN_PATH`, non in `robots.txt` né nel repo |
| Dati strutturati JSON-LD (Restaurant/Pizzeria, orari) | ⬜ | Orari e contatti arriveranno con la pagina Impostazioni |
| Dati strutturati JSON-LD del menù (Menu, MenuSection, MenuItem) | ⬜ | Il modello dati (step 2) è pronto; si genera dagli stessi dati della pagina. Vedi [guida frontend](../frontend/dati-menu.md) |
| URL puliti | ✅ | Route Laravel senza parametri inutili |
| Immagini ottimizzate con `alt` | ⬜ | |
| Core Web Vitals | ⬜ | Da misurare (Lighthouse) con le pagine vere |
| Lingua del documento (`lang="it"`) | ✅ | Testato |
| Nome precedente "Mundial 82" visibile nei contenuti, nella forma "ex Mundial 82" | ⬜ | Molti cercano ancora il vecchio nome. Da applicare a titoli, testi e dati strutturati con le pagine vere. Vedi [contesto](../progetto/contesto-e-decisioni.md#il-progetto) |

## AI-friendly

| Voce | Stato | Note |
|---|---|---|
| Menù, orari e indirizzo in HTML (mai solo in immagini) | ⬜ | Dati del menù pronti (step 2), pagine pubbliche da fare |
| Allergeni mostrati solo se verificati dal ristorante | 🟡 | Regola e metodi nel dominio, con test; la visualizzazione arriverà con il frontend |
| Dati strutturati leggibili dai motori generativi | ⬜ | Come JSON-LD sopra |
| `llms.txt` | ⬜ | Da valutare |
| Politica esplicita sui crawler AI in `robots.txt` | ⬜ | Da decidere con il committente |

## Mobile

| Voce | Stato | Note |
|---|---|---|
| Mobile-first | 🟡 | Layout base fluido (`max-w` + padding); framework frontend da scegliere in uno step dedicato |
| Viewport meta corretto | ✅ | Testato |
| Target touch adeguati (≥ 44×44 px) | ⬜ | |
| Nessuno scroll orizzontale | ⬜ | Da verificare con le pagine vere |
| Test del layout a viewport mobile | ⬜ | Da fare con le pagine vere |
