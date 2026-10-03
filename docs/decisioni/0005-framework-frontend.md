# 0005 — Framework frontend

**Stato:** proposta — **da decidere con Nicolas**. Non è stato installato né configurato nulla di questo stack: oggi il progetto ha solo Vite + Tailwind 4 di default e un layout Blade.

## Contesto

Sito vetrina multipagina (4 pagine), solo lettura: l'unica parte dinamica è il menù (~80 voci in 9 categorie) gestito da Filament 5. Nessun login pubblico, carrello, prenotazione o modulo. Priorità assolute: mobile-first, SEO/AI-friendly con contenuto leggibile senza JS, Core Web Vitals (vedi [target](../sviluppo/mobile-e-performance.md)). Due sole persone sviluppano; la manutenzione futura deve essere semplice. Si lascia aperta la porta a estensioni (ad es. prenotazioni), senza pagarle ora.

## Opzioni

**A) Blade + Tailwind + Alpine.js** — viste Blade server-side, Tailwind per lo stile, Alpine (≈ 15 KB) solo per piccole interazioni (menu mobile, indice del menù).
**B) Blade + Tailwind + Livewire** — come A, con componenti reattivi server-driven (Livewire 3/4). Filament 5 è già costruito su Livewire.
**C) Framework JS** — Inertia + Vue o React (SSR opzionale), oppure un frontend SSR dedicato (Nuxt/Next) che consuma Laravel come API.

## Confronto

Valutazione: ● ottimo · ◐ accettabile · ○ debole.

| Criterio | A) Blade + Alpine | B) Blade + Livewire | C) Inertia + Vue/React (SSR) |
|---|---|---|---|
| SEO e SSR | ● HTML completo di default, `<head>` per pagina nativo | ● Idem alla prima richiesta | ◐ Richiede SSR attivo (processo Node) e gestione `<head>`; senza SSR il contenuto non è nell'HTML |
| Prestazioni/CWV mobile | ● JS ≈ 15 KB, quasi tutto statico | ◐ Aggiunge il runtime Livewire (decine di KB) e richieste al server per le interazioni | ○ Bundle del framework + hydration: più JS, rischio su INP/LCP |
| Semplicità e manutenzione | ● Pochissimi concetti, stack già in uso | ● Semplice, PHP-centrico | ○ Due ecosistemi (PHP + JS), build, SSR, aggiornamenti |
| Facilità di aggiornamento contenuti/UI | ● Template leggibili da chiunque conosca HTML | ● Idem | ◐ Richiede competenze JS per ogni modifica |
| Integrazione con Filament e menù dinamico | ● Il menù è una query Eloquent resa in Blade; JSON-LD generato dagli stessi dati | ● Idem; Livewire già presente per Filament | ◐ Serve passare i dati come props/API; doppia modellazione |
| Peso del JS | ● Minimo | ◐ Medio | ○ Alto |
| Curva di apprendimento | ● Bassa | ● Bassa (Livewire già noto per Filament) | ○ Alta |
| Rischio di sovradimensionare | ● Basso | ◐ Medio: reattività non necessaria per una vetrina | ○ Alto |
| Apertura a estensioni future | ◐ Si può aggiungere Livewire/Inertia in seguito, per singole pagine | ● Prenotazioni/moduli ben coperti | ● Massima, ma a costo oggi |

Note:
- Per una vetrina quasi statica, ciò che serve da JS è poco: menu mobile apribile, eventuale filtro del menù. Entrambi si coprono con HTML (`<details>`, ancore) e, se serve, Alpine.
- Livewire non è un'alternativa a Alpine ma un'aggiunta: in Filament è già caricato **solo nel pannello admin**, quindi non pesa sul sito pubblico. Usarlo nel sito pubblico aggiungerebbe JS e richieste a pagine che oggi non ne hanno bisogno.
- La scelta A non preclude B o C più avanti: Livewire si può introdurre per una singola pagina (es. un form di prenotazione) senza riscrivere le altre.

## Raccomandazione

**A) Blade + Tailwind + Alpine.js**, con un principio: *HTML server-side prima, JS solo se necessario* (progressive enhancement). Motivi: SEO/AI-friendly per costruzione, JS minimo per i Core Web Vitals su mobile, nessuna infrastruttura aggiuntiva (niente Node in produzione), manutenzione alla portata di entrambi, integrazione diretta con Filament e con la query del menù. Alpine si aggiunge solo quando la prima interazione reale lo richiede (**non prima**: se bastano HTML e CSS, il sito parte con zero dipendenze JS in più). Livewire si valuterà per le future funzioni interattive, pagina per pagina.

C) è sconsigliata oggi: il costo (build, SSR, doppio ecosistema, JS) è sproporzionato per 4 pagine di contenuto.

## Rischi

- Alpine/JS minimo: da evitare che diventi scorciatoia per logica complessa; il limite è un budget JS (≤ 30 KB compresso, vedi [target](../sviluppo/mobile-e-performance.md)).
- Se emergesse presto un requisito molto interattivo (prenotazioni con disponibilità, ordini), la scelta va rivalutata con un nuovo ADR.
- Con Blade puro la qualità del markup dipende dalla disciplina (componenti Blade e test di pagina per H1, title, description, JSON-LD).

## Domande aperte per Nicolas

1. Concordi con A? Hai motivi infrastrutturali (hosting, processi Node, cache, CDN) che cambiano la valutazione?
2. Hosting e deploy: si compila `npm run build` in CI/deploy? Quale server web (compressione brotli/gzip, cache HTTP degli asset)?
3. Modello dati del menù (categorie, voci, ordine, asterisco, note, prezzo, allergeni): lo definisci tu in Filament? Il frontend ha bisogno dei campi prima di costruire le viste.
4. Pipeline immagini: elaborazione (AVIF/WebP, ridimensionamenti) in upload Filament oppure pre-generata? Libreria (GD/Imagick) disponibile sul server?
5. Font self-hosted: ok sostituire `bunny('Instrument Sans')` in `vite.config.js` e il `--font-sans` in `resources/css/app.css` (default Laravel, CDN esterno) con Bricolage Grotesque + Inter locali (già fatto in `frontend/home-layout-base`, da approvare)?
6. `sitemap.xml` e JSON-LD: chi genera cosa? Proposta: il backend espone i dati, il frontend li rende in Blade.
7. Politica cookie/terze parti (mappa, analytics): serve un banner? Preferenza: nessuna terza parte, quindi nessun banner.

## Decisione

*Da compilare dopo il confronto con Nicolas* (stato → "accettata" e aggiornamento di [ADR 0001](0001-stack.md)).
