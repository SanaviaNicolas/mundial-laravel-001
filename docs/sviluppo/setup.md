# Setup e sviluppo

Il setup passo-passo è nel [README](../../README.md). Qui i dettagli che servono a chi sviluppa.

## Database

- Sviluppo: database `mundial`; test: `mundial_testing`. Entrambi sul container Docker `docker-postgres` (PostgreSQL 18, `127.0.0.1:5432`).
- Le credenziali stanno solo in `.env` (ignorato da git). `.env.example` contiene solo segnaposto.
- `phpunit.xml` forza `DB_CONNECTION=pgsql` e `DB_DATABASE=mundial_testing`: i test non toccano mai il database di sviluppo. Host, utente e password arrivano da `.env` (in CI dalle variabili d'ambiente).

## Test e qualità

- Test: PHPUnit con `RefreshDatabase` attivo nel `TestCase` base.
- Lint: Laravel Pint (preset `laravel`, di default).
- Analisi statica: Larastan livello 5 su `app`, `routes`, `database`, `tests` (`phpstan.neon`).
- `composer check` esegue tutto ed è il requisito per ogni commit.

## Locale

`APP_LOCALE=it`, `APP_FALLBACK_LOCALE=it`, timezone `Europe/Rome` (impostata in `config/app.php`).
