<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundPageTest extends TestCase
{
    public function test_unknown_pages_get_a_friendly_404_with_links_back(): void
    {
        $response = $this->get('/pagina-che-non-esiste')
            ->assertNotFound()
            ->assertSee('<title>Pagina non trovata | Visciano 82</title>', false)
            ->assertSeeText('Pagina non trovata')
            ->assertSee('href="/menu"', false);

        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $response->getContent()));
    }

    public function test_unknown_english_pages_get_the_404_in_english(): void
    {
        $this->get('/en/missing-page')
            ->assertNotFound()
            ->assertSee('<html lang="en"', false)
            ->assertSeeText('Page not found')
            ->assertSee('href="/en/menu"', false);
    }
}
