# Contesto e decisioni di progetto

Registro delle decisioni **di prodotto, di organizzazione, di infrastruttura e di contenuto** che non sono scelte tecniche con alternative (quelle sono negli [ADR](../README.md#decisioni-tecniche-adr)). Per ogni argomento c'è solo ciò che non è già descritto altrove; la tabella in fondo rimanda alle altre pagine. La documentazione è pubblica: qui compaiono i **nomi dei servizi**, mai indirizzi, credenziali o percorsi riservati.

## Il progetto

- Sito multipagina della pizzeria-ristorante **"Visciano 82"** (ex "Mundial 82"). Il nome attuale è dal cognome del proprietario.
- Identificativo tecnico: **`mundial`** (repository, database, slug, nomi dei servizi). Il nome mostrato al pubblico è "Visciano 82".
- **SEO**: molte persone cercano ancora il vecchio nome, quindi **"Mundial 82" va tenuto visibile nei contenuti** del sito, nella forma **"ex Mundial 82"** (titoli, testi, dati strutturati dove pertinente). Voce in [checklist SEO](../seo/checklist.md).

## Team e metodo

- **Nicolas**: backend e sistemistica. **Francesco**: frontend.
- Entrambi lavorano **direttamente su `main`**, con commit piccole. **`git pull --rebase` prima di iniziare a lavorare e prima di pushare**; la **CI deve essere verde**.
- **Il push lo fa sempre la persona, mai Claude Code.**
- Sviluppo con **Claude Code**: skill superpowers (progettazione e piano prima di implementare), **TDD** (prima il test), verifica prima di dichiarare finito (anche su clone pulita), **riepilogo finale** breve a ogni task e **documentazione aggiornata** nello stesso commit. Le regole operative sono in [`CLAUDE.md`](../../CLAUDE.md); il flusso Git in [setup](../sviluppo/setup.md#workflow-git).

## Infrastruttura prevista

Servizi scelti (non ancora tutti configurati; i dettagli riservati non vanno in questa documentazione):

| Servizio | Ruolo |
|---|---|
| **Cloudflare** | DNS del dominio e protezione (voci SSL, proxy e crawler AI nella [checklist prima del lancio](../sviluppo/prima-del-lancio.md)) |
| **IONOS** | Registrazione del dominio |
| **Laravel Forge** | Deploy dell'applicazione (nello script di deploy va incluso `AllergenSeeder`, vedi checklist) |
| **DigitalOcean** (droplet) | Ospita applicazione **e database** sulla stessa macchina: **i backup del database sono da pianificare** (voce in checklist) |
| **Laravel Herd** | Ambiente locale: <https://mundial-laravel-001.test> (vedi [setup](../sviluppo/setup.md)) |

## Sicurezza dell'admin

- Il pannello è su un **percorso non di default e segreto**, letto da una variabile d'ambiente (mai nel repository né nella documentazione): [ADR 0003](../decisioni/0003-pannello-admin.md).
- **Per ora niente 2FA**: solo **password sicure**. Valutabile in seguito.
- **Rate limit sul login da sistemare prima del lancio** (Filament limita già i tentativi; resta da verificare con Cloudflare): [checklist prima del lancio](../sviluppo/prima-del-lancio.md).

## Menù: decisioni di prodotto

Le regole sul modello dati e sulla gestione sono negli ADR 0006 e 0007 e nella [guida contenuti](../contenuti/menu.md). Qui le decisioni sull'organizzazione del menù reale:

- **Pizze** è una macrocategoria; **le sezioni del listino sono le sue sottocategorie** (tradizione napoletana, classiche, speciali, bianche, chiuse).
- **Baguette**, **Panuozzi** e **Tegamini** sono **macrocategorie a sé** (le loro voci stanno direttamente nella macro).
- **Niente foto per singolo piatto**, almeno inizialmente: il design non le prevede e il modello dati non ha campi immagine. Eventuali immagini decorative sono statiche e le decide il frontend ([guida frontend](../frontend/dati-menu.md)).

## Impostazioni del sito

Decisione: due tipi di impostazioni, nella stessa tabella (chiave + valore JSON):
- **fisse**, con componenti dedicati nel pannello: **contatti e dati del locale**, **orari di apertura**, **link e social**;
- **generiche** chiave/valore JSON, **per uso tecnico**.

Stato: **decisa, in sviluppo** (step 3). Quando sarà implementata avrà il suo ADR e la sua pagina in `contenuti/`.

## Frontend

- Il **framework frontend NON è ancora deciso**: la scelta è **rimandata a uno step dedicato, con Francesco**. Per ora Vite + Tailwind di default.
- **Priorità assolute**: **SEO** (comprese le pratiche AI-friendly) e **mobile-first**: [checklist SEO](../seo/checklist.md).
- **Risorse di terze parti** (font, mappe, analytics, social incorporati…): **da decidere in modo esplicito**, una per una, perché pesano su prestazioni, privacy e cookie (informativa: voce nella [checklist prima del lancio](../sviluppo/prima-del-lancio.md)). Nessuna va aggiunta "di default".
- **Cookie e pagine legali** (decisione del 10/10/2026): il sito usa **solo cookie tecnici** (sessione e `XSRF-TOKEN` di Laravel) e nessuna risorsa di terzi, quindi **niente banner dei cookie**. Ci sono comunque una **privacy policy** (`/privacy`) e una **cookie policy** (`/cookie`), linkate nel footer insieme a copyright e P.IVA. I testi sono una **bozza da far validare** al ristorante (o al suo consulente); i dati legali mancanti compaiono come "[dato da completare]". La mappa dei contatti è un'**immagine statica ospitata da noi** (non una mappa incorporata) proprio per restare senza terze parti. Se in futuro si aggiunge una terza parte (mappa incorporata, statistiche, social incorporati), vanno riviste entrambe le informative e valutato il banner.

## Già documentato altrove

| Argomento | Dove |
|---|---|
| Lingue: italiano (riferimento) e inglese, fallback sull'italiano | [ADR 0005](../decisioni/0005-traduzioni.md) |
| Struttura delle categorie, tag "Novità"/"Stagionale", aggiunte con esclusioni, un solo prezzo per voce, allergeni solo se verificati, asterisco per i surgelati | [ADR 0006](../decisioni/0006-modello-dati-menu.md), [guida contenuti](../contenuti/menu.md), [guida frontend](../frontend/dati-menu.md) |
| Le migration contengono solo struttura; i dati di riferimento passano da seeder idempotenti o comandi artisan | [`CLAUDE.md`](../../CLAUDE.md), [setup](../sviluppo/setup.md#dati-di-riferimento) |
| Stack, test e qualità, SEO e ambienti non production | [ADR 0001](../decisioni/0001-stack.md), [0002](../decisioni/0002-testing-e-qualita.md), [0004](../decisioni/0004-seo-ambienti-non-production.md) |
