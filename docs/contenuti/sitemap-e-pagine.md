# Sitemap e schede pagina

**Stato:** proposta, da confermare. I dati reali mancanti sono indicati in questo documento come `TODO-DATO`; nel sito appaiono come testo segnaposto evidente (vedi [direzione grafica](../design/direzione-grafica.md)). Non vanno inventati valori plausibili.

## Dati reali mancanti

| Dato | Stato |
|---|---|
| Indirizzo completo | Ricevuto: Via Cadiceto, 30030 Vigonovo VE (manca il numero civico, se esiste) |
| Telefono | Ricevuto: 049 983 0186 |
| Orari di apertura | Ricevuti: lun chiuso; mar–sab 18:00–00:00; dom 18:30–00:00 |
| Coordinate geografiche (per mappa e JSON-LD) | `TODO-DATO` |
| P.IVA / ragione sociale (per il footer) | `TODO-DATO` |
| Profili social (URL) | `TODO-DATO` |
| Fascia di prezzo (`priceRange`) | `TODO-DATO` |
| Prezzi, allergeni, bevande, dolci del menù | Prezzi **indicativi inseriti come segnaposto** (non forniti dal committente: da confermare); allergeni, bevande, dolci `TODO-DATO` |
| Testi definitivi (storia, descrizione) e foto reali | Bozza della pagina storia scritta dai punti del cliente (vedi sotto); testi definitivi e foto `TODO-DATO` |

Nome locale: **Visciano 82**. Il vecchio nome "Mundial 82" non compare nei contenuti, salvo decisione contraria del committente.

## Struttura

| Pagina | URL | Priorità |
|---|---|---|
| Home | `/` | Alta |
| Menù | `/menu` | Alta (unica parte dinamica) |
| La storia | `/la-nostra-storia` | Alta (identità e fiducia) |
| Contatti | `/contatti` | Alta (SEO locale) |

URL minuscoli, italiani, senza estensione né parametri, senza slash finale. Eventuali pagine legali (privacy/cookie, note legali) servono solo se il sito usa cookie non tecnici o servizi di terzi: da decidere con il committente (domanda aperta).

Il sito è **multipagina**: ogni contenuto ha la sua pagina e la sua URL (niente one page). Navigazione principale: Menù · La storia · Contatti, più il pulsante **Chiama** (e logo → Home); gli stessi link sono nel footer.

## Home — `/`

- **Obiettivo**: far capire in 3 secondi cos'è (pizzeria-ristorante), dove si trova, e portare a Menù o telefonata.
- **Sezioni**: hero (foto + H1 + pulsante Chiama / link al menù) · fascia con le categorie · mosaico delle 9 categorie (link a `/menu#categoria`) · impasto (lievitazione, idratazione, digeribilità, link alla storia) · "Vieni a trovarci" (indirizzo, telefono, link a Contatti) · footer.
- **SEO**: title indicativo `Visciano 82 — Pizzeria e ristorante a Vigonovo`; meta description `Visciano 82, pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.` (≤ 155 caratteri). H1 attuale: `Pizza napoletana, fatta come si deve.`; da valutare un H1 con nome e città (SEO locale) con il testo definitivo.
- **Dati strutturati**: `Pizzeria`/`Restaurant` (sottotipo `LocalBusiness`): `name`, `url`, `image`, `telephone`, `address` (`PostalAddress`), `geo`, `openingHoursSpecification`, `servesCuisine`, `priceRange`, `sameAs`, `hasMenu` → URL del menù.
- **SEO locale**: città/zona nell'H1 e nel primo paragrafo; NAP (nome, indirizzo, telefono) identico ovunque; profilo Google Business collegato al sito (azione del committente).

## Menù — `/menu`

**Stato:** implementata con dati statici di esempio (`config/menu.php`), da rendere dinamica con Filament. Mancano ancora i dati strutturati JSON-LD e il contenuto reale.

