<?php

/*
 * Public menu. `source` picks where it comes from: `static` (this file, until the menu is filled
 * in Filament) or `database` (the admin data). Both give the same contract (app/Menu).
 *
 * Static data mirrors the admin model (docs/frontend/dati-menu.md). Its content is PROVISIONAL and
 * partly invented (items, ingredients, prices) so the page reads like a real menu: it will be
 * replaced by the restaurant's real list.
 * - sections keyed by slug, on two levels (`children`); `name`, optional `description`;
 *   `addons` apply to every item of the section and of its subsections;
 * - items: `name`, optional `description` and `notes`, `price` in cents (null = not known yet,
 *   nothing is shown), `ingredients`, `tags` (slugs of the `tags` list), optional `addons`;
 * - an ingredient is a name or ['name' => ..., 'frozen' => true, 'after' => true, 'section' => ...];
 * - addons: ['name' => ..., 'price' => cents (0 = no supplement)].
 * Texts are a plain string (same in every language) or ['it' => ..., 'en' => ...].
 * English texts are drafts to be reviewed with the client.
 */

$t = fn (string $it, string $en) => ['it' => $it, 'en' => $en];
$after = fn (string|array $name) => ['name' => $name, 'after' => true];

$pomodoro = $t('Pomodoro', 'Tomato');
$fiorDiLatte = $t('Fior di latte', 'Fior di latte mozzarella');
$mozzarella = 'Mozzarella';
$bufala = $t('Mozzarella di bufala', 'Buffalo mozzarella');
$basilico = $after($t('Basilico', 'Basil'));
$olio = $after($t('Olio extravergine', 'Extra virgin olive oil'));
$cotto = $t('Prosciutto cotto', 'Cooked ham');
$crudo = $t('Prosciutto crudo', 'Cured ham');
$funghi = $t('Funghi', 'Mushrooms');
$salsiccia = $t('Salsiccia', 'Sausage');
$friarielli = $t('Friarielli', 'Friarielli (Neapolitan broccoli rabe)');
$ricotta = 'Ricotta';
$provola = 'Provola';
$salame = $t('Salame', 'Salami');
$parmigiano = $t('Parmigiano', 'Parmesan');

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
                ['name' => $t('Aggiunta di un ingrediente', 'Extra topping'), 'price' => 100],
                ['name' => $t('Doppia mozzarella', 'Double mozzarella'), 'price' => 150],
                ['name' => $t('Mozzarella di bufala al posto del fior di latte', 'Buffalo mozzarella instead of fior di latte'), 'price' => 200],
            ],
            'children' => [
                'tradizione-napoletana' => [
                    'name' => $t('Tradizione napoletana', 'Traditional Neapolitan'),
                    'description' => $t('Con bordo alto, come si fa a Napoli.', 'With a puffy crust, the way they make it in Naples.'),
                    'items' => [
                        ['name' => 'Marinara', 'price' => 600, 'ingredients' => [$pomodoro, $t('Aglio', 'Garlic'), $t('Origano', 'Oregano'), $olio], 'tags' => ['vegetariano']],
                        ['name' => 'Margherita', 'price' => 700, 'ingredients' => [$pomodoro, $fiorDiLatte, $basilico, $olio], 'tags' => ['la-piu-scelta', 'vegetariano']],
                        ['name' => $t('Margherita con bufala', 'Margherita with buffalo mozzarella'), 'price' => 950, 'ingredients' => [$pomodoro, $bufala, $basilico, $olio], 'tags' => ['vegetariano']],
                        ['name' => 'Cosacca', 'price' => 750, 'ingredients' => [$pomodoro, $t('Pecorino romano', 'Pecorino romano'), $basilico, $olio], 'tags' => ['vegetariano']],
                    ],
                ],
                'le-classiche' => [
                    'name' => $t('Le classiche', 'The classics'),
                    'description' => $t('Quelle che non stancano mai.', 'The ones you never get tired of.'),
                    'items' => [
                        ['name' => 'Diavola', 'price' => 850, 'ingredients' => [$pomodoro, $mozzarella, $t('Salame piccante', 'Spicy salami')], 'tags' => ['piccante']],
                        ['name' => 'Napoli', 'price' => 800, 'ingredients' => [$pomodoro, $mozzarella, $t('Alici', 'Anchovies'), $t('Capperi', 'Capers'), $t('Origano', 'Oregano')]],
                        ['name' => 'Capricciosa', 'price' => 1000, 'ingredients' => [$pomodoro, $mozzarella, $cotto, $funghi, $t('Carciofi', 'Artichokes'), $t('Olive', 'Olives')]],
                        ['name' => $t('Quattro stagioni', 'Four seasons'), 'price' => 1000, 'ingredients' => [$pomodoro, $mozzarella, $cotto, ['name' => $funghi, 'frozen' => true], $t('Carciofi', 'Artichokes'), $t('Olive', 'Olives')]],
                        ['name' => $t('Tonno e cipolla', 'Tuna and onion'), 'price' => 900, 'ingredients' => [$pomodoro, $mozzarella, $t('Tonno', 'Tuna'), $t('Cipolla rossa', 'Red onion')]],
                    ],
                ],
                'le-speciali' => [
                    'name' => $t('Le speciali', 'The specials'),
                    'description' => $t('Le idee della casa.', 'The house ideas.'),
                    'items' => [
                        ['name' => 'Visciano 82', 'notes' => $t('La pizza della casa.', 'Our house pizza.'), 'price' => 1150, 'ingredients' => [$fiorDiLatte, $provola, $salsiccia, $t('Funghi porcini', 'Porcini mushrooms'), $after($t('Scaglie di parmigiano', 'Parmesan shavings'))], 'tags' => ['novita']],
                        ['name' => $t('Salsiccia e friarielli', 'Sausage and friarielli'), 'price' => 1050, 'ingredients' => [$fiorDiLatte, $salsiccia, $friarielli], 'tags' => ['pizza-del-mese']],
                        ['name' => $t('Mortadella e pistacchio', 'Mortadella and pistachio'), 'price' => 1200, 'ingredients' => [$fiorDiLatte, $after('Mortadella'), $after($t('Stracciatella', 'Stracciatella')), $after($t('Granella di pistacchio', 'Chopped pistachios'))]],
                        ['name' => $t('Bufalina gialla', 'Yellow bufalina'), 'price' => 1100, 'ingredients' => [$t('Pomodorino giallo', 'Yellow cherry tomatoes'), $bufala, $basilico, $olio], 'tags' => ['vegetariano']],
                        ['name' => $t('Mezza e mezza', 'Half and half'), 'notes' => $t('Mezza pizza e mezzo panuozzo.', 'Half pizza, half panuozzo.'), 'price' => 1200, 'ingredients' => [
                            ['name' => $mozzarella, 'section' => $t('Mezza pizza', 'Half pizza')],
                            ['name' => $t('Pomodorino', 'Cherry tomatoes'), 'section' => $t('Mezza pizza', 'Half pizza')],
                            ['name' => $t('Stracciatella', 'Stracciatella'), 'after' => true, 'section' => $t('Mezza pizza', 'Half pizza')],
                            ['name' => $t('Rucola', 'Rocket'), 'after' => true, 'section' => $t('Mezzo panuozzo', 'Half panuozzo')],
                            ['name' => $crudo, 'after' => true, 'section' => $t('Mezzo panuozzo', 'Half panuozzo')],
                        ]],
                    ],
                ],
                'fiorfritta-coccodrillo' => [
                    'name' => 'Fiorfritta / Coccodrillo',
                    'description' => $t('Fritte in modo leggero, ripiene a piacere.', 'Lightly fried, filled as you like.'),
                    'items' => [
                        ['name' => 'Montanara', 'notes' => $t('Base fritta.', 'Fried base.'), 'price' => 750, 'ingredients' => [$pomodoro, $parmigiano, $basilico], 'tags' => ['vegetariano']],
                        ['name' => $t('Fiorfritta ricotta e salame', 'Ricotta and salami fiorfritta'), 'price' => 850, 'ingredients' => [$ricotta, $salame, $provola, $t('Pepe', 'Pepper')]],
                        ['name' => $t('Coccodrillo classico', 'Classic coccodrillo'), 'price' => 900, 'ingredients' => [$ricotta, $cotto, $mozzarella, $pomodoro]],
                    ],
                ],
                'le-bianche' => [
                    'name' => $t('Le bianche', 'White pizzas'),
                    'description' => $t('Senza pomodoro.', 'No tomato sauce.'),
                    'items' => [
                        ['name' => $t('Quattro formaggi', 'Four cheeses'), 'price' => 950, 'ingredients' => [$fiorDiLatte, 'Gorgonzola', $provola, $parmigiano], 'tags' => ['vegetariano']],
                        ['name' => $t('Ortolana', 'Garden vegetables'), 'price' => 900, 'ingredients' => [$fiorDiLatte, $t('Zucchine', 'Courgettes'), $t('Melanzane', 'Aubergines'), $t('Peperoni', 'Peppers')], 'tags' => ['stagionale', 'vegetariano']],
                        ['name' => $t('Patate e salsiccia', 'Potatoes and sausage'), 'price' => 950, 'ingredients' => [$fiorDiLatte, $t('Patate al forno', 'Roast potatoes'), $salsiccia, $after($t('Rosmarino', 'Rosemary'))]],
                        ['name' => $t('Prosciutto e funghi', 'Ham and mushrooms'), 'price' => 900, 'ingredients' => [$fiorDiLatte, $cotto, $funghi]],
                    ],
                ],
                'le-chiuse' => [
                    'name' => $t('Le chiuse', 'Calzoni'),
                    'description' => $t('Calzoni al forno, ripieni fino al bordo.', 'Baked calzoni, filled to the brim.'),
                    'items' => [
                        ['name' => $t('Calzone classico', 'Classic calzone'), 'price' => 900, 'ingredients' => [$ricotta, $salame, $fiorDiLatte, $pomodoro]],
                        ['name' => $t('Calzone ortolano', 'Vegetable calzone'), 'price' => 850, 'ingredients' => [$ricotta, $t('Verdure di stagione', 'Seasonal vegetables'), $fiorDiLatte], 'tags' => ['vegetariano']],
                        ['name' => $t('Calzone fritto', 'Fried calzone'), 'notes' => $t('Fritto, come a Napoli.', 'Fried, the Neapolitan way.'), 'price' => 900, 'ingredients' => [$ricotta, $salame, $provola, $t('Pepe', 'Pepper')]],
                    ],
                ],
            ],
        ],
        'baguette' => [
            'name' => 'Baguette',
            'description' => $t('Croccanti fuori, morbide dentro.', 'Crunchy outside, soft inside.'),
            'items' => [
                ['name' => $t('Baguette prosciutto', 'Ham baguette'), 'price' => 750, 'ingredients' => [$cotto, $fiorDiLatte, $pomodoro]],
                ['name' => $t('Baguette salame', 'Salami baguette'), 'price' => 800, 'ingredients' => [$salame, $provola, $after($t('Rucola', 'Rocket'))]],
                ['name' => $t('Baguette vegetariana', 'Vegetarian baguette'), 'price' => 800, 'ingredients' => [$t('Verdure grigliate', 'Grilled vegetables'), 'Scamorza'], 'tags' => ['vegetariano']],
            ],
        ],
        'panuozzi' => [
            'name' => 'Panuozzi',
            'description' => $t('Impasto di pizza, cotto nel forno, farcito.', 'Pizza dough, baked in the oven, stuffed.'),
            'items' => [
                ['name' => $t('Panuozzo salsiccia e friarielli', 'Sausage and friarielli panuozzo'), 'price' => 900, 'ingredients' => [$salsiccia, $friarielli, $provola]],
                ['name' => $t('Panuozzo crudo e bufala', 'Cured ham and buffalo mozzarella panuozzo'), 'price' => 950, 'ingredients' => [$after($crudo), $after($bufala), $after($t('Rucola', 'Rocket'))]],
                ['name' => $t('Panuozzo porchetta e provola', 'Porchetta and provola panuozzo'), 'notes' => $t('Servito con patatine.', 'Served with chips.'), 'price' => 950, 'ingredients' => ['Porchetta', $provola, $t('Funghi trifolati', 'Sautéed mushrooms')]],
            ],
        ],
        'tegamini' => [
            'name' => 'Tegamini',
            'description' => $t('Da dividere, da fare la scarpetta.', 'To share, perfect for mopping up the sauce.'),
            'items' => [
                ['name' => $t('Parmigiana di melanzane', 'Aubergine parmigiana'), 'price' => 700, 'ingredients' => [$t('Melanzane', 'Aubergines'), $pomodoro, $fiorDiLatte, $parmigiano, $basilico], 'tags' => ['vegetariano']],
                ['name' => $t('Polpette al sugo', 'Meatballs in tomato sauce'), 'price' => 750, 'ingredients' => [$t('Polpette di manzo', 'Beef meatballs'), $pomodoro, $parmigiano]],
                ['name' => $t('Salsiccia e patate', 'Sausage and potatoes'), 'price' => 750, 'ingredients' => [$salsiccia, $t('Patate al forno', 'Roast potatoes'), $after($t('Rosmarino', 'Rosemary'))]],
            ],
        ],
        'dolci' => [
            'name' => $t('Dolci', 'Desserts'),
            'description' => $t('Per chiudere in dolcezza.', 'A sweet way to finish.'),
            'items' => [
                ['name' => $t('Tiramisù', 'Tiramisù'), 'description' => $t('Fatto in casa.', 'Homemade.'), 'price' => 500],
                ['name' => $t('Pastiera napoletana', 'Neapolitan pastiera'), 'description' => $t('Grano, ricotta e fiori d\'arancio.', 'Wheat, ricotta and orange blossom.'), 'price' => 500],
                ['name' => $t('Babà al rum', 'Rum babà'), 'price' => 500],
                ['name' => $t('Delizia al limone', 'Lemon delight'), 'description' => $t('Pan di spagna e crema al limone.', 'Sponge cake and lemon cream.'), 'price' => 550],
                ['name' => $t('Sorbetto al limone', 'Lemon sorbet'), 'price' => 350],
            ],
        ],
        'bevande' => [
            'name' => $t('Bevande', 'Drinks'),
            'children' => [
                'bibite' => [
                    'name' => $t('Bibite analcoliche', 'Soft drinks'),
                    'items' => [
                        ['name' => $t('Acqua naturale o frizzante', 'Still or sparkling water'), 'description' => $t('Bottiglia da 0,75 l.', '0.75 l bottle.'), 'price' => 250],
                        ['name' => 'Coca-Cola', 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 300],
                        ['name' => 'Coca-Cola Zero', 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 300],
                        ['name' => 'Fanta', 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 300],
                        ['name' => $t('Tè al limone', 'Lemon iced tea'), 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 300],
                        ['name' => $t('Tè alla pesca', 'Peach iced tea'), 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 300],
                    ],
                ],
                'birre' => [
                    'name' => $t('Birre', 'Beers'),
                    'items' => [
                        ['name' => $t('Birra alla spina piccola', 'Small draught beer'), 'description' => '0,2 l', 'price' => 350],
                        ['name' => $t('Birra alla spina media', 'Medium draught beer'), 'description' => '0,4 l', 'price' => 550],
                        ['name' => 'Peroni Nastro Azzurro', 'description' => $t('Bottiglia da 33 cl.', '33 cl bottle.'), 'price' => 400],
                        ['name' => $t('Ichnusa non filtrata', 'Ichnusa unfiltered'), 'description' => $t('Bottiglia da 50 cl.', '50 cl bottle.'), 'price' => 550],
                    ],
                ],
                'vini' => [
                    'name' => $t('Vini', 'Wines'),
                    'items' => [
                        ['name' => $t('Vino della casa', 'House wine'), 'description' => $t('Rosso o bianco, caraffa da ½ litro.', 'Red or white, ½ litre carafe.'), 'price' => 700],
                        ['name' => 'Falanghina', 'description' => $t('Bianco campano, al calice.', 'White wine from Campania, by the glass.'), 'price' => 500],
                        ['name' => 'Aglianico', 'description' => $t('Rosso campano, al calice.', 'Red wine from Campania, by the glass.'), 'price' => 500],
                        ['name' => 'Prosecco', 'description' => $t('Bollicine, al calice.', 'Sparkling, by the glass.'), 'price' => 500],
                    ],
                ],
            ],
        ],
    ],
];
