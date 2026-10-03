# Direzione grafica

**Stato:** proposta iniziale del frontend, da raffinare con le prime pagine reali. Le scelte tecniche collegate (framework, font, immagini) sono nell'[ADR 0005](../decisioni/0005-framework-frontend.md).

## Principi

- **Mobile-first**: si disegna e si testa prima a 360–390 px di larghezza; tablet e desktop sono un adattamento.
- **Le pizze sono le protagoniste**: foto grandi, menù leggibilissimo da smartphone, molto spazio bianco.
- **Il logo è l'unico elemento "artigianale"** (pennello + Vesuvio). Tutto il resto è pulito e geometrico: niente texture, niente decorazioni pesanti.
- **Nessun richiamo calcistico o al Mondiale**: il significato di "82" non è un tema del sito.
- Solo vetrina: nessun carrello, login, modulo o prenotazione. Il design non deve però impedire future estensioni.

## Palette

Il blu del logo (da campionare sul file vettoriale quando arriva) è il colore identitario. Contrasti calcolati con la formula WCAG 2.x (luminanza relativa); soglie AA: 4,5:1 testo normale, 3:1 testo grande (≥ 24 px, o ≥ 18,66 px grassetto) ed elementi grafici/UI.

| Ruolo | Nome | Valore | Uso |
|---|---|---|---|
| Fondo | Crema/farina | `#FAF6EE` | Sfondo pagina |
| Fondo alternativo | Crema scuro | `#F1EADB` | Sezioni alternate, card |
| Superficie | Bianco | `#FFFFFF` | Card, immagini |
| Testo | Inchiostro | `#1A1A1A` | Testo, titoli |
| Testo secondario | Inchiostro morbido | `#4A4640` | Descrizioni, ingredienti |
| Testo attenuato | Grigio caldo | `#6B655C` | Note, didascalie |
| Identità (grande) | Blu logo | `#0090D0` | Solo elementi grandi: filetti, icone grandi, "82" nel logo |
| Identità (testo/link) | Blu scuro | `#006FA6` | Link, testo piccolo, titoli di sezione colorati |
| Azione | Rosso pomodoro | `#C73A1F` | Pulsanti ("Chiama"), azioni primarie |
| Azione (hover/focus) | Pomodoro scuro | `#A92E16` | Stato hover/active dell'azione |
| Linee | Sabbia | `#D9D0BE` | Separatori (decorativi, non portano informazione) |

### Contrasti verificati

| Coppia (testo su sfondo) | Rapporto | Esito |
|---|---|---|
| Inchiostro su crema | 16,15:1 | AAA |
| Inchiostro su bianco | 17,40:1 | AAA |
| Inchiostro morbido su crema | 8,69:1 | AAA |
| Grigio caldo su crema | 5,35:1 | AA |
| Grigio caldo su crema scuro | 4,81:1 | AA |
| Blu scuro su bianco | 5,49:1 | AA |
| Blu scuro su crema | 5,09:1 | AA |
| Blu scuro su crema scuro | 4,58:1 | AA (al limite: evitare per testo piccolo) |
| Bianco su rosso pomodoro (pulsante) | 5,18:1 | AA |
| Bianco su pomodoro scuro (hover) | 6,80:1 | AA |
| Rosso pomodoro su crema (testo) | 4,81:1 | AA (usare solo se serve, meglio come sfondo di pulsante) |
| **Blu logo su crema** | **3,30:1** | Solo testo grande / elementi grafici (≥ 3:1), **mai testo piccolo** |
| **Blu logo su bianco** | **3,56:1** | Solo testo grande / elementi grafici |
| Sabbia su crema | 1,42:1 | Solo decorativo: nessuna informazione affidata al solo filetto |

Regole: il blu logo non si usa mai per testo sotto i 24 px; il colore non è mai l'unico veicolo di informazione; il focus da tastiera è sempre visibile (anello blu scuro, 2 px, con distanza dall'elemento). I valori vanno ricontrollati se la palette cambia (calcolo ripetibile: formula WCAG 2.x).

## Tipografia

