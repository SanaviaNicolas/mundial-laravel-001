# Branch e Pull Request

**PROPOSTA, da approvare** (frontend + Nicolas). Finché non è approvata, vale solo per il lavoro frontend; le regole Git già in vigore ([CLAUDE.md](../../CLAUDE.md)) restano valide: commit piccoli e atomici, Conventional Commits in inglese, nessun push da parte degli agenti, `composer check` verde prima di ogni commit.

## Branch

- `main` è sempre verde e deployabile. Nessun commit diretto su `main`.
- Nome: `<area>/<descrizione-breve-in-kebab-case>` con area `frontend`, `backend`, `docs`, `chore`. Esempi: `frontend/layout-base`, `frontend/pagina-menu`, `backend/menu-filament`, `docs/adr-framework`.
- Un branch = un obiettivo. Vita breve (idealmente 1–3 giorni), poi merge e cancellazione.
- Si parte sempre da `main` aggiornato; si fa `rebase` su `main` (non merge di `main` nel branch) prima di aprire la PR e quando `main` avanza.

## Pull Request

- PR verso `main`, **piccole**: indicativamente ≤ 400 righe modificate (esclusi lock file e file generati) e un solo argomento. Se cresce, si spezza.
- Titolo in Conventional Commits; descrizione in italiano con: cosa cambia, perché, come verificare (comandi/pagine), screenshot mobile per le modifiche grafiche, eventuali note per l'altra persona.
- Requisiti per il merge: CI verde (`composer check`), docs aggiornate nello stesso PR, almeno una review dell'altra persona sui file condivisi (vedi sotto).
- Merge con **squash** se i commit del branch sono di lavoro; con merge/rebase se già atomici e puliti. Decisione da prendere insieme.

## File condivisi: come evitare conflitti

Sono i punti dove frontend e backend si toccano. Regola generale: **chi li modifica lo segnala subito all'altro** e li tiene in PR piccole e separate, da mergiare presto.

| File | Rischio | Regola |
|---|---|---|
| `package.json`, `package-lock.json` | Conflitti sul lock | Le dipendenze npm le aggiunge solo il frontend, in una PR dedicata; il backend non tocca npm. Mai modificare il lock a mano. |
| `composer.json`, `composer.lock` | Idem | Le dipendenze PHP le aggiunge il backend, in PR dedicata. Il frontend chiede se serve una libreria PHP. |
| `vite.config.js`, `resources/css/app.css`, `resources/js/app.js` | Configurazione asset | Di proprietà del frontend. Modifiche del backend (es. tema Filament) concordate prima. |
| `resources/views/layouts/*`, componenti Blade | Layout e SEO | Di proprietà del frontend. Il backend passa dati alle viste, non le riscrive. |
| `routes/web.php` | Entrambi aggiungono route | Una route per riga, ordinata; il frontend registra le pagine statiche, il backend quelle dinamiche (menù). PR piccole e rebase frequente. |
| `app/Providers/Filament/*`, `app/Filament/**` | Admin | Di proprietà del backend. |
| `docs/README.md` (indice), `docs/seo/checklist.md` | Entrambi aggiornano | Una riga per voce, modifiche mirate, conflitti risolti subito (sono testo). |
| `tests/` | Test di pagina | File di test per area (es. `HomePageTest`, `MenuPageTest`): niente file condivisi tra aree. |

## Proprietà indicativa

- **Frontend**: `resources/views`, `resources/css`, `resources/js`, `public` (asset non Filament), `vite.config.js`, `package.json`, `docs/design`, `docs/contenuti`.
- **Backend/sistemistica**: `app/`, `database/`, `config/`, `routes/` (insieme), CI, server, deploy, `composer.json`, `docs/sviluppo`, ADR tecnici.
- **Condiviso con review reciproca**: layout/SEO, struttura delle route, dati del menù passati alle viste, ADR.

## Sincronizzazione

- Prima di iniziare un'attività che tocca file condivisi, un messaggio all'altra persona ("tocco layout e `vite.config.js`").
- Il modello dati del menù (campi di categoria e voce) va concordato **prima** che il frontend costruisca le viste: ne dipendono HTML, JSON-LD e test.
