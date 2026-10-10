# Sitemap e schede pagina

**Stato:** proposta, da confermare. I dati reali mancanti sono indicati in questo documento come `TODO-DATO`; nel sito appaiono come testo segnaposto evidente (vedi [direzione grafica](../design/direzione-grafica.md)). Non vanno inventati valori plausibili.

## Dati reali mancanti

| Dato | Stato |
|---|---|
| Indirizzo completo | Ricevuto: Via Cadiceto, 30030 Vigonovo VE (manca il numero civico, se esiste) |
| Telefono | Ricevuto: 049 983 0186 |
| Orari di apertura | Ricevuti: lun chiuso; mar–sab 18:00–00:00; dom 18:30–00:00 |
| Coordinate geografiche (per mappa e JSON-LD) | Ricevute dal luogo Google Maps indicato dal committente: 45.3770736, 12.0008935 (`site.geo`); il link esatto al luogo è `site.map_url` |
| P.IVA / ragione sociale / email (footer, privacy e cookie policy) | `TODO-DATO`: nel footer la P.IVA è nascosta, nelle pagine legali compare "[dato da completare]" (`company`, `vat`, `email` in `config/site.php`) |
| Profili social (URL) | Ricevuti: Instagram `@mundial82` (instagram.com/mundial82), Facebook `Mundial82` (facebook.com/Mundial82): usano ancora il vecchio nome. In `config/site.php` (`social`); da usare anche come `sameAs` nel JSON-LD |
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
| Privacy policy | `/privacy` | Obbligatoria |
| Cookie policy | `/cookie` | Obbligatoria |

