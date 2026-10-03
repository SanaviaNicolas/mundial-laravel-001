# 0002 — Test e qualità del codice

**Stato:** accettata

## Decisione
- **PHPUnit** come test runner: è quello fornito di default dallo skeleton di Laravel 13 (Pest non è il default, quindi non è stato aggiunto).
- Test su database PostgreSQL dedicato (`mundial_testing`) con `RefreshDatabase`, per avere lo stesso motore della produzione ed evitare differenze con SQLite.
- **Pint** con preset `laravel` per lo stile.
- **Larastan livello 5**: livello ragionevole per un progetto nuovo (controlla chiamate a metodi, proprietà, tipi di ritorno e argomenti senza imporre annotazioni pesanti); si può alzare con la crescita del codice.
- Script composer: `test`, `lint`, `analyse`, `check`.
- Sviluppo TDD: prima il test che fallisce, poi il codice.

## Conseguenze
Nessuna dipendenza oltre a quelle già incluse nello skeleton e a Larastan.
