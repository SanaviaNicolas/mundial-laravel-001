# 0001 — Stack e identificativi

**Stato:** accettata

## Contesto
Sito multipagina per una pizzeria-ristorante, con pannello per gestire contenuti e menù. Sviluppo in due: backend e frontend.

## Decisione
- PHP 8.5, Laravel 13, Filament 5, PostgreSQL 18.
- Identificativo tecnico `mundial` (repo, database); nome pubblico "Visciano 82" (`APP_NAME`, titoli, contenuti).
- Locale `it`, timezone `Europe/Rome`.
- Filament 5 richiede `php ^8.2` e `illuminate ^11.28|^12|^13`: compatibile con PHP 8.5 e Laravel 13.
- Il framework frontend non è ancora scelto: per ora Vite + Tailwind di default. Proposta e confronto in [ADR 0008](0008-framework-frontend.md).
- Non si installa Laravel Boost (lo skeleton ne suggerisce l'uso in `CLAUDE.md`/`AGENTS.md`): dipendenza non necessaria; quei file sono stati sostituiti.

## Conseguenze
Tutte le versioni sono verificate sulla documentazione ufficiale al momento dell'installazione.
