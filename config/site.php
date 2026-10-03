<?php

/*
 * Business data and page map shown on the public pages.
 * `vat` is still missing from the client: it is hidden while null.
 * `hours` use translation keys for the days (lang files, site.php); `open` null means closed.
 */
return [
    'phone' => '049 983 0186',
    'address' => 'Via Cadiceto, 30030 Vigonovo VE',
    'vat' => null,
    'hours' => [
        ['days' => 'monday', 'open' => null],
        ['days' => 'tuesday-saturday', 'open' => '18:00 – 00:00'],
        ['days' => 'sunday', 'open' => '18:30 – 00:00'],
    ],

    /*
     * Languages (the first one is the default, served without prefix) and pages:
     * page key => Blade view and localized URI. Add a page here to get its routes,
     * navigation entry and hreflang alternates.
     */
    'locales' => ['it', 'en'],
    'pages' => [
        'home' => ['view' => 'home', 'uri' => ['it' => '/', 'en' => '/en']],
        'menu' => ['view' => 'menu', 'uri' => ['it' => '/menu', 'en' => '/en/menu']],
        'story' => ['view' => 'storia', 'uri' => ['it' => '/la-nostra-storia', 'en' => '/en/our-story']],
        'contact' => ['view' => 'contatti', 'uri' => ['it' => '/contatti', 'en' => '/en/contact']],
    ],
];
