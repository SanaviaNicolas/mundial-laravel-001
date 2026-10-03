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

## Dati di riferimento
Le migration creano solo la struttura. Gli allergeni (14, Reg. UE 1169/2011) si caricano con `AllergenSeeder` (upsert per chiave, idempotente, eseguibile in production). Vedi [setup](../sviluppo/setup.md).
