# 0010 — Contratto dati del menù pubblico

**Stato:** accettata

## Contesto

Il menù del sito è ancora statico (il pannello Filament non è compilato), ma deve diventare dinamico senza riscrivere la pagina. I dati statici avevano una forma più povera del modello del pannello ([ADR 0006](0006-modello-dati-menu.md)): categorie su un solo livello, ingredienti come testo libero, badge inesistenti nel pannello, nessuna aggiunta né allergene.

## Decisione

- Un **contratto dati unico** per il frontend, in `app/Menu`: `Section` (categoria con voci e sottocategorie, due livelli), `Item` (nome, descrizione, note, prezzo in centesimi o `null`, ingredienti, tag `slug => nome`, aggiunte, allergeni `null` se non verificati), `Ingredient` (surgelato, a fine cottura, sezione), `Addon` (supplemento, allergeni propri) e `Price` (formattazione per lingua). Le viste usano solo questo contratto, mai i modelli.
- Due **sorgenti** dietro l'interfaccia `MenuSource`: `StaticMenu` (legge `config/menu.php`, riscritto con la stessa struttura del pannello) e `DatabaseMenu` (legge i modelli: visibilità, ordine, traduzioni con fallback, aggiunte effettive, allergeni pubblici). Si sceglie con `menu.source` (variabile `MENU_SOURCE`, default `static`). Entrambe restituiscono dati già tradotti, ordinati, senza categorie vuote.
- **Badge = tag** con slug convenzionali: `pizza-del-mese`, `la-piu-scelta`, insieme a `novita` e `stagionale`. Le voci con uno di questi tag entrano nella sezione "In evidenza" (`Item::HIGHLIGHTS`). Nessuna modifica al backend.
- Le card "In evidenza" **non hanno foto**: il ristorante non vuole foto per i singoli piatti (guida frontend). Restano le foto decorative statiche accanto a ogni categoria (`categoria-{slug}`).

## Alternative scartate

- **Solo dati statici riallineati**, scrivendo la sorgente database al momento del passaggio: meno codice oggi, ma il rischio che la vista dipenda da dettagli statici emerge solo alla fine.
- **Vista che legge direttamente i modelli Eloquent**: impossibile finché il pannello è vuoto, e mescola regole di dominio (allergeni, visibilità) nel template.
- **Campo dedicato per i badge** sulla voce: richiede migration e form; i tag coprono già il caso (come "Novità" e "Stagionale").

## Conseguenze

- Passare al menù dinamico = compilare il pannello e impostare `MENU_SOURCE=database`; la vista non cambia. Le due sorgenti hanno test sullo stesso contratto.
- `DatabaseMenu` calcola aggiunte e allergeni voce per voce (alcune query per voce): adeguato al volume previsto (~80 voci); se servirà, cache del menù invalidata al salvataggio nel pannello.
- I futuri dati strutturati JSON-LD del menù andranno generati dallo stesso contratto, così coincidono con il testo visibile.
