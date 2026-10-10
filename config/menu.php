<?php

/*
 * Public menu. `source` picks where it comes from: `static` (this file, until the menu is filled
 * in Filament) or `database` (the admin data). Both give the same contract (app/Menu).
 *
 * Static data mirrors the admin model (docs/frontend/dati-menu.md), with sample content:
 * - sections keyed by slug, on two levels (`children`); `name`, optional `description`;
 *   `addons` apply to every item of the section and of its subsections;
 * - items: `name`, optional `description` and `notes`, `price` in cents (null = not known yet,
 *   nothing is shown), `ingredients`, `tags` (slugs of the `tags` list), optional `addons`;
 * - an ingredient is a name or ['name' => ..., 'frozen' => true, 'after' => true, 'section' => ...];
 * - addons: ['name' => ..., 'price' => cents (0 = no supplement)].
 * Texts are a plain string (same in every language) or ['it' => ..., 'en' => ...].
 * Prices are indicative; English texts are drafts to be reviewed with the client.
 */

$t = fn (string $it, string $en) => ['it' => $it, 'en' => $en];

$pomodoro = $t('Pomodoro', 'Tomato');
$fiorDiLatte = $t('Fior di latte', 'Fior di latte mozzarella');
$mozzarella = 'Mozzarella';
$basilico = ['name' => $t('Basilico', 'Basil'), 'after' => true];
$olio = ['name' => $t('Olio extravergine', 'Extra virgin olive oil'), 'after' => true];
$cotto = $t('Prosciutto cotto', 'Cooked ham');
$funghi = $t('Funghi', 'Mushrooms');
$esempio = $t('Ingrediente di esempio', 'Sample ingredient');

