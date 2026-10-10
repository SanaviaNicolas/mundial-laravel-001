<?php

/*
 * Business data and page map shown on the public pages.
 * Legal data (`company`, `vat`, `email`) is still missing from the client: the footer hides the
 * VAT number while null, the legal pages show a recognizable "dato da completare" placeholder.
 * `hours`: `days` is a translation key (lang files, site.php), `day_of_week` the schema.org days,
 * `opens`/`closes` the times (null = closed; `closes` 00:00 = midnight).
 */
return [
    'phone' => '049 983 0186',
    'address' => 'Via Cadiceto, 30030 Vigonovo VE',
    // The same address split up for the structured data (JSON-LD).
    'address_parts' => ['street' => 'Via Cadiceto', 'postal_code' => '30030', 'locality' => 'Vigonovo', 'region' => 'VE', 'country' => 'IT'],
    // The restaurant's Google Maps place (tracking parameters removed) and its coordinates.
    'map_url' => 'https://www.google.com/maps/place/Mundial+82/@45.379143,11.9955429,17z/data=!4m15!1m8!3m7!1s0x477ec449ce78246b:0xedfefc261cb73c13!2sVia+Cadiceto,+30030+Vigonovo+VE!3b1!8m2!3d45.379143!4d11.9955429!16s%2Fg%2F11h15dpsx!3m5!1s0x477ec43712ad0fa9:0x5f0be56a0bb1dfc4!8m2!3d45.3770736!4d12.0008935!16s%2Fg%2F119v9qr2s',
    'geo' => ['lat' => 45.3770736, 'lng' => 12.0008935],
    'company' => null,
    'vat' => null,
    'email' => null,
    // Social profiles (they still use the old name, Mundial 82): plain links, nothing embedded.
    'social' => [
        'instagram' => ['name' => 'Instagram', 'url' => 'https://www.instagram.com/mundial82/'],
        'facebook' => ['name' => 'Facebook', 'url' => 'https://www.facebook.com/Mundial82'],
    ],
    // Real customer reviews chosen by the client (never invented), shown on the home page:
    // ['testo' => ['it' => ..., 'en' => ...], 'autore' => 'Maria R.', 'fonte' => 'Google']
    'reviews' => [],
    // Extraordinary closures, announced on the site (App\Support\Closures); they will move to the admin Settings page.
    // Example: ['dal' => '2026-08-10', 'al' => '2026-08-20', 'motivo' => ['it' => 'Ferie', 'en' => 'Holidays']]
    'closures' => [],
    'hours' => [
        ['days' => 'monday', 'day_of_week' => ['Monday'], 'opens' => null, 'closes' => null],
        ['days' => 'tuesday-saturday', 'day_of_week' => ['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], 'opens' => '18:00', 'closes' => '00:00'],
        ['days' => 'sunday', 'day_of_week' => ['Sunday'], 'opens' => '18:30', 'closes' => '00:00'],
    ],

    /*
     * Pages: page key => Blade view and localized URI (languages are in config/app.php, the
     * first one is the default, served without prefix). Add a page here to get its routes,
     * navigation entry and hreflang alternates.
     */
    'pages' => [
        'home' => ['view' => 'home', 'uri' => ['it' => '/', 'en' => '/en']],
        'menu' => ['view' => 'menu', 'uri' => ['it' => '/menu', 'en' => '/en/menu']],
        'story' => ['view' => 'storia', 'uri' => ['it' => '/la-nostra-storia', 'en' => '/en/our-story']],
        'contact' => ['view' => 'contatti', 'uri' => ['it' => '/contatti', 'en' => '/en/contact']],
        'privacy' => ['view' => 'legale', 'uri' => ['it' => '/privacy', 'en' => '/en/privacy']],
        'cookie' => ['view' => 'legale', 'uri' => ['it' => '/cookie', 'en' => '/en/cookies']],
    ],
];
