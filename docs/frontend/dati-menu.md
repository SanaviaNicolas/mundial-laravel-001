# Dati del menù: guida per il frontend

Guida per chi progetta e implementa l'interfaccia pubblica (con Claude Code e Claude Design) **senza bisogno di leggere il codice backend**. Spiega *quali dati esistono, cosa significano e come vanno presentati*. Va tenuta aggiornata in ogni step che cambia il menù.

Legenda: **[implementato]** = esiste già nel backend; **[in arrivo]** = previsto, non ancora implementato.

Gli esempi sono inventati o descritti in modo generico: non sono il listino reale.

---

## 1. Struttura del menù [implementato]

Le categorie hanno **due livelli**: *macrocategorie* e, dentro, *sottocategorie*. Una voce di menù appartiene a **una** categoria, che può essere una macro (es. una macro senza sottocategorie) o una sottocategoria.

```
Pizze                      ← macrocategoria (ordine 1)
├── Tradizione napoletana  ← sottocategoria (ordine 1) — descrizione: "con bordo alto"
│     ├── Voce A
│     └── Voce B
├── Classiche
├── Speciali
├── Bianche
└── Chiuse
Baguette                   ← macro SENZA sottocategorie: le voci stanno direttamente qui
│     ├── Voce C
│     └── Voce D
Bibite
├── Alcolici
└── Analcolici
```