- **Obiettivo**: consultare velocemente tutte le pizze e i piatti da smartphone; trovare la pizza giusta.
- **Sezioni**: H1 + indice delle 9 categorie (ancore) · una sezione `H2` per categoria con le voci (`H3` il nome o elemento lista) · note generali (allergeni, asterisco `*`, coperto) · invito a chiamare.
- **Categorie** (ordine proposto): Tradizione napoletana con bordo alto · Le classiche · Le speciali · Fiorfritta/Coccodrillo · Le bianche · Le chiuse · Baguette · Panuozzi · Tegamini. Struttura pronta per bevande e dolci (`TODO-DATO`).
- **Badge**: alcune voci hanno un badge statico ("Pizza del mese", "La più scelta"), mostrato accanto alla voce e in una sezione "In evidenza" in testa alla pagina.
- **Voce di menù**: nome (spesso dialettale), ingredienti (testo), riga "A fine cottura"/"Servito con", nota libera (es. "1° premio oscar della pizza '73"), asterisco su certi ingredienti, prezzo e allergeni (segnaposto), foto opzionale.
- **Pagina unica** con tutte le categorie in HTML server-side (leggibile da crawler e AI senza JS); l'eventuale filtro/ricerca è un miglioramento progressivo, non un requisito.
- **SEO**: title `Menù — pizze, panuozzi e cucina | Visciano 82`; meta description con le categorie principali; H1 `Il menù di Visciano 82`.
- **Dati strutturati**: `Menu` → `MenuSection` (una per categoria) → `MenuItem` (`name`, `description`, `offers`/prezzo solo quando disponibile). Generati dai dati di Filament, non scritti a mano. Il JSON-LD deve riflettere solo contenuto visibile in pagina.
- **Contenuto dinamico**: unica parte gestita da Filament (categorie, voci, ordine, visibilità, asterisco, note). Dettagli del modello dati: step backend, da concordare con Nicolas.

## La storia — `/la-nostra-storia`

**Stato:** implementata con **testo abbozzato** a partire dai punti del cliente; da rivedere e sostituire con i testi definitivi.

**Informazioni ricevute dal cliente** (fonte dei contenuti, da non arricchire con dettagli inventati):
- Famiglia di pizzaioli; il titolare si è trasferito in Veneto da piccolo e ha aperto presto la pizzeria.
- Tradizione napoletana originale, "vero napoletano", attaccamento alla tradizione.
- Ingredienti originali, ricercati e di qualità.
- Dopo 50 anni hanno trovato la farina e il metodo giusti (**da chiarire**: 50 anni di cosa? attività, ricerca, esperienza di famiglia?).
- Impasto: almeno 2 giorni di lievitazione, alta idratazione, alta digeribilità.

- **Obiettivo**: dare identità e fiducia, spiegare perché la pizza è diversa (impasto, tradizione).
- **Sezioni** (un H2 ciascuna): Una famiglia di pizzaioli · La tradizione, quella vera · La farina e il metodo giusti (tre riquadri: lievitazione, idratazione, digeribilità) · invito a guardare il menù o chiamare.
- **SEO**: title `La nostra storia — pizza napoletana di famiglia | Visciano 82`; H1 `La nostra storia`; description sulla famiglia, la tradizione e l'impasto.
- **Dati strutturati** (da fare): stessa entità `Pizzeria`/`Restaurant` della home; eventuale `AboutPage`. Niente date, premi o nomi finché non confermati.
- **Domande al cliente**: nome del titolare e della famiglia (se pubblicabili), anno di apertura, da quale zona di Napoli arriva la famiglia, il riconoscimento "1° premio oscar della pizza '73" (se è del locale, dove va citato), foto della famiglia e del forno.

## Contatti — `/contatti`

- **Obiettivo**: far arrivare le persone al locale o farle telefonare.
- **Stato**: implementata con i dati reali (indirizzo, telefono, orari).
- **Sezioni**: H1 · indirizzo · telefono (link `tel:`, pulsante Chiama) · link alla mappa (Google Maps) · orari (tabella semantica) · (da aggiungere: come arrivare/parcheggio, social).
- **SEO**: title `Contatti, orari e dove siamo | Visciano 82`; meta description con città, telefono e orari sintetici; H1 `Dove siamo`.
- **Dati strutturati**: stesso `Pizzeria`/`Restaurant` della home con `address`, `geo`, `telephone`, `openingHoursSpecification` completi (la pagina di riferimento per il NAP).
- **SEO locale**: NAP identico a footer e profilo Google; mappa con link a Google Maps/Apple Maps. Un embed di mappa terza parte comporta cookie/tracciamento: preferire un link o un'immagine statica finché non si decide il consenso (domanda aperta).
- Nessun modulo di contatto in questa fase.

## Pagine tecniche

- `404` personalizzata in italiano, con link alla home e al menù (noindex per costruzione: status 404).
- `/sitemap.xml` e `/robots.txt` (quest'ultimo già presente, vedi [ADR 0004](../decisioni/0004-seo-ambienti-non-production.md)).
- `/llms.txt`: da valutare (vedi [checklist SEO](../seo/checklist.md)).
