<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_responds_ok(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_home_has_essential_markup(): void
    {
        $this->get('/')
            ->assertSee('<html lang="it">', false)
            ->assertSee('<meta name="viewport" content="width=device-width, initial-scale=1">', false)
            ->assertSee('<title>Visciano 82 — Pizzeria e ristorante</title>', false)
            ->assertSee('<meta name="description" content="', false);
    }

    public function test_home_has_exactly_one_h1(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $html));
    }

    public function test_home_has_semantic_landmarks_and_navigation(): void
    {
        $this->get('/')
            ->assertSee('<header', false)
            ->assertSee('<nav aria-label="Principale"', false)
            ->assertSee('<main', false)
            ->assertSee('<footer', false)
            ->assertSee('href="#menu"', false)
            ->assertSee('href="#contatti"', false);
    }

    public function test_home_shows_call_button_from_site_config(): void
    {
        config(['site.phone' => '081 123 4567']);

        $this->get('/')->assertSee('href="tel:0811234567"', false);
    }

    public function test_home_shows_address_and_hours_in_html(): void
    {
        config(['site.address' => 'Via Prova 9, 00100 Città']);

        $this->get('/')
            ->assertSee('Via Prova 9, 00100 Città')
            ->assertSee('<table', false);
    }

    public function test_home_marks_placeholder_photos_as_provisional(): void
    {
        $this->get('/')->assertSee('Foto provvisoria');
    }

    public function test_home_does_not_show_todo_markers(): void
    {
        $this->get('/')->assertDontSee('TODO');
    }
}
