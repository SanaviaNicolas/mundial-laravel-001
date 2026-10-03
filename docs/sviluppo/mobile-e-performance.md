# Mobile e performance: checklist e target

Complementa la [checklist SEO](../seo/checklist.md). I target sono **obiettivi di progetto**, da misurare sulle pagine reali e da rivedere dopo le prime misure.

## Target misurabili

Misure su **mobile** (emulazione Moto G Power/rete 4G lenta di Lighthouse) e, quando c'è traffico, dati reali (CrUX/Search Console).

| Metrica | Target |
|---|---|
| Lighthouse mobile — Performance | ≥ 90 (home e menù) |
| Lighthouse mobile — Accessibilità | ≥ 95 |
| Lighthouse mobile — Best Practices | ≥ 95 |
| Lighthouse mobile — SEO | 100 |
| LCP (Largest Contentful Paint) | ≤ 2,5 s (obiettivo ≤ 2,0 s) |
| INP (Interaction to Next Paint) | ≤ 200 ms |
| CLS (Cumulative Layout Shift) | ≤ 0,1 (obiettivo ≤ 0,05) |
| TTFB | ≤ 0,8 s |
| Peso pagina (trasferito, prima visita, esclusa la parte sotto la piega caricata in lazy) | Home ≤ 500 KB; Menù ≤ 300 KB senza foto |
| JavaScript | ≤ 30 KB compresso per pagina (obiettivo: nessun JS necessario per leggere il contenuto) |
| CSS | ≤ 30 KB compresso |
| Richieste a domini di terze parti | 0 (font, analytics, mappe incluse) salvo decisione esplicita |

## Checklist implementativa

**Rendering e risorse**
- [ ] Contenuto completo nell'HTML server-side; nessuna pagina dipende da JS per mostrare testo o menù.
- [ ] CSS critico/minimo (Tailwind con purge), un solo foglio; JS caricato `defer`.
- [ ] Font self-hosted, `woff2`, sottoinsieme latino, `font-display: swap`, `preload` del solo font del testo.
- [ ] Immagini AVIF/WebP, `srcset`/`sizes`, `width`/`height`, lazy tranne LCP (`fetchpriority="high"`).
- [ ] Cache HTTP: asset con hash (Vite) `immutable` a lungo termine; HTML con cache breve o validazione.
- [ ] Compressione (gzip/brotli) attiva sul server: da concordare con Nicolas.
- [ ] Nessuna risorsa di terze parti che blocchi il rendering.

**Mobile**
- [ ] `<meta name="viewport" content="width=device-width, initial-scale=1">` (già presente, testato).
- [ ] Nessuno scroll orizzontale a 320, 360, 390, 414, 768 px.
- [ ] Target touch ≥ 44×44 px, distanza ≥ 8 px.
- [ ] Testo corrente ≥ 16 px; zoom pagina non bloccato.
- [ ] Pulsante "Chiama" (`tel:`) sempre raggiungibile.
- [ ] Menù navigabile con una mano: indice categorie, ancore, ritorno in cima.
- [ ] Preferenze utente rispettate: `prefers-reduced-motion`, `prefers-color-scheme` (tema chiaro unico: da decidere).
- [ ] Focus visibile e ordine di tabulazione corretto (accessibilità).

## Come si testa

- **Viewport reali**: Chrome DevTools device mode e, appena possibile, un dispositivo fisico Android e uno iOS (Safari). Viewport minimi: 320×568, 360×800, 390×844, 768×1024, 1280×800.
- **Lighthouse** mobile in modalità navigazione, in locale su build di produzione (`npm run build`, `APP_ENV=production` solo per misurare) e poi sull'ambiente di staging/produzione. Si annotano i punteggi in questa pagina a ogni rilascio rilevante.
- **PageSpeed Insights** e Search Console (Core Web Vitals) dopo la messa online.
- **Automatizzabile più avanti**: Lighthouse CI o controllo del peso degli asset in CI. Non ora: eviterebbe dipendenze e complessità prima di avere pagine vere.
- **Test Laravel** (già nella suite): presenza di viewport, `lang`, `title`, meta description, un solo H1 per pagina.