- **Ordine**: ogni categoria e ogni voce ha un ordine deciso dal ristorante (drag and drop nell'admin). Il frontend deve rispettarlo, non riordinare alfabeticamente.
- **Visibilità**: ogni categoria e voce può essere nascosta. **Nascondere una macro nasconde anche tutte le sue sottocategorie e le loro voci.** Una voce nascosta non va mai mostrata.
- **Categorie senza voci** o macro che hanno sottocategorie ma nessuna voce visibile: non mostrarle (né nel menù di navigazione né come sezione vuota). Se una macro ha voci proprie *e* sottocategorie, mostra prima le voci proprie e poi le sottocategorie.
- Ogni categoria ha un **nome**, un **identificativo per l'indirizzo (slug)** — pensato per ancore o pagine, es. `tradizione-napoletana` — e una **descrizione facoltativa** (es. "con bordo alto"): se c'è, va mostrata sotto il titolo della categoria.
- Lo slug è **solo italiano** per ora. Quando serviranno URL in inglese sarà da rivedere (slug tradotti, step frontend/i18n).

## 2. Informazioni di ogni voce [implementato]

| Dato | Note per il design |
|---|---|
| **Nome** | Breve (tipicamente 1–3 parole, fino a ~25 caratteri). Può contenere apostrofi e accenti. Non fare affidamento sul maiuscolo: nei dati il maiuscolo non è garantito, usare CSS (`text-transform`) se si vuole. |
| **Descrizione** | Facoltativa, testo breve. Quasi sempre assente per le pizze (le descrive la lista ingredienti). |
| **Note** | Facoltative, brevi. Informazioni accessorie: riconoscimenti, "base fritta", "mezza pizza e mezzo calzone", "servito con pane a fette". Mostrarle come testo secondario, distinguibile dagli ingredienti. |
| **Prezzo** | In **centesimi interi** (850 = 8,50 €). **Può essere assente** finché il ristorante non lo fornisce: in quel caso **non mostrare nulla** (niente "0 €", niente "n.d.", niente spazio vuoto che sembri un errore). |
| **Ingredienti** | Lista **ordinata**; v. sotto. |
| **Tag** | v. sotto. |
| **Aggiunte** | v. sotto. |
| **Allergeni** | v. sezione 3 — regola fondamentale. |

### Immagini: nessuna foto per singolo piatto

Il ristorante **non vuole foto per i singoli piatti**, almeno inizialmente: **il design non deve prevederle sulle voci** (niente miniature, niente card con immagine, niente segnaposto). Il modello dati **non ha campi immagine**. Eventuali immagini **decorative** del sito (testata, sfondi, atmosfera del locale) sono **statiche** e le decide il frontend: non vengono dall'admin. Resta valido il principio SEO: il contenuto del menù è sempre testo in HTML, mai affidato a immagini.

### Ingredienti

Ogni ingrediente di una voce ha:
- **ordine** (quello in cui deve comparire);
- **"a fine cottura"** (sì/no): serve a distinguere ciò che cuoce con la pizza da ciò che si aggiunge dopo.
- **sezione** (facoltativa): una etichetta traducibile che raggruppa gli ingredienti.

L'ingrediente stesso ha un **nome** e un flag **surgelato**: gli ingredienti surgelati vanno mostrati con un **asterisco** (`*`), e in pagina deve esserci una **legenda** ("* prodotto surgelato", tradotta). L'asterisco va dopo il nome dell'ingrediente, nel testo, non in un'icona.

Esempio 1 — pizza semplice con ingredienti in cottura e a fine cottura:

```
Voce "Esempio"
  in cottura:      pomodoro, mozzarella, salsiccia
  a fine cottura:  basilico, grana a scaglie
```
Presentazione suggerita: una riga di testo con gli ingredienti in cottura separati da virgole, poi "A fine cottura: …". Se nessun ingrediente è "a fine cottura", nessuna etichetta.

Esempio 2 — voce **"metà e metà"** (due parti con ingredienti diversi):

```
Voce "Esempio doppio"          note: "mezza pizza e mezzo panuozzo"
  sezione "Mezza pizza":     mozzarella, datterino, [a fine cottura] stracciatella
  sezione "Mezzo panuozzo":  [a fine cottura] rucola, crudo, bufala
```
Gli ingredienti con la stessa sezione vanno raggruppati sotto il suo titolo, nell'ordine dato. Le voci normali non hanno sezioni: in quel caso non mostrare titoli.

Esempio 3 — ingrediente surgelato: `pomodoro, mozzarella, gamberetti*` + legenda a fondo pagina o fondo sezione.

Esempio 4 — **alternative** ("salamino o ciccioli"): non c'è un campo dedicato; la scelta è scritta nelle **note** della voce e gli ingredienti sono tutti elencati. Nessuna logica particolare per il frontend.

### Tag

Etichette applicate alle voci, ognuna con uno **slug stabile** (identificativo che non cambia) e un nome tradotto. Esempi: `vegano`, `vegetariano`, `senza-glutine`, `piccante`, `novita`, `stagionale`.

- Il frontend deve identificare i tag **dallo slug**, mai dal nome.
- Un tag può avere un'icona o un badge nel design. I tag che il ristorante non ha creato semplicemente non esistono: non dare per scontato che esistano tutti.
- Servono anche per i piatti che nascono già senza glutine, senza lattosio o vegani (non per questo hanno un'aggiunta).

### Aggiunte (scelte con supplemento)

Sono scelte facoltative con un **nome** e un **supplemento di prezzo** in centesimi (anche **0**: "senza lattosio", "impasto senza glutine"). Esempi: aggiunta di un ingrediente, cornicione ripieno.

Per ogni voce il backend calcola **quali aggiunte valgono** (default di categoria o di macro, più quelle collegate a quella voce, meno le esclusioni): il frontend **non deve ricalcolarlo**, riceve l'elenco finale per voce, già ordinato e già privato delle aggiunte nascoste.

Esempio: "cornicione ripieno" vale per tutte le pizze tranne i calzoni: la pagina del calzone non lo riceve.

Presentazione: elenco compatto per voce, oppure per categoria se le aggiunte sono uguali per tutte le voci (in tal caso conviene raggrupparle una sola volta in testa alla categoria — il frontend può confrontare gli elenchi). Supplemento 0: mostrare "senza supplemento" o niente, non "+0 €".

## 3. REGOLA ALLERGENI (fondamentale) [implementato]

> **Un elenco di allergeni vuoto NON significa "nessun allergene".** Può voler dire solo che nessuno ha ancora inserito o controllato i dati.

Ogni voce ha uno stato:

| Stato | Cosa fa il sito |
|---|---|
| **Non verificata** (stato iniziale, e stato in cui la voce torna da sola ad ogni modifica di ingredienti o allergeni) | **Non mostra alcun allergene.** Mostra un invito a **chiedere al personale** ("Per informazioni sugli allergeni chiedi al personale"). |
| **Verificata**, con allergeni | Mostra l'elenco degli allergeni. |
| **Verificata**, senza allergeni | Può mostrare "nessun allergene" (solo in questo caso). |

Il backend lo rende esplicito: per ogni voce fornisce `null` se non verificata, altrimenti l'elenco (che può essere vuoto). Il frontend **non deve mai** dedurre "nessun allergene" da una lista vuota o da un dato mancante.

Gli allergeni di una voce sono già **calcolati** dal backend: l'unione di quelli dei suoi ingredienti e di quelli indicati direttamente sulla voce. Il frontend li riceve già uniti.

### Allergeni delle aggiunte

Sono **separati**: ogni aggiunta può portare propri allergeni (es. latte per una "aggiunta formaggio"). Non vanno uniti a quelli della voce: si mostrano come **"con questa aggiunta contiene: …"**, accanto all'aggiunta, quando sono indicati.

**Finché le aggiunte non hanno uno stato di verifica** (oggi manca, è tra le voci della [checklist prima del lancio](../sviluppo/prima-del-lancio.md)):
- accanto alle aggiunte il frontend mostra sempre una **nota generica**, tradotta, tipo "per le aggiunte chiedi al personale" (una volta per voce o per sezione di aggiunte, non ripetuta a ogni riga);
- **l'assenza di allergeni indicati su un'aggiunta NON significa che non ne contenga**: non scrivere mai "senza allergeni" per un'aggiunta e non dedurlo da un elenco vuoto.

Quando le aggiunte avranno la verifica, questa regola sarà allineata a quella delle voci (sezione 3) e la guida aggiornata.

### I 14 allergeni UE (Reg. 1169/2011)

Elenco fisso (non modificabile dall'admin). La **chiave** è stabile (usarla per le icone), i nomi sono tradotti.

| Chiave | Italiano | Inglese |
|---|---|---|
| `gluten` | Cereali contenenti glutine | Cereals containing gluten |
| `crustaceans` | Crostacei | Crustaceans |
| `eggs` | Uova | Eggs |
| `fish` | Pesce | Fish |
| `peanuts` | Arachidi | Peanuts |
| `soybeans` | Soia | Soybeans |
| `milk` | Latte (incluso lattosio) | Milk (including lactose) |
| `nuts` | Frutta a guscio | Tree nuts |
| `celery` | Sedano | Celery |
| `mustard` | Senape | Mustard |
| `sesame` | Semi di sesamo | Sesame seeds |
| `sulphites` | Anidride solforosa e solfiti | Sulphur dioxide and sulphites |
| `lupin` | Lupini | Lupin |
| `molluscs` | Molluschi | Molluscs |

Per il design: servono **14 icone** (o abbreviazioni) coerenti, sempre **accompagnate dal nome in testo** (accessibilità e SEO: il testo non va sostituito dalle sole icone). Mobile: l'elenco deve poter andare a capo, i nomi più lunghi sono "Anidride solforosa e solfiti" e "Cereali contenenti glutine".

## 4. Multilingua it/en [implementato]

- Lingue: **italiano** (riferimento) e **inglese**.
- **Tutti i testi pubblici sono traducibili**: nomi, descrizioni e note delle voci, categorie, ingredienti, sezioni degli ingredienti, tag, allergeni, aggiunte.
- **Fallback sull'italiano**: se l'inglese manca o è vuoto, il backend restituisce il testo italiano. Il frontend non deve gestire testi mancanti.
- Per il layout: l'inglese è spesso più lungo dell'italiano per le descrizioni; previsto spazio per testi di lunghezza diversa nelle due lingue, senza ancorare larghezze al testo italiano. Un testo può comparire in italiano dentro una pagina inglese (fallback): niente assunzioni sulla lingua di un singolo campo.
- Gli slug delle categorie non sono tradotti (per ora).

## 5. Ordine e sezioni speciali [implementato]

- **Novità** e **Stagionale** sono **tag** (slug convenzionali `novita` e `stagionale`), non categorie: la voce resta nella sua categoria normale e in più ha il tag. Una sezione speciale del sito ("Novità", "Di stagione") si costruisce **filtrando le voci per tag**; la stessa voce può quindi comparire in due punti della pagina.
- **Sezione "In evidenza" del sito** [implementato]: raccoglie le voci con uno dei tag di rilievo `pizza-del-mese`, `la-piu-scelta`, `novita`, `stagionale` (`Item::HIGHLIGHTS`), mostrati come badge colorati; la voce compare anche nella sua categoria. Card **senza foto**. Se nessuna voce ha questi tag la sezione non c'è. I badge "Pizza del mese" e "La più scelta" sono quindi tag, non campi dedicati ([ADR 0010](../decisioni/0010-contratto-dati-menu.md)).
- Il ristorante può creare altri tag (es. "piccante") con lo stesso meccanismo: sul sito compaiono come piccole etichette accanto alla voce.
- Il contenuto **cambia spesso** (voci nascoste e riattivate, nuovi prezzi, stagionali): il design non deve dipendere da un numero fisso di voci o categorie, e le sezioni speciali possono essere vuote (in tal caso non mostrarle).

## 6. Ordini di grandezza utili al design (dal listino attuale)

- Circa **60–70 pizze** divise in 5 sottocategorie (da 4 a ~25 voci ciascuna), più **baguette** (~5), **panuozzi** (~5) e **tegamini** (~4). Bibite, dolci e ristorante arriveranno con dati propri.
- **Nomi** brevi (fino a ~25 caratteri). **Ingredienti per voce**: da 2 a circa 9; le liste più lunghe arrivano a **100–150 caratteri** di testo. I singoli nomi di ingrediente sono di solito brevi, ma alcuni sono lunghi (fino a ~45 caratteri, con denominazioni tipo "D.O.P.").
- La **pagina del menù sarà molto lunga** (decine di voci per categoria): serve una **navigazione tra categorie** efficace su mobile (indice o barra di ancore che resta visibile, link alle categorie, ritorno in cima), titoli di categoria ben riconoscibili e tempi di caricamento contenuti (immagini ottimizzate, nessun contenuto solo-JS).
- **Mobile-first**: è il device principale. Le liste di ingredienti devono andare a capo senza scroll orizzontale; target touch adeguati per le ancore e per i filtri per tag.
- Il prezzo può mancare per molte voci all'inizio: il layout non deve avere una colonna "prezzo" che appaia vuota.

## 7. Altre informazioni dinamiche previste [in arrivo — NON ancora implementate]

Una pagina **Impostazioni** nel pannello (chiave/valore, con valore JSON) conterrà ciò che non è menù: **orari di apertura**, **chiusure straordinarie**, **contatti**, **indirizzo**, **social**, ecc. **Oggi non esiste ancora**: serve solo per progettare in anticipo i punti del sito che li useranno (header/footer, pagina contatti, dati strutturati).

Esempio **indicativo** di JSON per gli orari (la forma definitiva sarà fissata quando lo implementeremo; gli orari qui sono inventati):

```json
{
  "orari": {
    "lunedi":    [],
    "martedi":   [{ "dalle": "12:00", "alle": "14:30" }, { "dalle": "19:00", "alle": "23:00" }],
    "mercoledi": [{ "dalle": "19:00", "alle": "23:00" }]
  },
  "chiusure_straordinarie": [
    { "dal": "2026-08-10", "al": "2026-08-20", "motivo": { "it": "Ferie", "en": "Holidays" } }
  ]
}
```

Da prevedere nel design: "aperto/chiuso ora", giorni di chiusura, più fasce nello stesso giorno, chiusure straordinarie con motivo tradotto.

**Chiusure straordinarie: frontend già pronto** [implementato con dati da config]: `App\Support\Closures::notice()` legge `config('site.closures')` con la forma qui sopra (`dal`, `al`, `motivo` tradotto) e restituisce l'avviso da mostrare: da 30 giorni prima dell'inizio ("Ferie: chiusi dal 10 al 20 agosto.") fino all'ultimo giorno ("Ferie: chiusi fino al 20 agosto, riapriamo il 21 agosto."), poi niente. Il sito lo mostra in una fascia oro in cima a ogni pagina (`role="status"`), sopra gli orari nei contatti e nel footer. Quando la pagina Impostazioni esisterà, `Closures` leggerà da lì: le viste non cambiano.

## 8. Come il frontend riceve i dati [implementato]

Il sito è renderizzato lato server (Blade): il contenuto è in HTML, leggibile senza JavaScript. Le viste ricevono il menù come **contratto dati** (`app/Menu`, [ADR 0010](../decisioni/0010-contratto-dati-menu.md)): un elenco di `Section` (slug, nome, descrizione, voci, sottocategorie) con `Item` (nome, descrizione, note, prezzo in centesimi o `null`, ingredienti con surgelato/a fine cottura/sezione, tag `slug => nome`, aggiunte con supplemento e allergeni propri, allergeni `null` se non verificati). Helper per la presentazione: `Section::leaves()` (categorie con voci, per la navigazione), `Section::sharedAddons()` (aggiunte uguali per tutte le voci, mostrate una volta), `Section::allergensUnverified()` (invito unico a chiedere al personale), `Item::ingredientGroups()` (ingredienti raggruppati per sezione e divisi "in cottura" / "a fine cottura"), `Price::format()`.

La sorgente si sceglie con `MENU_SOURCE`: `static` (oggi, `config/menu.php`, dati di esempio con la stessa struttura del pannello e allergeni mai verificati; comprende anche **Dolci** e **Bevande** con le sottocategorie Bibite analcoliche, Birre e Vini: voci senza ingredienti, con il formato nella descrizione; tutte le voci sono provvisorie e in parte inventate) o `database` (il pannello Filament). La pagina non cambia passando dall'una all'altra.

**Contratto che il frontend dà per scontato** (garantito da entrambe le sorgenti):
- le categorie e le voci arrivano **già filtrate per visibilità** (una macro nascosta nasconde il suo sottoalbero) e **già ordinate**;
- i testi arrivano **già nella lingua richiesta**, con fallback sull'italiano;
- gli **allergeni sono già gestiti secondo la regola della sezione 3** (nessun allergene mai mostrato per voci non verificate);
- le **aggiunte** arrivano già calcolate per voce, senza quelle nascoste;
- le **categorie vuote** (anche le macro con tutte le sottocategorie vuote) non ci sono.

**Cosa esiste oggi nel dominio** (metodi su cui costruire quel livello; nomi indicativi di concetti, non serve conoscere i campi):
- elenco categorie/voci "visibili" e "ordinate" (la visibilità considera l'intera catena di categorie);
- testo tradotto con fallback per ogni campo traducibile;
- ingredienti di una voce in ordine, con flag "a fine cottura", sezione e flag "surgelato";
- tag di una voce (con slug);
- **aggiunte effettive** di una voce (solo visibili);
- **allergeni pubblici** di una voce (assenti se non verificata, altrimenti elenco, anche vuoto) e **allergeni delle aggiunte** (a parte, per aggiunta);
- prezzo in centesimi (assente se non noto).

## 9. Collegamento con la SEO [implementato]

I **dati strutturati schema.org** (JSON-LD) per il menù sono generati **dagli stessi dati** della pagina (`App\Support\StructuredData`, dal contratto `app/Menu`), così non possono discordare:

- `Menu` → `MenuSection` (macrocategoria → sezione, sottocategoria → sezione annidata) → `MenuItem` (voce);
- per ogni `MenuItem`: nome, descrizione, prezzo (`offers` con `price` in euro e `priceCurrency` EUR, solo se il prezzo c'è), `suitableForDiet` dai tag (es. vegano, senza glutine) quando pertinente;
- orari di apertura (`openingHoursSpecification`) e dati del ristorante (`Restaurant`/`Pizzeria`) dalle Impostazioni (sezione 7);
- gli allergeni **non** hanno una proprietà dedicata nello standard e restano **solo in HTML**, e solo se verificati (sezione 3).

Cosa serve al markup (SEO e motori generativi):
- **tutto il contenuto del menù in HTML testuale** (nomi, ingredienti, note, prezzi, allergeni verificati): **mai solo in immagini** o in elementi che richiedono JS;
- titoli di categoria come heading con **gerarchia corretta** (un solo H1 per pagina);
- una sola fonte dei dati per pagina e dati strutturati coerenti col testo visibile;
- se il menù avrà pagine per categoria o per lingua: `title`, meta description, canonical e `hreflang` per pagina.

Stato delle voci SEO: [checklist](../seo/checklist.md).
