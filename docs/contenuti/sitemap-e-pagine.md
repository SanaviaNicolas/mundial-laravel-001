# Sitemap e schede pagina

**Stato:** proposta, da confermare. Tutti i dati reali mancanti sono indicati come `TODO-DATO`: non vanno inventati né sostituiti con valori plausibili.

## Dati reali mancanti

| Dato | Stato |
|---|---|
| Indirizzo completo (via, civico, CAP, comune, provincia) | `TODO-DATO` |
| Telefono | `TODO-DATO` |
| Orari di apertura (per giorno, eventuali chiusure/pause) | `TODO-DATO` |
| Coordinate geografiche (per mappa e JSON-LD) | `TODO-DATO` |
| P.IVA / ragione sociale (per il footer) | `TODO-DATO` |
| Profili social (URL) | `TODO-DATO` |
| Fascia di prezzo (`priceRange`) | `TODO-DATO` |
| Prezzi, allergeni, bevande, dolci del menù | `TODO-DATO` (struttura prevista) |
| Testi ("storia", descrizione del locale) e foto reali | `TODO-DATO` |

Nome locale: **Visciano 82**. Il vecchio nome "Mundial 82" non compare nei contenuti, salvo decisione contraria del committente.

## Struttura

| Pagina | URL | Priorità |
|---|---|---|
| Home | `/` | Alta |
| Menù | `/menu` | Alta (unica parte dinamica) |
| Il ristorante | `/il-ristorante` | Media |
| Contatti | `/contatti` | Alta (SEO locale) |
| Chi siamo | `/chi-siamo` | Opzionale: da valutare se confluire in "Il ristorante" |

URL minuscoli, italiani, senza estensione né parametri, senza slash finale. Eventuali pagine legali (privacy/cookie, note legali) servono solo se il sito usa cookie non tecnici o servizi di terzi: da decidere con il committente (domanda aperta).

Navigazione principale: Menù · Il ristorante · Contatti, più il pulsante **Chiama** (e logo → Home).

## Home — `/`

- **Obiettivo**: far capire in 3 secondi cos'è (pizzeria-ristorante), dove si trova, e portare a Menù o telefonata.
- **Sezioni**: hero (foto 16:9 + H1 + pulsante Chiama / link al menù) · specialità in evidenza (3–4 card) · il locale in breve (testo + foto) · orari e indirizzo (HTML, non immagine) · footer.
- **SEO**: title indicativo `Visciano 82 — Pizzeria e ristorante a TODO-DATO (città)`; meta description `Pizzeria e ristorante Visciano 82 a TODO-DATO: pizza napoletana, cucina, menù, orari e contatti.` (≤ 155 caratteri); H1 `Visciano 82 — pizzeria e ristorante a TODO-DATO` (un solo H1).
- **Dati strutturati**: `Pizzeria`/`Restaurant` (sottotipo `LocalBusiness`): `name`, `url`, `image`, `telephone`, `address` (`PostalAddress`), `geo`, `openingHoursSpecification`, `servesCuisine`, `priceRange`, `sameAs`, `hasMenu` → URL del menù.
- **SEO locale**: città/zona nell'H1 e nel primo paragrafo; NAP (nome, indirizzo, telefono) identico ovunque; profilo Google Business collegato al sito (azione del committente).

## Menù — `/menu`

- **Obiettivo**: consultare velocemente tutte le pizze e i piatti da smartphone; trovare la pizza giusta.
- **Sezioni**: H1 + indice delle 9 categorie (ancore) · una sezione `H2` per categoria con le voci (`H3` il nome o elemento lista) · note generali (allergeni, asterisco `*`, coperto) · invito a chiamare.
- **Categorie** (ordine proposto): Tradizione napoletana con bordo alto · Le classiche · Le speciali · Fiorfritta/Coccodrillo · Le bianche · Le chiuse · Baguette · Panuozzi · Tegamini. Struttura pronta per bevande e dolci (`TODO-DATO`).
- **Voce di menù**: nome (spesso dialettale), ingredienti (testo), riga "A fine cottura"/"Servito con", nota libera (es. "1° premio oscar della pizza '73"), asterisco su certi ingredienti, prezzo e allergeni (segnaposto), foto opzionale.
- **Pagina unica** con tutte le categorie in HTML server-side (leggibile da crawler e AI senza JS); l'eventuale filtro/ricerca è un miglioramento progressivo, non un requisito.
- **SEO**: title `Menù — pizze, panuozzi e cucina | Visciano 82`; meta description con le categorie principali; H1 `Il menù di Visciano 82`.
- **Dati strutturati**: `Menu` → `MenuSection` (una per categoria) → `MenuItem` (`name`, `description`, `offers`/prezzo solo quando disponibile). Generati dai dati di Filament, non scritti a mano. Il JSON-LD deve riflettere solo contenuto visibile in pagina.
- **Contenuto dinamico**: unica parte gestita da Filament (categorie, voci, ordine, visibilità, asterisco, note). Dettagli del modello dati: step backend, da concordare con Nicolas.

## Il ristorante — `/il-ristorante`

- **Obiettivo**: dare identità e fiducia: ambiente, impasto/lavorazione, ingredienti, la storia (incl. il riconoscimento "1° premio oscar della pizza '73", da confermare con il committente).
- **Sezioni**: H1 · presentazione del locale (testo + foto 3:2) · la nostra pizza (impasto, cottura, ingredienti) · la cucina · invito al menù e a venire a trovarci.
- **SEO**: title `Il ristorante — la nostra storia e la nostra pizza | Visciano 82`; meta description sul tipo di cucina e il locale; H1 `Il ristorante Visciano 82`.
- **Dati strutturati**: riferimenti alla stessa entità `Pizzeria`/`Restaurant` della home (stesso `@id`); `AboutPage` opzionale. Niente dati inventati su premi o storia finché non confermati.
- **Contenuti**: testi e foto `TODO-DATO`.

## Contatti — `/contatti`

- **Obiettivo**: far arrivare le persone al locale o farle telefonare.
- **Sezioni**: H1 · indirizzo · telefono (link `tel:`, pulsante Chiama) · orari (tabella semantica) · mappa · come arrivare/parcheggio · social.
- **SEO**: title `Contatti, orari e dove siamo | Visciano 82`; meta description con città, telefono e orari sintetici; H1 `Contatti e orari di Visciano 82`.
- **Dati strutturati**: stesso `Pizzeria`/`Restaurant` della home con `address`, `geo`, `telephone`, `openingHoursSpecification` completi (la pagina di riferimento per il NAP).
- **SEO locale**: NAP identico a footer e profilo Google; mappa con link a Google Maps/Apple Maps. Un embed di mappa terza parte comporta cookie/tracciamento: preferire un link o un'immagine statica finché non si decide il consenso (domanda aperta).
- Nessun modulo di contatto in questa fase.

## Pagine tecniche

- `404` personalizzata in italiano, con link alla home e al menù (noindex per costruzione: status 404).
- `/sitemap.xml` e `/robots.txt` (quest'ultimo già presente, vedi [ADR 0004](../decisioni/0004-seo-ambienti-non-production.md)).
- `/llms.txt`: da valutare (vedi [checklist SEO](../seo/checklist.md)).
