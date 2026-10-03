# 0003 — Pannello admin Filament

**Stato:** accettata (aggiornata nello step 1.1)

## Decisione
- Filament 5.9 con un unico panel `admin` (provider `app/Providers/Filament/AdminPanelProvider.php`), login Filament.
- Per ora nessuna risorsa e nessun utente: gli utenti admin verranno gestiti con la prima feature di dominio.
- **Niente URL di default.** Il percorso predefinito di Filament (`/admin`, `/admin/login`) è noto ai bot che cercano pannelli da attaccare. Il pannello sta quindi su un prefisso segreto, con slug del login personalizzato (`/<prefisso>/accesso`, via `Panel::path()` e `Panel::loginRouteSlug()`).
- **Il prefisso non è nel repository** (repo e `/docs/` sono pubblici): arriva dalla variabile d'ambiente `ADMIN_PATH`, letta in `config/admin.php` (`config('admin.path')`). Il valore reale sta solo nel `.env` di ogni ambiente; `.env.example` ha il valore vuoto; i test usano un valore fittizio definito in `phpunit.xml`.
- **Variabile mancante = pannello disattivato**, mai fallback a un percorso noto: se `ADMIN_PATH` è vuoto il provider non registra il panel e ogni URL del pannello risponde 404 (test dedicato). Gli altri comandi artisan (es. `composer install`) continuano a funzionare.
- I test leggono sempre il percorso da `config('admin.path')`, senza valori scritti nel codice.
- Il panel non deve mai essere indicizzato: vedi [ADR 0004](0004-seo-ambienti-non-production.md).
- Gli asset pubblicati da Filament (`public/css|js|fonts/filament`) sono ignorati da git e rigenerati da `composer install` / `composer update`.

## Conseguenze
- Il prefisso va comunicato fuori dal repository e impostato in `.env` in ogni ambiente (locale, staging, produzione).
- Un prefisso nascosto riduce gli attacchi automatici ma non sostituisce l'autenticazione: restano obbligatorie password robuste. **Decisione attuale: per ora niente 2FA**, solo password sicure (da rivalutare); il rate limit sul login è da sistemare prima del lancio ([checklist](../sviluppo/prima-del-lancio.md)).
