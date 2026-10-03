# 0006 — Modello dati del menù

**Stato:** in costruzione (si completa con i blocchi dello step 2)

## Categorie
- Tabella `categories` autoreferenziale: `parent_id` nullo = macrocategoria, altrimenti sottocategoria. **Profondità massima 2**: lo impone il modello (`DomainException`) e, nell'admin, il form offre come genitore solo le macrocategorie.
- Campi: nome e descrizione (traducibili), `slug` univoco, `sort_order`, `is_visible`.
- Nascondere una macrocategoria nasconde anche le sue sottocategorie (scope `Category::visible()`).
- Una categoria con sottocategorie non si può cancellare (vincolo di chiave esterna `restrict`).

## Dati di riferimento
Le migration creano solo la struttura. Gli allergeni (14, Reg. UE 1169/2011) si caricano con `AllergenSeeder` (upsert per chiave, idempotente, eseguibile in production). Vedi [setup](../sviluppo/setup.md).
