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

- **Titoli**: Bricolage Grotesque (pesi 700–800), sans con carattere e calore; sostituisce Montserrat, scartato perché troppo freddo e generico.
- **Testo**: sans molto leggibile, Inter (pesi 400, 500, 600).
- **Self-hosted**: file `woff2` serviti dal sito, **nessun Google Fonts / CDN di terzi** (prestazioni, GDPR). I file `woff2` (Bricolage Grotesque e Inter variable, sottoinsieme latino) stanno in `resources/fonts/` e sono dichiarati con `@font-face` in `resources/css/app.css`; il provider `bunny` (CDN) del default Laravel è stato rimosso da `vite.config.js`.
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
- **Rapporti fissi** per tipo, per evitare layout shift: hero **a tutto schermo** (cover, soggetto al centro), foto di sezione **4:3** con bordi arrotondati, anteprima social Open Graph **1200×630**. `width`/`height` sempre presenti (o `aspect-ratio`).
- **Responsive**: `srcset`/`sizes` con larghezze ~ 480 / 800 / 1200 / 1600 px; non servire più pixel del necessario.
- **Caricamento**: `loading="lazy"` e `decoding="async"` ovunque **tranne** l'immagine LCP (hero), che ha `fetchpriority="high"` ed è `preload`-ata se serve.
- **`alt`** descrittivo e in italiano per ogni immagine di contenuto; vuoto (`alt=""`) per le decorative.
- **Segnaposto provvisori**: le foto attuali sono placeholder. Devono essere riconoscibili come tali (etichetta "FOTO PROVVISORIA" non nascosta, sfondo neutro con rapporto corretto) e **sostituibili senza toccare il codice**: le immagini stanno in una cartella dedicata (da definire nello step di implementazione) e la sostituzione di un file con lo stesso nome/slug è sufficiente. Nessuna foto provvisoria deve arrivare in produzione con la dicitura nascosta: la checklist SEO la considera un blocco al rilascio.
- Peso: hero ≤ 150 KB, foto pizza ≤ 80 KB ciascuna (nel formato servito al mobile).

## Logo

- Attualmente solo JPG con sfondo bianco: **non va usato così nel sito** (fondo crema).
- Si usa un **segnaposto testuale** ("Visciano 82") in un unico componente/partial (`<x-logo>` o simile), così la sostituzione con l'SVG è una modifica in un solo file.
- Quando arriva l'SVG: inline o `<img>` con `alt="Visciano 82"`, versione monocromatica per sfondi scuri, favicon e icone derivate. Il campionamento esatto del blu va fatto sul file vettoriale e riportato in questa pagina.

## Stato di implementazione

- Palette e font sono definiti come token Tailwind in `resources/css/app.css` (`@theme`): `crema`, `ink`, `blu`, `blu-scuro`, `pomodoro`, ecc.
- Componenti Blade in `resources/views/components/`: `logo` (segnaposto testuale che eredita il colore del testo), `button` (primario, secondario, `outline-light` per fondi scuri), `foto` (immagine o segnaposto "Foto provvisoria", chiaro o scuro, a tutto riquadro o con rapporto fisso).
- **Dati segnaposto**: i dati reali mancanti sono testo di esempio riconoscibile (`Via Esempio 1`, `000 000 0000`, `€ 00,00`, testi "di esempio"), centralizzati in `config/site.php`. Telefono, indirizzo e orari sono ora **reali** (forniti dal committente); la P.IVA manca ed è nascosta finché `site.vat` è nullo. I prezzi del menù sono **indicativi, non forniti dal committente**. Nei contenuti visibili non compare più la dicitura `TODO-DATO` (sostituita da questa scelta); nelle schede pagina resta come elenco di ciò che manca. Prima del rilascio vanno sostituiti tutti.

### Linguaggio visivo

