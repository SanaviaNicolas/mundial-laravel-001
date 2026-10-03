<?php

/*
 * Static placeholder menu (prices are indicative, to be confirmed with the client): categories keyed by URL slug, each with its items.
 * It will be replaced by the dynamic menu managed in Filament.
 *
 * Item keys: nome, ingredienti, prezzo, and optionally `nota`
 * (e.g. "A fine cottura: ...", "Servito con: ...").
 */
return [
    'tradizione-napoletana' => [
        'nome' => 'Tradizione napoletana',
        'descrizione' => 'Con bordo alto, come si fa a Napoli.',
        'voci' => [
            ['nome' => 'Marinara', 'ingredienti' => 'Pomodoro, aglio, origano, olio extravergine.', 'prezzo' => '€ 6,00'],
            ['nome' => 'Margherita', 'ingredienti' => 'Pomodoro, fior di latte, basilico.', 'nota' => 'A fine cottura: olio extravergine.', 'prezzo' => '€ 7,00'],
            ['nome' => 'Margherita con bufala', 'ingredienti' => 'Pomodoro, mozzarella di bufala, basilico.', 'prezzo' => '€ 9,50'],
        ],
    ],
    'le-classiche' => [
        'nome' => 'Le classiche',
        'descrizione' => 'Quelle che non stancano mai.',
        'voci' => [
            ['nome' => 'Diavola', 'ingredienti' => 'Pomodoro, mozzarella, salame piccante.', 'prezzo' => '€ 8,50'],
            ['nome' => 'Capricciosa', 'ingredienti' => 'Pomodoro, mozzarella, prosciutto cotto, funghi, carciofi, olive.', 'prezzo' => '€ 10,00'],
            ['nome' => 'Quattro stagioni', 'ingredienti' => 'Pomodoro, mozzarella, prosciutto cotto, funghi*, carciofi, olive.', 'prezzo' => '€ 10,00'],
        ],
    ],
    'le-speciali' => [
        'nome' => 'Le speciali',
        'descrizione' => 'Le idee della casa.',
        'voci' => [
            ['nome' => 'Pizza della casa', 'ingredienti' => 'Ingredienti della pizza, scritti per esteso.', 'nota' => 'Nota sulla pizza: testo di esempio.', 'prezzo' => '€ 11,00'],
            ['nome' => 'Salsiccia e friarielli', 'ingredienti' => 'Fior di latte, salsiccia, friarielli.', 'prezzo' => '€ 10,50'],
            ['nome' => 'Pizza speciale', 'ingredienti' => 'Altra pizza con ingredienti di esempio.', 'nota' => 'Servito con: contorno di esempio.', 'prezzo' => '€ 12,00'],
        ],
    ],
    'fiorfritta-coccodrillo' => [
        'nome' => 'Fiorfritta / Coccodrillo',
        'descrizione' => 'Fritte in modo leggero, ripiene a piacere.',
        'voci' => [
            ['nome' => 'Fiorfritta di esempio', 'ingredienti' => 'Ingredienti di esempio per la fiorfritta.', 'prezzo' => '€ 8,00'],
            ['nome' => 'Coccodrillo di esempio', 'ingredienti' => 'Ingredienti di esempio per il coccodrillo.', 'prezzo' => '€ 8,50'],
            ['nome' => 'Fritta della casa', 'ingredienti' => 'Ingredienti di esempio.', 'nota' => 'A fine cottura: testo di esempio.', 'prezzo' => '€ 9,00'],
        ],
    ],
    'le-bianche' => [
        'nome' => 'Le bianche',
        'descrizione' => 'Senza pomodoro.',
        'voci' => [
            ['nome' => 'Quattro formaggi', 'ingredienti' => 'Fior di latte, gorgonzola, provola, parmigiano.', 'prezzo' => '€ 9,50'],
            ['nome' => 'Ortolana', 'ingredienti' => 'Fior di latte, zucchine, melanzane, peperoni.', 'prezzo' => '€ 9,00'],
            ['nome' => 'Prosciutto e funghi', 'ingredienti' => 'Fior di latte, prosciutto cotto, funghi.', 'prezzo' => '€ 9,00'],
        ],
    ],
    'le-chiuse' => [
        'nome' => 'Le chiuse',
        'descrizione' => 'Calzoni al forno, ripieni fino al bordo.',
        'voci' => [
            ['nome' => 'Calzone classico', 'ingredienti' => 'Ricotta, salame, fior di latte, pomodoro.', 'prezzo' => '€ 9,00'],
            ['nome' => 'Calzone ortolano', 'ingredienti' => 'Ricotta, verdure di stagione, fior di latte.', 'prezzo' => '€ 8,50'],
            ['nome' => 'Calzone della casa', 'ingredienti' => 'Ingredienti di esempio.', 'prezzo' => '€ 9,50'],
        ],
    ],
    'baguette' => [
        'nome' => 'Baguette',
        'descrizione' => 'Croccanti fuori, morbide dentro.',
        'voci' => [
            ['nome' => 'Baguette prosciutto', 'ingredienti' => 'Prosciutto cotto, fior di latte, pomodoro.', 'prezzo' => '€ 7,50'],
            ['nome' => 'Baguette salame', 'ingredienti' => 'Salame, provola, rucola.', 'prezzo' => '€ 8,00'],
            ['nome' => 'Baguette vegetariana', 'ingredienti' => 'Verdure grigliate, scamorza.', 'prezzo' => '€ 8,00'],
        ],
    ],
    'panuozzi' => [
        'nome' => 'Panuozzi',
        'descrizione' => 'Impasto di pizza, cotto nel forno, farcito.',
        'voci' => [
            ['nome' => 'Panuozzo di esempio', 'ingredienti' => 'Ingredienti di esempio.', 'prezzo' => '€ 8,50'],
            ['nome' => 'Panuozzo della casa', 'ingredienti' => 'Ingredienti di esempio.', 'nota' => 'Servito con: testo di esempio.', 'prezzo' => '€ 9,00'],
            ['nome' => 'Panuozzo speciale', 'ingredienti' => 'Ingredienti di esempio.', 'prezzo' => '€ 9,50'],
        ],
    ],
    'tegamini' => [
        'nome' => 'Tegamini',
        'descrizione' => 'Da dividere, da fare la scarpetta.',
        'voci' => [
            ['nome' => 'Tegamino di esempio', 'ingredienti' => 'Ingredienti di esempio.', 'prezzo' => '€ 6,50'],
            ['nome' => 'Tegamino della casa', 'ingredienti' => 'Ingredienti di esempio.', 'prezzo' => '€ 7,00'],
            ['nome' => 'Tegamino speciale', 'ingredienti' => 'Ingredienti di esempio.', 'nota' => 'A fine cottura: testo di esempio.', 'prezzo' => '€ 8,00'],
        ],
    ],
];
