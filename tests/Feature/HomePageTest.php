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
}