Dopo i primi tentativi (blocchi di colore, foto a cerchio, bollino rotante, fascia inclinata, mosaico colorato: troppo "da sito creativo" per una pizzeria) e una versione sobria ma banale, la direzione attuale è **cinematografica ed editoriale**: foto grandi, tipografia enorme, pochi colori, movimento al servizio del racconto. Scelte:

- **Hero a tutto schermo** (`min-height: 100svh`): foto in cover con **lento zoom iniziale** (Ken Burns) e **parallasse allo scroll**, menu **trasparente** sopra l'immagine, titolo gigante (fino a 120 px) rivelato **riga per riga** all'apertura, poi testo e pulsanti in dissolvenza. Il gradiente finale sfuma nel nero della sezione successiva, senza stacco.
- **Frase d'impatto a parole che si accendono**: "Almeno 2 giorni di lievitazione. Alta idratazione. Alta digeribilità…" in testo enorme su nero; ogni parola passa da opaca a piena mentre entra nello schermo (CSS `animation-timeline: view()`).
- **Menù come indice tipografico**: le nove categorie sono righe giganti separate da filetti; all'hover/focus una **fascia rosso pomodoro** scorre da sinistra e il testo diventa bianco; a destra il numero di voci.
- **Banda fotografica** a tutta altezza con parallasse ("Una famiglia di pizzaioli") che rimanda alla storia.
- **Contatti** su nero **piatto** con il numero di telefono gigante.
- **Niente gradienti di colore** come decorazione (un tentativo con un bagliore rosso nei contatti è stato scartato: effetto brutto). Sono ammessi solo gli **scrim neri** sopra le foto, necessari per leggere il testo.
- **Header**: trasparente sopra l'hero; dopo 40 px di scroll diventa crema semi-trasparente con sfocatura e resta fisso (piccolo JS). Sulle pagine interne è sempre pieno.
- **Transizioni tra pagine** con la View Transitions API (`@view-transition`), dove supportata.
- **Menù (`/menu`)**: la categoria che si sta leggendo si evidenzia nella barra delle categorie (IntersectionObserver).
- **Palette**: nero inchiostro e crema dominano; il rosso pomodoro è per azioni, hover e bagliore; il blu resta solo nel logo ("82").
- **Titoli** in Bricolage Grotesque extrabold con scale fluide (`clamp`), testo in Inter 17 px; un solo raggio (`rounded-2xl`), pulsanti a pillola.
- **Regole sul movimento**: solo miglioramento progressivo. Senza supporto a `animation-timeline` tutto è visibile e statico; con `prefers-reduced-motion: reduce` non si muove nulla (animazioni dentro `@media (prefers-reduced-motion: no-preference)`). Niente animazioni "a ogni sezione": il movimento è nell'hero, nella frase d'impatto, nelle bande fotografiche e nell'indice del menù.
- **JS**: circa 1 KB (`resources/js/app.js`): classe `js`, header allo scroll, evidenziazione delle categorie. Il sito funziona senza.
- **Foto provvisorie**: l'hero e la banda "famiglia" usano due ritagli della stessa foto di prova (vedi sotto); le altre foto sono ancora segnaposto. Le foto reali dovranno avere soggetto centrale e zone scure o sfocate dove va il testo.

### Pagine interne

Tutte le pagine (menù, storia, contatti) condividono il linguaggio della home: **hero fotografico** con menu trasparente (`x-hero` + sezione `header-overlay`), titolo gigante rivelato riga per riga, **strisce di sfondo** alternate e banda fotografica finale. Componenti riusabili in `resources/views/components/`: `hero` (foto a tutta larghezza con parallasse e scrim), `frase` (testo grande a parole che si accendono), `badge`, `button`, `foto`, `logo`.

**Menù (`/menu`)**: hero con foto → sezione **"In evidenza"** (le pizze con badge, in due riquadri grandi) → barra delle categorie (a capo su mobile, fissa da tablet in su, con evidenziazione della categoria corrente) → una sezione per categoria in **layout editoriale a due colonne** (titolo e descrizione fissi a sinistra da tablet in su, voci a destra con nome, prezzo, ingredienti, nota) → chiusura nera con "Chiama". Sfondi alternati crema/crema scuro.

