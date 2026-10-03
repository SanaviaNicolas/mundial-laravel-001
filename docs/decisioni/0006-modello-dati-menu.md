# 0006 — Modello dati del menù

**Stato:** in costruzione (si completa con i blocchi dello step 2)

## Categorie
- Tabella `categories` autoreferenziale: `parent_id` nullo = macrocategoria, altrimenti sottocategoria. **Profondità massima 2**: lo impone il modello (`DomainException`) e, nell'admin, il form offre come genitore solo le macrocategorie.
- Campi: nome e descrizione (traducibili), `slug` univoco, `sort_order`, `is_visible`.
- Nascondere una macrocategoria nasconde anche le sue sottocategorie (scope `Category::visible()`).
- Una categoria con sottocategorie non si può cancellare (vincolo di chiave esterna `restrict`).

## Voci di menù
- `menu_items`: appartengono a una categoria (macro o sottocategoria), senza slug (non hanno pagina propria). Nome, descrizione e note traducibili, `sort_order`, `is_visible`.
- **Prezzo in centesimi** (intero, evita errori di arrotondamento dei decimali) e **nullable**: `null` = prezzo non ancora noto, il frontend non mostra nulla.
- Visibile in pubblico solo se la voce e tutta la catena di categorie sono visibili (`MenuItem::visible()`).
- Una categoria con voci non si può cancellare.

## Ingredienti
- `ingredients`: riusabili, nome traducibile, flag `is_frozen` (surgelato: asterisco con legenda sul sito). Un ingrediente usato non si può cancellare.
- `menu_item_ingredients` (righe di una voce, modello `MenuItemIngredient`): ordine (`sort_order`), `after_cooking` (a fine cottura / dopo cottura) e `section` (etichetta traducibile opzionale per raggruppare, es. le due metà di una voce "metà e metà"). Varianti come "a fette" o "di Agerola" sono ingredienti distinti. Le alternative ("A o B") non hanno una struttura dedicata: si collegano entrambi gli ingredienti (così gli allergeni sono completi) e la scelta si scrive nelle note.

## Tag
`tags`: slug univoco e stabile, nome traducibile; relazione molti-a-molti con le voci. "Novità" e "Stagionale" sono tag come gli altri.

## Aggiunte
- `addons`: nome traducibile, supplemento `price_cents` (intero, anche 0), `sort_order`, `is_visible`.
- Si applicano **di default a tutte le voci di una categoria** (tabella `addon_category`; un'aggiunta assegnata a una macro vale anche per le sue sottocategorie, il contrario no) e/o si **collegano direttamente a singole voci** (`addon_menu_item`).
- **Esclusioni**: la stessa tabella `addon_menu_item` ha il flag `is_excluded`: `true` = quell'aggiunta, anche se ereditata dalla categoria, non vale per quella voce (es. "cornicione ripieno" su tutte le pizze tranne le calzoni). È la rappresentazione più semplice: una sola tabella, nessun caso particolare.
- `MenuItem::effectiveAddons()` = (aggiunte della categoria e della macro + collegate alla voce) − esclusioni, senza duplicati, in ordine, solo visibili (con `visibleOnly: false` anche le nascoste, per l'admin).

## Dati di riferimento
Le migration creano solo la struttura. Gli allergeni (14, Reg. UE 1169/2011) si caricano con `AllergenSeeder` (upsert per chiave, idempotente, eseguibile in production). Vedi [setup](../sviluppo/setup.md).