return [
    'source' => env('MENU_SOURCE', 'static'),

    'tags' => [
        'pizza-del-mese' => $t('Pizza del mese', 'Pizza of the month'),
        'la-piu-scelta' => $t('La più scelta', 'Most ordered'),
        'novita' => $t('Novità', 'New'),
        'stagionale' => $t('Di stagione', 'Seasonal'),
        'vegetariano' => $t('Vegetariana', 'Vegetarian'),
        'piccante' => $t('Piccante', 'Spicy'),
    ],

    'sections' => [
        'pizze' => [
            'name' => $t('Pizze', 'Pizzas'),
            'addons' => [
                ['name' => $t('Aggiunta di esempio', 'Sample extra'), 'price' => 100],
                ['name' => $t('Variante di esempio', 'Sample option'), 'price' => 0],
            ],
            'children' => [
                'tradizione-napoletana' => [
                    'name' => $t('Tradizione napoletana', 'Traditional Neapolitan'),
                    'description' => $t('Con bordo alto, come si fa a Napoli.', 'With a puffy crust, the way they make it in Naples.'),
                    'items' => [
                        ['name' => 'Marinara', 'price' => 600, 'ingredients' => [$pomodoro, $t('Aglio', 'Garlic'), $t('Origano', 'Oregano'), $olio], 'tags' => ['vegetariano']],
                        ['name' => 'Margherita', 'price' => 700, 'ingredients' => [$pomodoro, $fiorDiLatte, $basilico, $olio], 'tags' => ['la-piu-scelta', 'vegetariano']],
                        ['name' => $t('Margherita con bufala', 'Margherita with buffalo mozzarella'), 'price' => 950, 'ingredients' => [$pomodoro, $t('Mozzarella di bufala', 'Buffalo mozzarella'), $basilico]],
                    ],
                ],
                'le-classiche' => [
                    'name' => $t('Le classiche', 'The classics'),
                    'description' => $t('Quelle che non stancano mai.', 'The ones you never get tired of.'),
                    'items' => [
                        ['name' => 'Diavola', 'price' => 850, 'ingredients' => [$pomodoro, $mozzarella, $t('Salame piccante', 'Spicy salami')], 'tags' => ['piccante']],
                        ['name' => 'Capricciosa', 'price' => 1000, 'ingredients' => [$pomodoro, $mozzarella, $cotto, $funghi, $t('Carciofi', 'Artichokes'), $t('Olive', 'Olives')]],
                        ['name' => $t('Quattro stagioni', 'Four seasons'), 'price' => 1000, 'ingredients' => [$pomodoro, $mozzarella, $cotto, ['name' => $funghi, 'frozen' => true], $t('Carciofi', 'Artichokes'), $t('Olive', 'Olives')]],
                    ],
                ],
                'le-speciali' => [
                    'name' => $t('Le speciali', 'The specials'),
                    'description' => $t('Le idee della casa.', 'The house ideas.'),
                    'items' => [
                        ['name' => $t('Pizza della casa', 'House pizza'), 'notes' => $t('Nota sulla pizza: testo di esempio.', 'Note on the pizza: sample text.'), 'price' => 1100, 'ingredients' => [$fiorDiLatte, $esempio, ['name' => $esempio, 'after' => true]], 'tags' => ['novita']],
                        ['name' => $t('Salsiccia e friarielli', 'Sausage and friarielli'), 'price' => 1050, 'ingredients' => [$fiorDiLatte, $t('Salsiccia', 'Sausage'), $t('Friarielli', 'Friarielli (Neapolitan broccoli rabe)')], 'tags' => ['pizza-del-mese']],
                        ['name' => $t('Metà e metà di esempio', 'Sample half and half'), 'notes' => $t('Mezza pizza e mezzo panuozzo.', 'Half pizza, half panuozzo.'), 'price' => 1200, 'ingredients' => [
                            ['name' => $mozzarella, 'section' => $t('Mezza pizza', 'Half pizza')],
                            ['name' => $t('Stracciatella', 'Stracciatella'), 'after' => true, 'section' => $t('Mezza pizza', 'Half pizza')],
                            ['name' => $t('Rucola', 'Rocket'), 'after' => true, 'section' => $t('Mezzo panuozzo', 'Half panuozzo')],
                            ['name' => $t('Prosciutto crudo', 'Cured ham'), 'after' => true, 'section' => $t('Mezzo panuozzo', 'Half panuozzo')],
                        ]],
                    ],
                ],
                'fiorfritta-coccodrillo' => [
                    'name' => 'Fiorfritta / Coccodrillo',
                    'description' => $t('Fritte in modo leggero, ripiene a piacere.', 'Lightly fried, filled as you like.'),
                    'items' => [
                        ['name' => $t('Fiorfritta di esempio', 'Sample fiorfritta'), 'notes' => $t('Base fritta.', 'Fried base.'), 'price' => 800, 'ingredients' => [$esempio]],
                        ['name' => $t('Coccodrillo di esempio', 'Sample coccodrillo'), 'price' => 850, 'ingredients' => [$esempio]],
                        ['name' => $t('Fritta della casa', 'House fried pizza'), 'price' => null, 'ingredients' => [$esempio, ['name' => $esempio, 'after' => true]]],
                    ],
                ],
                'le-bianche' => [
                    'name' => $t('Le bianche', 'White pizzas'),
                    'description' => $t('Senza pomodoro.', 'No tomato sauce.'),
                    'items' => [
                        ['name' => $t('Quattro formaggi', 'Four cheeses'), 'price' => 950, 'ingredients' => [$fiorDiLatte, 'Gorgonzola', 'Provola', $t('Parmigiano', 'Parmesan')], 'tags' => ['vegetariano']],
                        ['name' => $t('Ortolana', 'Garden vegetables'), 'price' => 900, 'ingredients' => [$fiorDiLatte, $t('Zucchine', 'Courgettes'), $t('Melanzane', 'Aubergines'), $t('Peperoni', 'Peppers')], 'tags' => ['stagionale', 'vegetariano']],
                        ['name' => $t('Prosciutto e funghi', 'Ham and mushrooms'), 'price' => 900, 'ingredients' => [$fiorDiLatte, $cotto, $funghi]],
                    ],
                ],
                'le-chiuse' => [
                    'name' => $t('Le chiuse', 'Calzoni'),
                    'description' => $t('Calzoni al forno, ripieni fino al bordo.', 'Baked calzoni, filled to the brim.'),
                    'items' => [
                        ['name' => $t('Calzone classico', 'Classic calzone'), 'price' => 900, 'ingredients' => ['Ricotta', $t('Salame', 'Salami'), $fiorDiLatte, $pomodoro]],
                        ['name' => $t('Calzone ortolano', 'Vegetable calzone'), 'price' => 850, 'ingredients' => ['Ricotta', $t('Verdure di stagione', 'Seasonal vegetables'), $fiorDiLatte], 'tags' => ['vegetariano']],
                        ['name' => $t('Calzone della casa', 'House calzone'), 'price' => 950, 'ingredients' => [$esempio]],
                    ],
                ],
            ],
        ],
        'baguette' => [
            'name' => 'Baguette',
            'description' => $t('Croccanti fuori, morbide dentro.', 'Crunchy outside, soft inside.'),
            'items' => [
                ['name' => $t('Baguette prosciutto', 'Ham baguette'), 'price' => 750, 'ingredients' => [$cotto, $fiorDiLatte, $pomodoro]],
                ['name' => $t('Baguette salame', 'Salami baguette'), 'price' => 800, 'ingredients' => [$t('Salame', 'Salami'), 'Provola', ['name' => $t('Rucola', 'Rocket'), 'after' => true]]],
                ['name' => $t('Baguette vegetariana', 'Vegetarian baguette'), 'price' => 800, 'ingredients' => [$t('Verdure grigliate', 'Grilled vegetables'), 'Scamorza'], 'tags' => ['vegetariano']],
            ],
        ],
        'panuozzi' => [
            'name' => 'Panuozzi',
            'description' => $t('Impasto di pizza, cotto nel forno, farcito.', 'Pizza dough, baked in the oven, stuffed.'),
            'items' => [
                ['name' => $t('Panuozzo di esempio', 'Sample panuozzo'), 'price' => 850, 'ingredients' => [$esempio]],
                ['name' => $t('Panuozzo della casa', 'House panuozzo'), 'notes' => $t('Servito con: testo di esempio.', 'Served with: sample text.'), 'price' => 900, 'ingredients' => [$esempio]],
                ['name' => $t('Panuozzo speciale', 'Special panuozzo'), 'price' => 950, 'ingredients' => [$esempio]],
            ],
        ],
        'tegamini' => [
            'name' => 'Tegamini',
            'description' => $t('Da dividere, da fare la scarpetta.', 'To share, perfect for mopping up the sauce.'),
            'items' => [
                ['name' => $t('Tegamino di esempio', 'Sample tegamino'), 'price' => 650, 'ingredients' => [$esempio]],
                ['name' => $t('Tegamino della casa', 'House tegamino'), 'price' => 700, 'ingredients' => [$esempio]],
                ['name' => $t('Tegamino speciale', 'Special tegamino'), 'price' => 800, 'ingredients' => [$esempio, ['name' => $esempio, 'after' => true]]],
            ],
        ],
    ],
];
