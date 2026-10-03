# 0003 — Pannello admin Filament

**Stato:** accettata

## Decisione
- Filament 5.9 installato con un unico panel `admin` su `/admin` (provider `app/Providers/Filament/AdminPanelProvider.php`), con login Filament.
- Per ora nessuna risorsa e nessun utente: gli utenti admin verranno gestiti con la prima feature di dominio.
- Il panel non deve mai essere indicizzato: il middleware `App\Http\Middleware\NoIndex` aggiunge l'header `X-Robots-Tag: noindex, nofollow` a ogni risposta del panel, in qualsiasi ambiente (test dedicato). `robots.txt` aggiunge inoltre `Disallow: /admin`.
- Gli asset pubblicati da Filament (`public/css|js|fonts/filament`) sono ignorati da git e rigenerati da `composer install` / `composer update` (script `post-update-cmd`, `filament:upgrade`).

## Conseguenze
Il middleware `NoIndex` è riusato per i siti in ambienti non production (vedi ADR 0004).