- **Titoli**: sans geometrico, Montserrat (pesi 600–700).
- **Testo**: sans molto leggibile, Inter (pesi 400, 500, 600).
- **Self-hosted**: file `woff2` serviti dal sito, **nessun Google Fonts / CDN di terzi** (prestazioni, GDPR). Attenzione: il default di Laravel in `vite.config.js` usa il provider font `bunny` (CDN esterno) e `resources/css/app.css` dichiara `Instrument Sans`: vanno sostituiti nello step di implementazione (vedi domande aperte nell'ADR 0005).
- **Sottoinsiemi**: solo latino (con lettere accentate italiane e punteggiatura tipografica `’ « » € •`). Variable font se più leggero dei singoli pesi.
- **`font-display: swap`** per il testo; fallback di sistema con metriche allineate (`size-adjust`) per ridurre il layout shift. `preload` solo del font del testo principale.
- **Scala** (mobile → desktop, indicativa): corpo 16–18 px / interlinea 1,6; H1 32→48 px; H2 24→32 px; H3 20→24 px. Non scendere sotto 16 px per il testo corrente (evita lo zoom automatico su iOS).

## Spaziature e layout

- Griglia base a **4 px**; spaziature ricorrenti 8 / 16 / 24 / 32 / 48 / 64.
- Contenuto a colonna singola su mobile con **gutter di 16 px**; larghezza massima di lettura ~ 65–75 caratteri; contenitore max ~ 1120 px su desktop.
- Breakpoint mobile-first (default Tailwind): `sm` 640, `md` 768, `lg` 1024, `xl` 1280.
- Sezioni separate da spazio verticale generoso (48–96 px), non da bordi pesanti.
- **Target touch ≥ 44×44 px** con almeno 8 px di distanza tra target adiacenti.

## Componenti base previsti

Elenco di ciò che serve, non un design system: si creano solo quando la prima pagina li usa.

- Header con logo + navigazione (su mobile: menu compatto, raggiungibile e utilizzabile anche senza JS dove possibile) e pulsante **Chiama** sempre accessibile.
- Footer con indirizzo, orari, telefono, social, P.IVA e link utili.
- Pulsante primario (azione, pomodoro) e secondario (contorno blu scuro); link testuale (blu scuro, sottolineato).
- Hero (immagine 16:9 + H1 + una sola azione).
- Card pizza / voce di menù: nome, ingredienti, riga "A fine cottura"/"Servito con", nota, prezzo (segnaposto), eventuale foto.
- Navigazione del menù per categoria: ancore a pagina intera su una sola pagina (indice sticky su mobile), per 9 categorie e ~80 voci.
- Blocco orari (tabella semantica), blocco contatti, mappa (link a mappa esterna o embed solo dopo consenso: da decidere).
- Etichette: asterisco `*` per gli ingredienti marcati, segnaposto allergeni.

## Immagini

- **Formati**: AVIF con fallback WebP (e JPEG dove serve), generati dalla sorgente; mai solo in immagine le informazioni (menù, orari, indirizzo).
- **Rapporti fissi** per tipo, per evitare layout shift: hero **16:9**, pizze **4:3**, galleria/locale **3:2**, anteprima social Open Graph **1200×630**. `width`/`height` sempre presenti (o `aspect-ratio`).
- **Responsive**: `srcset`/`sizes` con larghezze ~ 480 / 800 / 1200 / 1600 px; non servire più pixel del necessario.
- **Caricamento**: `loading="lazy"` e `decoding="async"` ovunque **tranne** l'immagine LCP (hero), che ha `fetchpriority="high"` ed è `preload`-ata se serve.
- **`alt`** descrittivo e in italiano per ogni immagine di contenuto; vuoto (`alt=""`) per le decorative.
- **Segnaposto provvisori**: le foto attuali sono placeholder. Devono essere riconoscibili come tali (etichetta "FOTO PROVVISORIA" non nascosta, sfondo neutro con rapporto corretto) e **sostituibili senza toccare il codice**: le immagini stanno in una cartella dedicata (da definire nello step di implementazione) e la sostituzione di un file con lo stesso nome/slug è sufficiente. Nessuna foto provvisoria deve arrivare in produzione con la dicitura nascosta: la checklist SEO la considera un blocco al rilascio.
- Peso: hero ≤ 150 KB, foto pizza ≤ 80 KB ciascuna (nel formato servito al mobile).

## Logo

- Attualmente solo JPG con sfondo bianco: **non va usato così nel sito** (fondo crema).
- Si usa un **segnaposto testuale** ("Visciano 82") in un unico componente/partial (`<x-logo>` o simile), così la sostituzione con l'SVG è una modifica in un solo file.
- Quando arriva l'SVG: inline o `<img>` con `alt="Visciano 82"`, versione monocromatica per sfondi scuri, favicon e icone derivate. Il campionamento esatto del blu va fatto sul file vettoriale e riportato in questa pagina.