URL minuscoli, italiani, senza estensione né parametri, senza slash finale. Le pagine legali (privacy e cookie policy) sono linkate dal footer, non dalla navigazione principale; vedi [Contesto e decisioni](../progetto/contesto-e-decisioni.md#frontend).

Il sito è **multilingua** (italiano e inglese, vedi [ADR 0009](../decisioni/0009-multilingua.md)): ogni pagina ha l'URL italiano e quello inglese (`/` ↔ `/en`, `/menu` ↔ `/en/menu`, `/la-nostra-storia` ↔ `/en/our-story`, `/contatti` ↔ `/en/contact`, `/privacy` ↔ `/en/privacy`, `/cookie` ↔ `/en/cookies`). I testi inglesi sono bozze da validare con il cliente. Il sito è **multipagina**: ogni contenuto ha la sua pagina e la sua URL (niente one page). Navigazione principale: Menù · La storia · Contatti, più il pulsante **Chiama** (e logo → Home); gli stessi link sono nel footer.

## Home — `/`

- **Obiettivo**: far capire in 3 secondi cos'è (pizzeria-ristorante), dove si trova, e portare a Menù o telefonata.
- **Sezioni**: hero (foto + H1 + pulsante Chiama / link al menù) · fascia con le categorie · mosaico delle 9 categorie (link a `/menu#categoria`) · impasto (lievitazione, idratazione, digeribilità, link alla storia) · "Vieni a trovarci" (indirizzo, telefono, link a Contatti) · footer.
- **SEO**: title indicativo `Visciano 82 — Pizzeria e ristorante a Vigonovo`; meta description `Visciano 82, pizzeria e ristorante: pizza napoletana, cucina, menù, orari e contatti.` (≤ 155 caratteri). H1 attuale: `Pizza napoletana, fatta come si deve.`; da valutare un H1 con nome e città (SEO locale) con il testo definitivo.
- **Dati strutturati**: `Pizzeria`/`Restaurant` (sottotipo `LocalBusiness`): `name`, `url`, `image`, `telephone`, `address` (`PostalAddress`), `geo`, `openingHoursSpecification`, `servesCuisine`, `priceRange`, `sameAs`, `hasMenu` → URL del menù.
- **SEO locale**: città/zona nell'H1 e nel primo paragrafo; NAP (nome, indirizzo, telefono) identico ovunque; profilo Google Business collegato al sito (azione del committente).

## Menù — `/menu`

**Stato:** implementata con dati statici di esempio (`config/menu.php`) che hanno già la forma dei dati del pannello: diventa dinamica impostando `MENU_SOURCE=database` ([ADR 0010](../decisioni/0010-contratto-dati-menu.md)). Mancano ancora i dati strutturati JSON-LD e il contenuto reale.

- **Obiettivo**: consultare velocemente tutte le pizze e i piatti da smartphone; trovare la pizza giusta.
- **Sezioni**: H1 · tre qualità dell'impasto · "In evidenza" (voci con badge) · indice delle categorie (ancore) · macrocategorie `H2`, sottocategorie `H3` (numerate e con foto), voci un livello sotto (`H3` sotto una macro senza sottocategorie, `H4` sotto una sottocategoria) · legenda dei surgelati · invito a chiamare.
- **Categorie** (ordine proposto): Tradizione napoletana con bordo alto · Le classiche · Le speciali · Fiorfritta/Coccodrillo · Le bianche · Le chiuse · Baguette · Panuozzi · Tegamini. Struttura pronta per bevande e dolci (`TODO-DATO`).
- **Badge**: i tag `pizza-del-mese`, `la-piu-scelta`, `novita` e `stagionale` diventano badge accanto alla voce e la portano nella sezione "In evidenza" in testa alla pagina (card senza foto).
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
- **Maradona**: il titolare è appassionato di Maradona; il vecchio nome del locale, **Mundial 82**, viene dal **Mondiale del 1982** (confermato). Aneddoti, cimeli o pizze dedicate: per ora nessuna informazione.

- **Obiettivo**: dare identità e fiducia, spiegare perché la pizza è diversa (impasto, tradizione).
- **Sezioni** (un H2 ciascuna): Una famiglia di pizzaioli · La tradizione, quella vera · La farina e il metodo giusti (tre riquadri: lievitazione, idratazione, digeribilità) · **Il 10 nel cuore** (omaggio a Maradona) · invito a guardare il menù o chiamare.
- **Omaggio a Maradona** (decisione: pagina La storia + dettaglio grafico, tono affettuoso): una sezione con la **foto del murale di Maradona a Largo Maradona** (Quartieri Spagnoli, Napoli), una toppa tonda blu con il "10" come numero di maglia, e un testo breve sul passaggio da Mundial 82 a Visciano 82 e sulla passione del titolare. La foto viene da **Pexels** (licenza gratuita, attribuzione non obbligatoria), fornita dal team e **ritagliata** per escludere le persone riconoscibili (una coppia, la folla); mostra un'opera d'arte pubblica con loghi dipinti (Napoli, sponsor dell'epoca): uso di omaggio, nessuna frase che faccia pensare a un'approvazione ufficiale, nessuna foto ritratto, firma o logo aggiunti da noi. Testo in **bozza**, da far confermare al cliente: costruito solo sulle informazioni ricevute, senza episodi, date o cimeli inventati.
- **SEO**: title `La nostra storia — pizza napoletana di famiglia | Visciano 82`; H1 `La nostra storia`; description sulla famiglia, la tradizione e l'impasto.
- **Dati strutturati** (da fare): stessa entità `Pizzeria`/`Restaurant` della home; eventuale `AboutPage`. Niente date, premi o nomi finché non confermati.
- **Domande al cliente**: se c'è un aneddoto o un oggetto legato a Maradona da raccontare (o una pizza dedicata); nome del titolare e della famiglia (se pubblicabili), anno di apertura, da quale zona di Napoli arriva la famiglia, il riconoscimento "1° premio oscar della pizza '73" (se è del locale, dove va citato), foto della famiglia e del forno.

## Contatti — `/contatti`

- **Obiettivo**: far arrivare le persone al locale o farle telefonare.
- **Stato**: implementata con i dati reali (indirizzo, telefono, orari).
- **Sezioni**: H1 · indirizzo · telefono (link `tel:`, pulsante Chiama) · link alla mappa (il luogo Google Maps indicato dal committente, uguale in footer e contatti) · **mappa statica** sotto (immagine ospitata da noi, cliccabile verso Google Maps) · orari (tabella semantica) · "Seguici" con le icone di Instagram e Facebook · (da aggiungere: come arrivare/parcheggio).
- **SEO**: title `Contatti, orari e dove siamo | Visciano 82`; meta description con città, telefono e orari sintetici; H1 `Dove siamo`.
- **Dati strutturati**: stesso `Pizzeria`/`Restaurant` della home con `address`, `geo`, `telephone`, `openingHoursSpecification` completi (la pagina di riferimento per il NAP).
- **SEO locale**: NAP identico a footer e profilo Google; mappa con link a Google Maps/Apple Maps. Un embed di mappa terza parte comporta cookie/tracciamento: preferire un link o un'immagine statica finché non si decide il consenso (domanda aperta).
- Nessun modulo di contatto in questa fase.

## Privacy e cookie policy — `/privacy`, `/cookie`

- **Stato**: implementate con una **bozza** di testo (italiano e inglese) da validare con il ristorante o il suo consulente. Una sola vista (`legale`) per entrambe; i testi sono nei file di lingua (`site.legal`), i dati del locale arrivano da `config/site.php`, il nome e la durata dei cookie dalla configurazione della sessione (così restano veri se cambiano).
- **Privacy**: titolare, dati trattati (solo log tecnici del server e il numero di chi telefona), basi giuridiche, conservazione, fornitori, diritti e reclamo al Garante.
- **Cookie**: solo i due cookie tecnici (sessione e `XSRF-TOKEN`) più la nota sul `sessionStorage` dell'animazione d'apertura; nessuna terza parte, quindi nessun banner.

## Pagine tecniche

- `404` personalizzata in italiano, con link alla home e al menù (noindex per costruzione: status 404).
- `/sitemap.xml` e `/robots.txt` (quest'ultimo già presente, vedi [ADR 0004](../decisioni/0004-seo-ambienti-non-production.md)).
- `/llms.txt`: da valutare (vedi [checklist SEO](../seo/checklist.md)).
