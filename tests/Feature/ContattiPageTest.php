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
            ->assertSee('18:30')
            ->assertSee('https://www.google.com/maps/search/?api=1&amp;query='.urlencode('Via Prova 9, 00100 Città'), false);
    }

    public function test_contatti_shows_the_social_profiles(): void
    {
        $main = str($this->get('/contatti')->getContent())->after('<main')->before('</main>');

        $this->assertStringContainsString('href="https://www.instagram.com/mundial82/"', $main);
        $this->assertStringContainsString('href="https://www.facebook.com/Mundial82"', $main);
        $this->assertStringContainsString('aria-label="Instagram (si apre in una nuova scheda)"', $main);
        $this->assertStringNotContainsString('@mundial82', $main);
    }
}
