<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContattiPageTest extends TestCase
{
    public function test_contatti_responds_ok_with_title_and_description(): void
    {
        $this->get('/contatti')
            ->assertOk()
            ->assertSee('<title>Contatti, orari e dove siamo | Visciano 82</title>', false)
            ->assertSee('<meta name="description" content="', false);
    }

    public function test_contatti_has_exactly_one_h1(): void
    {
        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $this->get('/contatti')->getContent()));
    }

    public function test_contatti_shows_address_phone_and_hours_in_html(): void
    {
        config(['site.address' => 'Via Prova 9, 00100 Città']);

        $this->get('/contatti')
            ->assertSee('Via Prova 9, 00100 Città')
            ->assertSee('href="tel:0499830186"', false)
            ->assertSee('<table', false)
            ->assertSee('18:30');
    }

    public function test_contatti_shows_the_social_profiles(): void
    {
        $main = str($this->get('/contatti')->getContent())->after('<main')->before('</main>');

        $this->assertStringContainsString('href="https://www.instagram.com/mundial82/"', $main);
        $this->assertStringContainsString('href="https://www.facebook.com/Mundial82"', $main);
        $this->assertStringContainsString('aria-label="Instagram (si apre in una nuova scheda)"', $main);
        $this->assertStringNotContainsString('@mundial82', $main);
    }

    public function test_contatti_shows_a_self_hosted_static_map_linking_to_the_exact_google_maps_place(): void
    {
        $main = str($this->get('/contatti')->getContent())->after('<main')->before('</main>');
        $url = e(config('site.map_url'));

        $this->assertStringStartsWith('https://www.google.com/maps/place/Mundial+82/', config('site.map_url'));
        $this->assertStringContainsString('<a href="'.$url.'" target="_blank" rel="noopener"', $main);
        $this->assertStringContainsString('mappa/mappa-800.avif 800w', $main);
        $this->assertStringContainsString('href="https://www.openstreetmap.org/copyright"', $main);
        $this->assertFileExists(public_path('mappa/mappa-1600.webp'));
    }

    public function test_every_map_link_uses_the_same_place_url(): void
    {
        $footer = str($this->get('/')->getContent())->after('<footer')->before('</footer>');

        $this->assertStringContainsString('href="'.e(config('site.map_url')).'"', $footer);
        $this->assertStringNotContainsString('maps/search', $this->get('/contatti')->getContent());
    }
}
