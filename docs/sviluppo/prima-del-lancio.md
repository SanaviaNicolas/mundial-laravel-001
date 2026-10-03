# Checklist prima del lancio

Elenco delle cose **reali, già emerse**, da completare o controllare prima di mettere il sito in produzione. Si aggiorna man mano. Stato: ✅ fatto · 🟡 parziale / da verificare in produzione · ⬜ da fare.

## Contenuti e dati

| Voce | Stato | Note |
|---|---|---|
| Allergeni **verificati dal ristorante per ogni voce** | ⬜ | Finché una voce non è verificata, il sito non ne mostra gli allergeni e invita a chiedere al personale. Usare il filtro "Da verificare" nella lista Voci. Vedi [guida contenuti](../contenuti/menu.md) |
| **Stato di verifica allergeni anche per le aggiunte** | ⬜ | **Oggi manca** (funzionalità da sviluppare): nel frattempo il frontend mostra la nota "per le aggiunte chiedi al personale". Vedi [guida frontend](../frontend/dati-menu.md) |
| **Prezzi** inseriti per tutte le voci e le aggiunte | ⬜ | Un prezzo mancante non viene mostrato: controllare che non sia una dimenticanza |
| **Dati legali del footer** confermati dal ristorante (ragione sociale, P.IVA, ecc.) | ⬜ | Di solito obbligatori nel footer del sito di un'attività: da verificare con il ristorante e, se serve, con il commercialista |
| Dati reali del menù caricati (seeder dedicato) | ⬜ | Arriveranno con i dati strutturati dal ristorante; il seeder sarà indipendente da `AllergenSeeder` |

## Deploy e ambiente

| Voce | Stato | Note |
|---|---|---|
| **`AllergenSeeder` nello script di deploy** | ⬜ | `php artisan db:seed --class=AllergenSeeder --force`, dopo `php artisan migrate --force`, a ogni deploy (è idempotente), quindi nello script di deploy di Laravel Forge |
| `noindex` solo fuori production e `robots.txt` di production | 🟡 | Implementato e testato (ADR 0004): controllare in produzione che `APP_ENV=production` e che `robots.txt` indichi la sitemap e non blocchi il sito |
| **`ADMIN_PATH` reale** impostato solo nell'ambiente | ⬜ | Mai nel repository né nei documenti (ADR 0003); se vuoto il pannello è disattivato |
| Creazione degli **utenti admin** con password sicure | ⬜ | `php artisan make:filament-user` sul server; password lunghe e uniche, gestite in un password manager |
| **Rate limit sul login** | 🟡 | Filament limita già i tentativi di login (5 tentativi, controllato nel sorgente di Filament 5). Il limite si basa sull'IP: richiede i proxy fidati (voce sotto) per funzionare dietro Cloudflare. Valutare un'ulteriore protezione su Cloudflare |
| Informativa **privacy e cookie** | ⬜ | Dipende dalle risorse esterne scelte nel frontend (font, mappe, social, statistiche): decidere quando si sceglie il frontend |

## Cloudflare, rete e backup

| Voce | Stato | Note |
|---|---|---|
| **Cloudflare con SSL "Full (strict)"** | ⬜ | Richiede un certificato valido sul server di origine |
| **Proxy fidati in Laravel** per vedere gli IP reali | ⬜ | Senza, dietro Cloudflare tutte le richieste sembrano arrivare dagli stessi indirizzi (e il limite di login e i log sbagliano). Si configura in Laravel con gli intervalli IP di Cloudflare |
| **Policy sui crawler AI** da decidere | ⬜ | Decidere con il ristorante se consentirli o no e scriverlo in `robots.txt`. Attenzione alle impostazioni di Cloudflare, che possono **bloccare i crawler AI** o **aggiungere regole al `robots.txt`**: la policy reale è la somma delle due cose |
| **Backup del database** | ⬜ | Il database sta sulla **stessa droplet** dell'applicazione: se la droplet si perde, si perde tutto. Backup periodici fuori dalla droplet e prova di ripristino |
