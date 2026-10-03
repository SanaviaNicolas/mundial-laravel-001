# 0007 — Pannello di gestione del menù (Filament)

**Stato:** accettata

## Decisione
- Cinque risorse nel gruppo "Menù": **Categorie**, **Voci**, **Ingredienti**, **Aggiunte**, **Tag**. Gli allergeni non sono una risorsa: sono un elenco fisso, usato solo come opzioni nei form.
- **Interfaccia in italiano** (locale `it`, traduzioni di Filament incluse). Accesso a qualunque utente autenticato (`User::canAccessPanel()` sempre vero): nessun sistema di ruoli per ora.
- **Testi traducibili**: una scheda per lingua (Italiano obbligatorio, Inglese facoltativo) con l'helper `Translated::tabs()`; colonne con ricerca e ordinamento sul testo nella lingua corrente con fallback (`Translated::column()`). Vedi [ADR 0005](0005-traduzioni.md).
- **Riordino drag and drop** (`reorderable('sort_order')`): macrocategorie nella lista Categorie; sottocategorie e voci nelle relative schede (relation manager) della categoria; ingredienti nel Repeater del form della voce; aggiunte nella lista Aggiunte. Le voci si riordinano dentro la loro categoria (non in una lista globale, dove l'ordine non avrebbe senso).
- **Prezzi**: si digitano in euro e si salvano in centesimi (`Fields::price()`).
- **Allergeni**: nel form della voce si vedono gli allergeni effettivi (anteprima in sola lettura) e lo stato della verifica; azioni "Conferma allergeni" / "Annulla verifica allergeni" nella pagina e nella lista; colonna e filtro "allergeni verificati / da verificare". Regola completa in [ADR 0006](0006-modello-dati-menu.md).
- **Aggiunte nel form della voce**: anteprima delle aggiunte valide, esclusione di quelle ereditate dalla categoria (`excludedAddons`), aggiunta di extra (`extraAddons`).
- **Cancellazioni protette**: l'azione Elimina è nascosta per categorie con sottocategorie o voci e per ingredienti in uso (oltre al vincolo del database). Non c'è eliminazione di massa.
- Gli ingredienti **non si creano dal form della voce**: vanno creati prima nella sezione Ingredienti, con i loro allergeni, per non rischiare ingredienti senza dati sugli allergeni.

## Conseguenze
- Gli utenti admin si creano con `php artisan make:filament-user` (non fa parte di nessuno seeder).
- Test Livewire per ogni risorsa (lista, ricerca/ordinamento, creazione, modifica, riordino, azioni).
