# Setup e sviluppo

Il setup passo-passo è nel [README](../../README.md). Qui i dettagli che servono a chi sviluppa.

## Server locale

Il sito si serve con [Laravel Herd](https://herd.laravel.com), non con `php artisan serve`: dalla cartella del progetto si lanciano `herd link` e `herd secure` (senza passare un nome: il sito prende il nome della cartella) e il sito risponde su <https://mundial-laravel-001.test> (`APP_URL=https://mundial-laravel-001.test`). `npm run dev` avvia Vite per gli asset.

## Workflow Git

Si lavora direttamente su `main`, con commit piccole. Prima di pushare si esegue `git pull --rebase` e `composer check` deve essere verde; dopo il push la CI deve diventare verde. `composer check` va provato anche su una clone pulita (`git clone` in una cartella temporanea, copia di `.env`, `composer install`, `composer check`): la CI parte solo dai file tracciati da git, quindi file o cartelle vuote presenti solo in locale non ci sono.

## Pannello admin

Il prefisso del pannello è nella variabile `ADMIN_PATH` di `.env` (senza slash iniziale), il login è su `/<ADMIN_PATH>/accesso`. Il valore reale non è nel repository: chiedilo al responsabile del progetto. Se `ADMIN_PATH` è vuoto il pannello è disattivato (404 su tutti gli URL del pannello). I test usano `ADMIN_PATH` definito in `phpunit.xml` e leggono sempre `config('admin.path')`.

## Database

- Sviluppo: database `mundial`; test: `mundial_testing`. Entrambi sul container Docker `docker-postgres` (PostgreSQL 18, `127.0.0.1:5432`).
- Le credenziali stanno solo in `.env` (ignorato da git). `.env.example` contiene solo segnaposto.
- `phpunit.xml` forza `DB_CONNECTION=pgsql` e `DB_DATABASE=mundial_testing`: i test non toccano mai il database di sviluppo. Host, utente e password arrivano da `.env` (in CI dalle variabili d'ambiente).

## Dati di riferimento

Le migration creano solo la struttura, mai dati. I dati di riferimento si caricano con seeder idempotenti (si possono rilanciare senza creare duplicati), da eseguire **dopo `migrate`** in locale e a ogni deploy (anche in production):

```bash
php artisan db:seed --class=AllergenSeeder --force
```

- `AllergenSeeder`: i 14 allergeni del Reg. UE 1169/2011 (elenco fisso, non modificabile dall'admin). È indipendente dal futuro seeder del menù.
- CI: il workflow esegue solo `composer check`; i test che hanno bisogno degli allergeni li caricano esplicitamente.

## Test e qualità

- Test: PHPUnit con `RefreshDatabase` attivo nel `TestCase` base.
- Lint: Laravel Pint (preset `laravel`, di default).
- Analisi statica: Larastan livello 5 su `app`, `routes`, `database`, `tests` (`phpstan.neon`).
- `tests/Feature/DocsTest.php` controlla che i link interni dei file Markdown (README e `docs/`) non siano rotti e che ogni documento sia elencato in `docs/README.md`.
- `composer check` esegue tutto ed è il requisito per ogni commit.

## Locale

`APP_LOCALE=it`, `APP_FALLBACK_LOCALE=it`, timezone `Europe/Rome` (impostata in `config/app.php`).

## CI

La CI (GitHub Actions, `.github/workflows/ci.yml`) serve a controllare in modo automatico e uguale per tutti che il codice sia sano: a ogni push su `main` e a ogni pull request installa il progetto su una macchina pulita (PHP 8.5, PostgreSQL 18 con database `mundial_testing`) ed esegue `composer check`, cioè lint, analisi statica e test.

Se fallisce, la modifica non va unita a `main`. Intercetta ciò che in locale può passare inosservato (dipendenze mancanti, `.env` diverso, test che dipendono dalla propria macchina).

La password del Postgres di CI (`postgres`) è usa e getta e vale solo dentro il job; `ADMIN_PATH` per i test arriva da `phpunit.xml`. Gli asset non vengono compilati: i test non ne hanno bisogno (`withoutVite()`).
