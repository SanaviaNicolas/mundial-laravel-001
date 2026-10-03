<?php

/*
 * Static placeholder menu: categories keyed by URL slug, each with its items.
 * It will be replaced by the dynamic menu managed in Filament.
 *
 * Texts are a plain string (same in every language) or ['it' => ..., 'en' => ...].
 * Prices are numbers (indicative, to be confirmed with the client) formatted per locale.
 * Item keys: nome, ingredienti, prezzo, and optionally `nota` ("A fine cottura: ...",
 * "Servito con: ...") and `badge` (`mese` = pizza of the month, `scelta` = most chosen).
 * English texts are drafts to be reviewed with the client.
 */
return [
    'tradizione-napoletana' => [
        'nome' => ['it' => 'Tradizione napoletana', 'en' => 'Traditional Neapolitan'],
        'descrizione' => ['it' => 'Con bordo alto, come si fa a Napoli.', 'en' => 'With a puffy crust, the way they make it in Naples.'],
        'voci' => [
            ['nome' => 'Marinara', 'ingredienti' => ['it' => 'Pomodoro, aglio, origano, olio extravergine.', 'en' => 'Tomato, garlic, oregano, extra virgin olive oil.'], 'prezzo' => 6.00],
            ['nome' => 'Margherita', 'ingredienti' => ['it' => 'Pomodoro, fior di latte, basilico.', 'en' => 'Tomato, fior di latte mozzarella, basil.'], 'nota' => ['it' => 'A fine cottura: olio extravergine.', 'en' => 'After baking: extra virgin olive oil.'], 'prezzo' => 7.00, 'badge' => 'scelta'],
            ['nome' => ['it' => 'Margherita con bufala', 'en' => 'Margherita with buffalo mozzarella'], 'ingredienti' => ['it' => 'Pomodoro, mozzarella di bufala, basilico.', 'en' => 'Tomato, buffalo mozzarella, basil.'], 'prezzo' => 9.50],
        ],
    ],
    'le-classiche' => [
        'nome' => ['it' => 'Le classiche', 'en' => 'The classics'],
        'descrizione' => ['it' => 'Quelle che non stancano mai.', 'en' => 'The ones you never get tired of.'],
        'voci' => [
            ['nome' => 'Diavola', 'ingredienti' => ['it' => 'Pomodoro, mozzarella, salame piccante.', 'en' => 'Tomato, mozzarella, spicy salami.'], 'prezzo' => 8.50],
            ['nome' => 'Capricciosa', 'ingredienti' => ['it' => 'Pomodoro, mozzarella, prosciutto cotto, funghi, carciofi, olive.', 'en' => 'Tomato, mozzarella, cooked ham, mushrooms, artichokes, olives.'], 'prezzo' => 10.00],
            ['nome' => ['it' => 'Quattro stagioni', 'en' => 'Four seasons'], 'ingredienti' => ['it' => 'Pomodoro, mozzarella, prosciutto cotto, funghi*, carciofi, olive.', 'en' => 'Tomato, mozzarella, cooked ham, mushrooms*, artichokes, olives.'], 'prezzo' => 10.00],
        ],
    ],
    'le-speciali' => [
        'nome' => ['it' => 'Le speciali', 'en' => 'The specials'],
        'descrizione' => ['it' => 'Le idee della casa.', 'en' => 'The house ideas.'],
        'voci' => [
            ['nome' => ['it' => 'Pizza della casa', 'en' => 'House pizza'], 'ingredienti' => ['it' => 'Ingredienti della pizza, scritti per esteso.', 'en' => 'Pizza ingredients, written out in full.'], 'nota' => ['it' => 'Nota sulla pizza: testo di esempio.', 'en' => 'Note on the pizza: sample text.'], 'prezzo' => 11.00],
            ['nome' => ['it' => 'Salsiccia e friarielli', 'en' => 'Sausage and friarielli'], 'ingredienti' => ['it' => 'Fior di latte, salsiccia, friarielli.', 'en' => 'Fior di latte mozzarella, sausage, friarielli (Neapolitan broccoli rabe).'], 'prezzo' => 10.50, 'badge' => 'mese'],
            ['nome' => ['it' => 'Pizza speciale', 'en' => 'Special pizza'], 'ingredienti' => ['it' => 'Altra pizza con ingredienti di esempio.', 'en' => 'Another pizza with sample ingredients.'], 'nota' => ['it' => 'Servito con: contorno di esempio.', 'en' => 'Served with: sample side.'], 'prezzo' => 12.00],
        ],
    ],
    'fiorfritta-coccodrillo' => [
        'nome' => 'Fiorfritta / Coccodrillo',
        'descrizione' => ['it' => 'Fritte in modo leggero, ripiene a piacere.', 'en' => 'Lightly fried, filled as you like.'],
        'voci' => [
            ['nome' => ['it' => 'Fiorfritta di esempio', 'en' => 'Sample fiorfritta'], 'ingredienti' => ['it' => 'Ingredienti di esempio per la fiorfritta.', 'en' => 'Sample ingredients for the fiorfritta.'], 'prezzo' => 8.00],
            ['nome' => ['it' => 'Coccodrillo di esempio', 'en' => 'Sample coccodrillo'], 'ingredienti' => ['it' => 'Ingredienti di esempio per il coccodrillo.', 'en' => 'Sample ingredients for the coccodrillo.'], 'prezzo' => 8.50],
            ['nome' => ['it' => 'Fritta della casa', 'en' => 'House fried pizza'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'nota' => ['it' => 'A fine cottura: testo di esempio.', 'en' => 'After baking: sample text.'], 'prezzo' => 9.00],
        ],
    ],
    'le-bianche' => [
        'nome' => ['it' => 'Le bianche', 'en' => 'White pizzas'],
        'descrizione' => ['it' => 'Senza pomodoro.', 'en' => 'No tomato sauce.'],
        'voci' => [
            ['nome' => ['it' => 'Quattro formaggi', 'en' => 'Four cheeses'], 'ingredienti' => ['it' => 'Fior di latte, gorgonzola, provola, parmigiano.', 'en' => 'Fior di latte mozzarella, gorgonzola, provola, parmesan.'], 'prezzo' => 9.50],
            ['nome' => ['it' => 'Ortolana', 'en' => 'Garden vegetables'], 'ingredienti' => ['it' => 'Fior di latte, zucchine, melanzane, peperoni.', 'en' => 'Fior di latte mozzarella, courgettes, aubergines, peppers.'], 'prezzo' => 9.00],
            ['nome' => ['it' => 'Prosciutto e funghi', 'en' => 'Ham and mushrooms'], 'ingredienti' => ['it' => 'Fior di latte, prosciutto cotto, funghi.', 'en' => 'Fior di latte mozzarella, cooked ham, mushrooms.'], 'prezzo' => 9.00],
        ],
    ],
    'le-chiuse' => [
        'nome' => ['it' => 'Le chiuse', 'en' => 'Calzoni'],
        'descrizione' => ['it' => 'Calzoni al forno, ripieni fino al bordo.', 'en' => 'Baked calzoni, filled to the brim.'],
        'voci' => [
            ['nome' => ['it' => 'Calzone classico', 'en' => 'Classic calzone'], 'ingredienti' => ['it' => 'Ricotta, salame, fior di latte, pomodoro.', 'en' => 'Ricotta, salami, fior di latte mozzarella, tomato.'], 'prezzo' => 9.00],
            ['nome' => ['it' => 'Calzone ortolano', 'en' => 'Vegetable calzone'], 'ingredienti' => ['it' => 'Ricotta, verdure di stagione, fior di latte.', 'en' => 'Ricotta, seasonal vegetables, fior di latte mozzarella.'], 'prezzo' => 8.50],
            ['nome' => ['it' => 'Calzone della casa', 'en' => 'House calzone'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'prezzo' => 9.50],
        ],
    ],
    'baguette' => [
        'nome' => 'Baguette',
        'descrizione' => ['it' => 'Croccanti fuori, morbide dentro.', 'en' => 'Crunchy outside, soft inside.'],
        'voci' => [
            ['nome' => ['it' => 'Baguette prosciutto', 'en' => 'Ham baguette'], 'ingredienti' => ['it' => 'Prosciutto cotto, fior di latte, pomodoro.', 'en' => 'Cooked ham, fior di latte mozzarella, tomato.'], 'prezzo' => 7.50],
            ['nome' => ['it' => 'Baguette salame', 'en' => 'Salami baguette'], 'ingredienti' => ['it' => 'Salame, provola, rucola.', 'en' => 'Salami, provola, rocket.'], 'prezzo' => 8.00],
            ['nome' => ['it' => 'Baguette vegetariana', 'en' => 'Vegetarian baguette'], 'ingredienti' => ['it' => 'Verdure grigliate, scamorza.', 'en' => 'Grilled vegetables, scamorza.'], 'prezzo' => 8.00],
        ],
    ],
    'panuozzi' => [
        'nome' => 'Panuozzi',
        'descrizione' => ['it' => 'Impasto di pizza, cotto nel forno, farcito.', 'en' => 'Pizza dough, baked in the oven, stuffed.'],
        'voci' => [
            ['nome' => ['it' => 'Panuozzo di esempio', 'en' => 'Sample panuozzo'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'prezzo' => 8.50],
            ['nome' => ['it' => 'Panuozzo della casa', 'en' => 'House panuozzo'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'nota' => ['it' => 'Servito con: testo di esempio.', 'en' => 'Served with: sample text.'], 'prezzo' => 9.00],
            ['nome' => ['it' => 'Panuozzo speciale', 'en' => 'Special panuozzo'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'prezzo' => 9.50],
        ],
    ],
    'tegamini' => [
        'nome' => 'Tegamini',
        'descrizione' => ['it' => 'Da dividere, da fare la scarpetta.', 'en' => 'To share, perfect for mopping up the sauce.'],
        'voci' => [
            ['nome' => ['it' => 'Tegamino di esempio', 'en' => 'Sample tegamino'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'prezzo' => 6.50],
            ['nome' => ['it' => 'Tegamino della casa', 'en' => 'House tegamino'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'prezzo' => 7.00],
            ['nome' => ['it' => 'Tegamino speciale', 'en' => 'Special tegamino'], 'ingredienti' => ['it' => 'Ingredienti di esempio.', 'en' => 'Sample ingredients.'], 'nota' => ['it' => 'A fine cottura: testo di esempio.', 'en' => 'After baking: sample text.'], 'prezzo' => 8.00],
        ],
    ],
];
