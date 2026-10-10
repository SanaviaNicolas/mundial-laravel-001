<?php

namespace Tests\Feature;

use App\Menu\MenuSource;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    /** @return array<string, array{string, string, string}> */
    public static function englishPages(): array
    {
        return [
            'home' => ['/en', 'Visciano 82 (formerly Mundial 82) — Pizzeria in Vigonovo', '/'],
            'menu' => ['/en/menu', 'Menu — pizzas, panuozzi and kitchen | Visciano 82', '/menu'],
            'story' => ['/en/our-story', 'Our story — family-run Neapolitan pizza | Visciano 82', '/la-nostra-storia'],
            'contact' => ['/en/contact', 'Contact, opening hours and where to find us | Visciano 82', '/contatti'],
        ];
    }

    #[DataProvider('englishPages')]
    public function test_english_pages_respond_in_english_and_link_to_the_italian_version(string $uri, string $title, string $italianUri): void
    {
        $this->get($uri)
            ->assertOk()
            ->assertSee('<html lang="en"', false)
            ->assertSee('<title>'.$title.'</title>', false)
            ->assertSee('<link rel="alternate" hreflang="it" href="'.url($italianUri).'">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url($uri).'">', false)
            ->assertSee('<link rel="canonical" href="'.url($uri).'">', false)
            ->assertSee('<a href="'.$italianUri.'" hreflang="it" lang="it"', false);
    }

    public function test_italian_pages_declare_their_english_alternate_and_canonical(): void
    {
        $this->get('/menu')
            ->assertSee('<html lang="it"', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.url('/en/menu').'">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="'.url('/menu').'">', false)
            ->assertSee('<link rel="canonical" href="'.url('/menu').'">', false)
            ->assertSee('<a href="/en/menu" hreflang="en" lang="en"', false);
    }

    public function test_english_navigation_links_to_english_pages(): void
    {
        $this->get('/en')
            ->assertSee('href="/en/menu"', false)
            ->assertSee('href="/en/our-story"', false)
            ->assertSee('href="/en/contact"', false)
            ->assertDontSee('Guarda il menù');
    }

    public function test_english_menu_is_translated_and_prices_use_english_format(): void
    {
        $this->get('/en/menu')
            ->assertSeeText('Pizza of the month')
            ->assertSeeText('Most ordered')
            ->assertSeeText('Featured')
            ->assertSeeText('Traditional Neapolitan')
            ->assertSeeText('€ 7.00');
    }

    public function test_italian_menu_keeps_italian_price_format(): void
    {
        $this->get('/menu')->assertSeeText('€ 7,00');
    }

    public function test_english_pages_have_one_h1_and_no_horizontal_hooks_missing(): void
    {
        foreach (['/en', '/en/menu', '/en/our-story', '/en/contact'] as $uri) {
            $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $this->get($uri)->getContent()), $uri);
        }
    }

    public function test_english_home_links_each_menu_category_to_the_english_menu(): void
    {
        $response = $this->get('/en');

        foreach (app(MenuSource::class)->sections() as $section) {
            foreach ($section->leaves() as $leaf) {
                $response->assertSee('href="/en/menu#'.$leaf->slug.'"', false);
            }
        }
    }
}
