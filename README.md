# Visciano 82

Sito web della pizzeria-ristorante **Visciano 82** (ex "Mundial 82"): semplice sito multipagina in Laravel, con pannello Filament per gestire i contenuti e in particolare il menù.

Identificativo tecnico del progetto: `mundial` (repo, database, slug).

## Requisiti

- PHP 8.5 e Composer 2
- Node.js 22 e npm
- PostgreSQL 18 (in locale tramite il container Docker `docker-postgres`)
- [Laravel Herd](https://herd.laravel.com) per servire il sito in locale
- Git

Stack: Laravel 13, Filament 5, PostgreSQL, PHPUnit, Pint, Larastan, Vite + Tailwind.

## Setup locale

```bash
git clone git@SanaviaNicolas:SanaviaNicolas/mundial-laravel-001.git
cd mundial-laravel-001
composer install
cp .env.example .env
php artisan key:generate
```

Database (container PostgreSQL già in esecuzione, con utente `postgres`):

```bash
docker exec docker-postgres psql -U postgres -c "CREATE DATABASE mundial" -c "CREATE DATABASE mundial_testing"
```

In `.env` imposta:

- `DB_PASSWORD`: la password locale del tuo container (non va mai committata);
- `ADMIN_PATH`: il prefisso segreto del pannello admin, senza slash iniziale (se vuoto il pannello è disattivato; vedi [ADR 0003](docs/decisioni/0003-pannello-admin.md)).

Poi:

```bash
php artisan migrate
npm install
npm run dev
```

Il sito si serve con Laravel Herd: dalla cartella del progetto lancia `herd link mundial` e aprilo su <http://mundial.test> (`APP_URL` in `.env.example` è già coerente). Il pannello admin è su `/<ADMIN_PATH>`, con login su `/<ADMIN_PATH>/accesso`.

## Comandi

| Comando | Cosa fa |
|---|---|
| `composer test` | Esegue i test (database `mundial_testing`) |
| `composer lint` | Controlla lo stile con Pint (`vendor/bin/pint` per correggere) |
| `composer analyse` | Analisi statica con Larastan (livello 5) |
| `composer check` | Esegue lint, analisi e test |

## Documentazione

Tutta la documentazione è in [`docs/`](docs/README.md). Le regole di lavoro per gli agenti sono in [`CLAUDE.md`](CLAUDE.md).
