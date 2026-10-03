# Il menù nel pannello di amministrazione

Guida per chi gestisce i contenuti (non serve conoscere il codice). Il pannello è in italiano; tutti i testi pubblici si scrivono in **italiano** (obbligatorio) e in **inglese** (facoltativo: se manca, il sito mostra l'italiano). Nei moduli ogni testo ha due schede, "Italiano" e "Inglese".

Le voci si trovano nel gruppo **Menù** della barra laterale. In tutte le liste si può cercare, ordinare per colonna e, dove previsto, riordinare trascinando le righe.

## Tag

I tag sono etichette da applicare alle voci: *vegano*, *vegetariano*, *senza glutine*, *piccante*, *novità*, *stagionale*… Servono anche per i piatti che nascono già senza glutine, senza lattosio o vegani.

- Ogni tag ha un **identificativo (slug)**: lettere minuscole, numeri e trattini (es. `senza-glutine`). Si propone da solo dal nome; **dopo la creazione non si può più cambiare**, perché il sito lo usa per riconoscere il tag.
- "Novità" e "Stagionale" sono tag come gli altri: la voce resta nella sua categoria e in più ha il tag. Le sezioni speciali del sito si costruiranno dai tag.
- Un tag si può eliminare dalla pagina di modifica: le voci che lo avevano lo perdono.

## Ingredienti

Gli ingredienti si creano una volta sola e si riusano in tutte le voci (es. "fior di latte", "basilico").

- **Surgelato**: se attivo, sul sito l'ingrediente compare con un asterisco e in pagina c'è la legenda dei surgelati.
- **Allergeni**: si spuntano quelli contenuti nell'ingrediente (sono i 14 allergeni del Regolamento UE 1169/2011, elenco fisso). **Attenzione:** se si cambiano gli allergeni di un ingrediente, la verifica degli allergeni di *tutte* le voci che lo usano viene azzerata e va rifatta (vedi "Allergeni" più avanti).
- Varianti come "fior di latte a fette" o "fior di latte di Agerola" sono ingredienti diversi.
- Un ingrediente usato in almeno una voce non si può eliminare. La colonna "Voci" dice in quante è usato.

## Aggiunte

Le aggiunte sono scelte facoltative con un eventuale supplemento di prezzo: aggiunta di un ingrediente, cornicione ripieno, impasto senza glutine, senza lattosio…

- **Supplemento**: si scrive in euro (es. `1,50`); può essere anche `0` per le scelte senza costo.
- **Dove valgono**: si spuntano le categorie per cui l'aggiunta vale **di default per tutte le voci**. Scegliendo una macrocategoria (es. "Pizze") vale anche per tutte le sue sottocategorie; scegliendo solo una sottocategoria vale solo per quella. Dal modulo di una singola voce si possono poi *escludere* aggiunte ereditate (es. "cornicione ripieno" su tutte le pizze tranne i calzoni) o aggiungerne altre solo per quella voce.
- **Allergeni dell'aggiunta**: si spuntano quelli che l'aggiunta porta con sé (es. latte per "aggiunta bufala"). Il sito li mostra a parte ("con questa aggiunta contiene…") e **non** li somma agli allergeni della voce. Se non si spunta nulla, non significa che l'aggiunta sia priva di allergeni: l'aggiunta non ha una verifica propria.
- **Visibile sul sito** e **ordine**: si trascinano le righe nella lista per cambiare l'ordine in cui compaiono.

## Voci di menù (piatti e bevande)

Ogni voce appartiene a una categoria (una macrocategoria o una sottocategoria). Le voci non hanno una pagina propria.

- **Nome, descrizione, note**: in italiano e in inglese. Le note sono per le informazioni accessorie: riconoscimenti, "base fritta", "mezza pizza e mezzo calzone", "servito con pane a fette"…
- **Prezzo**: in euro. **Si può lasciare vuoto** finché non è noto: sul sito semplicemente non compare.
- **Ingredienti**: si scelgono dall'elenco degli ingredienti, **nell'ordine in cui devono comparire** (si trascinano). Per ognuno si può indicare *A fine cottura / dopo la cottura*. Se manca un ingrediente va creato prima nella sezione Ingredienti (con i suoi allergeni).
- **Voci in due parti** (es. metà pizza e metà panuozzo, ciascuna con i suoi ingredienti): per ogni ingrediente si può scrivere una **sezione** ("Mezza pizza", "Mezzo panuozzo"); gli ingredienti con la stessa sezione vengono raggruppati sul sito. Per le voci normali la sezione si lascia vuota.
- **Alternative** ("salamino o ciccioli"): non esiste un campo apposito. Si aggiungono entrambi gli ingredienti (così gli allergeni sono completi) e si scrive la scelta nelle note.
- **Tag**: *vegano*, *piccante*, *novità*, *stagionale*… La voce resta nella sua categoria; il tag serve per filtri e sezioni speciali.
- **Aggiunte**: nel modulo si vede l'elenco delle aggiunte valide per la voce. Si possono *escludere* quelle ereditate dalla categoria e aggiungerne di *extra* solo per quella voce.
- **Visibile sul sito**: si può nascondere una voce senza cancellarla.

Nella lista delle voci si può cercare per nome e filtrare per **categoria** (scegliendo una macrocategoria compaiono anche le voci delle sue sottocategorie), **tag**, **visibilità** e **allergeni verificati / da verificare**.

## Allergeni (regola fondamentale)

Gli allergeni sono i **14 del Regolamento UE 1169/2011** (elenco fisso, non modificabile). Gli allergeni di una voce sono **calcolati**: sono l'unione di quelli dei suoi ingredienti e di quelli indicati direttamente sulla voce (utili per bevande o per ciò che non viene dagli ingredienti, come il glutine dell'impasto). Nel modulo della voce si vede l'anteprima.

**Un elenco vuoto NON significa "nessun allergene".** Può voler dire solo che nessuno ha ancora inserito i dati. Per questo ogni voce ha uno stato:

- **Non verificati** (stato di partenza): il sito **non mostra gli allergeni** e invita a chiedere al personale.
- **Verificati**: il ristorante ha controllato e confermato che l'elenco è completo. Solo da questo momento il sito mostra gli allergeni (e, se l'elenco è vuoto, "nessun allergene").

Come si verifica: dalla pagina della voce (o dalla lista) si usa **Conferma allergeni**; per ritirare la conferma, **Annulla verifica allergeni**.

**La verifica si azzera da sola** (la voce torna "da verificare") quando:
- si aggiunge, si toglie o si cambia un ingrediente della voce (non basta riordinarli o cambiare "a fine cottura");
- si cambiano gli allergeni diretti della voce;
- si cambiano gli allergeni di un ingrediente: in questo caso si azzerano **tutte** le voci che lo usano.

Dopo ogni modifica del genere bisogna quindi riverificare le voci interessate (usa il filtro "Da verificare" nella lista delle voci).

**Allergeni delle aggiunte**: sono separati. Il sito li mostra come "con questa aggiunta contiene…" e non li somma a quelli della voce. Le aggiunte non hanno uno stato di verifica: se per un'aggiunta non è indicato nessun allergene, non va presentata come "senza allergeni".