**Badge** (dati statici, chiave `badge` della voce in `config/menu.php`): `mese` → "Pizza del mese" (rosso pomodoro, stella) e `scelta` → "La più scelta" (blu logo con testo nero, cuore). Compaiono accanto alla voce e la fanno entrare in "In evidenza". Due tipi distinti, scelti per colore e icona oltre che per testo. Quando il menù passerà a Filament, `badge` sarà un campo opzionale della voce (enum con i due valori).

**Storia (`/la-nostra-storia`)**: hero con titolo → frase a parole che si accendono → foto + testo sulla tradizione → tre righe giganti su nero (lievitazione, idratazione, digeribilità) → banda fotografica con invito a guardare il menù o chiamare.

**Contatti (`/contatti`)**: hero con indirizzo → "Chiamaci" (telefono gigante, pulsanti Chiama e mappa) accanto agli orari in tabella semantica a righe grandi.

Il contenuto del menù viene da `config/menu.php` (dati **statici di esempio**, prezzi indicativi): sarà sostituito dal menù dinamico gestito in Filament; la struttura (`nome`, `descrizione`, `voci` con `nome`, `ingredienti`, `nota`, `prezzo`, `badge`) è la base proposta per il modello.

### Nessuno scroll orizzontale

Regola di progetto: mai scroll orizzontale della pagina, né a mobile né a desktop. Accorgimenti: `overflow-x: clip` su `body`; elementi decorativi che sporgono (cerchi, fascia inclinata, scritta del footer) sono contenuti in un wrapper `overflow-hidden`; le categorie del menù vanno **a capo** su mobile (sticky solo da `md` in su) invece di scorrere. Verifica fatta con Playwright a 320, 360, 390, 414, 568, 768, 1024, 1280, 1440 e 1920 px su `/` e `/menu`, provando a forzare lo scroll (`scrollTo`) e cercando contenitori scrollabili.

### Sito multipagina

Il sito non è una one page: Home, Menù (`/menu`), La storia (`/la-nostra-storia`) e Contatti (`/contatti`), con la stessa navigazione in header e footer (voce corrente evidenziata e con `aria-current`). La home è un'anteprima che rimanda alle pagine, non contiene i contenuti completi. Le pagine interne riusano lo stesso linguaggio (titolo grande su fondo crema, strisce di sfondo alternate, foto 4:3 arrotondate, chiusura nera con invito a chiamare).

### Gestione delle immagini (implementata)

- Le foto si cercano come `public/images/{nome}-{640,1280,1920,2560}.{avif,webp}`; il componente Blade `x-foto name="hero"` genera `<picture>` con AVIF e WebP responsive (`srcset`/`sizes`), `width`/`height` letti dal file (niente layout shift), `loading="lazy"` di default e `eager` + `fetchpriority="high"` per l'immagine LCP (hero).
- **Sostituire una foto = copiare i file con lo stesso nome**, senza toccare il codice. Finché i file non esistono compare il segnaposto "Foto provvisoria".
- Generazione delle varianti (esempio con `sharp`, installato fuori dal progetto): per ogni larghezza `resize({ width })` e poi `.webp({ quality: 72 })` e `.avif({ quality: 50 })`. La foto di prova dell'hero pesa 18 KB (AVIF) / 27 KB (WebP) a 640 px e 78 KB (AVIF) / 116 KB (WebP) a 1920 px.
- **Foto provvisoria di prova**: l'hero usa una foto fornita solo "per rendere l'idea". Non è nostra e il repository è pubblico, quindi `public/images/hero-*` è in `.gitignore` e **non va committata**: sul repository l'hero mostra il segnaposto. Va sostituita con una foto del locale (o con diritti chiari) prima del rilascio.
