<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    /**
     * @return array<string, array<string, mixed>> JSON-LD blocks of the page, keyed by @type
     */
    private function jsonLd(string $uri): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $this->get($uri)->getContent(), $matches);

        return collect($matches[1])->map(fn (string $json) => json_decode($json, true, flags: JSON_THROW_ON_ERROR))->keyBy('@type')->all();
    }

    public function test_every_page_describes_the_restaurant(): void
    {
        foreach (['/', '/menu', '/contatti', '/en/contact'] as $uri) {
            $this->assertArrayHasKey('Restaurant', $this->jsonLd($uri), $uri);
        }

        $restaurant = $this->jsonLd('/')['Restaurant'];

        $this->assertSame('https://schema.org', $restaurant['@context']);
        $this->assertSame('Visciano 82', $restaurant['name']);
        $this->assertArrayNotHasKey('alternateName', $restaurant);
        $this->assertSame('+390499830186', $restaurant['telephone']);
        $this->assertSame('Vigonovo', $restaurant['address']['addressLocality']);
        $this->assertSame('30030', $restaurant['address']['postalCode']);
        $this->assertSame(45.3770736, $restaurant['geo']['latitude']);
        $this->assertSame(url('/menu'), $restaurant['hasMenu']);
        $this->assertTrue($restaurant['acceptsReservations']);
        $this->assertContains('https://www.instagram.com/mundial82/', $restaurant['sameAs']);
        $this->assertSame(
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'], 'opens' => '18:00', 'closes' => '23:59'],
            $restaurant['openingHoursSpecification'][0],
        );
        $this->assertCount(2, $restaurant['openingHoursSpecification'], 'Monday is closed: no entry');
    }

    public function test_announced_closures_are_special_opening_hours(): void
    {
        Carbon::setTestNow('2026-08-01 12:00');
        config(['site.closures' => [['dal' => '2026-08-10', 'al' => '2026-08-20']]]);

        $special = $this->jsonLd('/')['Restaurant']['specialOpeningHoursSpecification'];

        $this->assertSame([['@type' => 'OpeningHoursSpecification', 'opens' => '00:00', 'closes' => '00:00', 'validFrom' => '2026-08-10', 'validThrough' => '2026-08-20']], $special);
        Carbon::setTestNow();
    }

    public function test_the_menu_page_describes_the_visible_menu(): void
    {
        config(['menu.tags' => ['vegetariano' => 'Vegetariana']]);
        config(['menu.sections' => ['pizze' => ['name' => 'Pizze', 'children' => ['classiche' => [
            'name' => 'Classiche',
            'description' => 'Di sempre',
            'items' => [
                ['name' => 'Margherita', 'price' => 700, 'ingredients' => ['Pomodoro', 'Mozzarella'], 'tags' => ['vegetariano']],
                ['name' => 'Senza prezzo', 'price' => null, 'ingredients' => []],
            ],
        ]]]]]);

        $menu = $this->jsonLd('/menu')['Menu'];
        $section = $menu['hasMenuSection'][0]['hasMenuSection'][0];

        $this->assertSame(url('/menu'), $menu['url']);
        $this->assertSame('Pizze', $menu['hasMenuSection'][0]['name']);
        $this->assertSame('Classiche', $section['name']);
        $this->assertSame(['@type' => 'MenuItem', 'name' => 'Margherita', 'description' => 'Pomodoro, Mozzarella', 'offers' => ['@type' => 'Offer', 'price' => '7.00', 'priceCurrency' => 'EUR'], 'suitableForDiet' => 'https://schema.org/VegetarianDiet'], $section['hasMenuItem'][0]);
        $this->assertArrayNotHasKey('offers', $section['hasMenuItem'][1]);
        $this->assertArrayNotHasKey('Menu', $this->jsonLd('/'));
    }
}
