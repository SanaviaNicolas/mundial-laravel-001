# Visciano 82

Sito web della pizzeria-ristorante **Visciano 82** (ex "Mundial 82"): semplice sito multipagina in Laravel, con pannello Filament per gestire i contenuti e in particolare il menù.

Identificativo tecnico del progetto: `mundial` (repo, database, slug).

## Requisiti

- PHP 8.5 e Composer 2
- Node.js 22 e npm
- PostgreSQL 18 (in locale tramite il container Docker `docker-postgres`)
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

Imposta in `.env` il valore di `DB_PASSWORD` (la password locale del tuo container; non va mai committata), poi:

```bash
php artisan migrate
npm install
npm run dev        # oppure: composer dev
```

Il sito è su <http://localhost:8000> (`php artisan serve`), il pannello admin su `/admin`.

## Comandi

| Comando | Cosa fa |
|---|---|
| `composer test` | Esegue i test (database `mundial_testing`) |
| `composer lint` | Controlla lo stile con Pint (`vendor/bin/pint` per correggere) |
| `composer analyse` | Analisi statica con Larastan (livello 5) |
| `composer check` | Esegue lint, analisi e test |

## Documentazione

Tutta la documentazione è in [`docs/`](docs/README.md). Le regole di lavoro per gli agenti sono in [`CLAUDE.md`](CLAUDE.md).
