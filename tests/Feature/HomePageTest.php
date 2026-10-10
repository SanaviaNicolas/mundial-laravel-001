<?php

namespace Tests\Feature;

use Tests\Concerns\FakesPhotos;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use FakesPhotos;

    public function test_home_responds_ok(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_home_has_essential_markup(): void
    {
        $this->get('/')
            ->assertSee('<html lang="it"', false)
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
            ->assertSee('href="/menu"', false)
            ->assertSee('href="/la-nostra-storia"', false)
            ->assertSee('href="/contatti"', false);
    }

    public function test_home_shows_call_button_from_site_config(): void
    {
        config(['site.phone' => '081 123 4567']);

        $this->get('/')->assertSee('href="tel:0811234567"', false);
    }

    public function test_home_states_the_dough_qualities_in_readable_text(): void
    {
        $this->get('/')
            ->assertSeeText('Almeno 2 giorni di lievitazione')
            ->assertSeeText('Alta digeribilità');
    }

    public function test_home_shows_how_many_items_each_menu_category_has(): void
    {
        config(['menu' => ['prova' => [
            'nome' => 'Categoria prova',
            'descrizione' => 'Descrizione',
            'voci' => [
                ['nome' => 'Uno', 'ingredienti' => 'a', 'prezzo' => '€ 1,00'],
                ['nome' => 'Due', 'ingredienti' => 'b', 'prezzo' => '€ 2,00'],
            ],
        ]]]);

        $this->get('/')->assertSeeText('Categoria prova')->assertSeeText('2 proposte');
    }

    public function test_home_does_not_show_todo_markers(): void
    {
        $this->get('/')->assertDontSee('TODO');
    }

    public function test_home_links_every_menu_category_to_the_menu_page(): void
    {
        $response = $this->get('/');

        foreach (array_keys(config('menu')) as $slug) {
            $response->assertSee('href="/menu#'.$slug.'"', false);
        }
    }

    public function test_home_shows_the_real_contact_details(): void
    {
        $this->get('/')
            ->assertSee('Via Cadiceto, 30030 Vigonovo VE')
            ->assertSee('049 983 0186')
            ->assertSee('href="tel:0499830186"', false);
    }

    public function test_header_has_an_accessible_mobile_menu_button_and_panel(): void
    {
        $this->get('/')
            ->assertSee('popovertarget="menu-principale"', false)
            ->assertSee('aria-label="Apri il menu"', false)
            ->assertSee('id="menu-principale" popover', false)
            ->assertSee('aria-label="Chiudi il menu"', false)
            ->assertSee('<nav aria-label="Menu"', false);
    }

    public function test_header_uses_the_vector_logo_linking_to_the_home_of_the_current_language(): void
    {
        $this->get('/menu')
            ->assertSee('brand/logo.svg#logo', false)
            ->assertSee('<a href="/" class="inline-flex text-current" aria-label="Visciano 82 — Home">', false);

        $this->get('/en/menu')->assertSee('<a href="/en" class="inline-flex text-current" aria-label="Visciano 82 — Home">', false);
    }

    public function test_logo_asset_exists_and_follows_the_text_color(): void
    {
        $svg = file_get_contents(public_path('brand/logo.svg'));

        $this->assertStringContainsString('id="logo"', $svg);
        $this->assertStringContainsString('fill="currentColor"', $svg);
        $this->assertStringContainsString('fill="#0090d0"', $svg);
    }

    public function test_menu_index_previews_the_photo_of_each_category(): void
    {
        config(['menu' => ['prova' => ['nome' => 'Categoria prova', 'voci' => []]]]);
        $this->fakePhoto('categoria-prova');

        $this->get('/')->assertSee('data-img="'.asset('images/categoria-prova-640.webp').'"', false);
    }
}
