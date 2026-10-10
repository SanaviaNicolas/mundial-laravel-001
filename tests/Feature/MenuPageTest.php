<?php

namespace Tests\Feature;

use App\Support\Menu;
use Tests\Concerns\FakesPhotos;
use Tests\TestCase;

class MenuPageTest extends TestCase
{
    use FakesPhotos;

    public function test_menu_responds_ok_with_title_and_description(): void
    {
        $this->get('/menu')
            ->assertOk()
            ->assertSee('<title>Menù — pizze, panuozzi e cucina | Visciano 82</title>', false)
            ->assertSee('<meta name="description" content="', false);
    }

    public function test_menu_has_exactly_one_h1(): void
    {
        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $this->get('/menu')->getContent()));
    }

    public function test_menu_lists_every_category_with_an_h2_and_an_anchor(): void
    {
        $response = $this->get('/menu');
        $categories = Menu::categories();

        $this->assertCount(9, $categories);
        // One h2 per category plus "In evidenza" for the highlighted pizzas.
        $this->assertSame(count($categories) + 1, preg_match_all('/<h2[\s>]/i', $response->getContent()));

        foreach ($categories as $slug => $category) {
            $response->assertSee('id="'.$slug.'"', false)
                ->assertSee('href="#'.$slug.'"', false)
                ->assertSee($category['nome']);
        }
    }

    public function test_menu_shows_items_with_ingredients_and_price(): void
    {
        config(['menu' => ['prova' => [
            'nome' => 'Categoria prova',
            'voci' => [['nome' => 'Pizza prova', 'ingredienti' => 'Ingrediente uno, due', 'prezzo' => '€ 7,50']],
        ]]]);

        $this->get('/menu')
            ->assertSee('Pizza prova')
            ->assertSee('Ingrediente uno, due')
            ->assertSee('€ 7,50');
    }

    public function test_menu_does_not_show_todo_markers(): void
    {
        $this->get('/menu')->assertDontSee('TODO');
    }

    public function test_menu_has_no_zero_prices(): void
    {
        $this->get('/menu')->assertDontSee('00,00');
    }

    public function test_menu_shows_badges_on_highlighted_items_and_features_them(): void
    {
        $response = $this->get('/menu');

        $response->assertSeeText('Pizza del mese')
            ->assertSeeText('La più scelta')
            ->assertSeeText('In evidenza');
    }

    public function test_menu_without_badges_has_no_highlight_section(): void
    {
        config(['menu' => ['prova' => [
            'nome' => 'Categoria prova',
            'voci' => [['nome' => 'Pizza prova', 'ingredienti' => 'a, b', 'prezzo' => '€ 7,50']],
        ]]]);

        $this->get('/menu')
            ->assertDontSeeText('In evidenza')
            ->assertDontSeeText('Pizza del mese');
    }

    public function test_badge_is_shown_next_to_the_item_that_has_it(): void
    {
        config(['menu' => ['prova' => [
            'nome' => 'Categoria prova',
            'voci' => [['nome' => 'Pizza prova', 'ingredienti' => 'a, b', 'prezzo' => '€ 7,50', 'badge' => 'scelta']],
        ]]]);

        $this->get('/menu')->assertSeeText('La più scelta')->assertDontSeeText('Pizza del mese');
    }

    public function test_menu_has_a_category_switcher_panel_listing_every_category(): void
    {
        $response = $this->get('/menu')
            ->assertSee('popovertarget="categorie-menu"', false)
            ->assertSee('id="categorie-menu" popover', false);

        foreach (Menu::categories() as $slug => $categoria) {
            $response->assertSee('href="#'.$slug.'" data-name="'.$categoria['nome'].'"', false);
        }
    }

    public function test_each_category_is_illustrated_by_its_own_photo_and_featured_items_reuse_it(): void
    {
        config(['menu' => ['prova' => [
            'nome' => 'Categoria prova',
            'voci' => [['nome' => 'Pizza prova', 'ingredienti' => 'a, b', 'prezzo' => '€ 7,50', 'badge' => 'mese']],
        ]]]);
        $this->fakePhoto('categoria-prova');

        $html = $this->get('/menu')->getContent();

        // One in the featured card, one next to the category.
        $this->assertSame(2, substr_count($html, 'images/categoria-prova-640.webp 640w'));
    }

    public function test_menu_numbers_the_categories(): void
    {
        $this->get('/menu')->assertSeeInOrder(['01', 'Tradizione napoletana', '02', 'Le classiche']);
    }

    public function test_menu_states_the_dough_qualities(): void
    {
        $this->get('/menu')->assertSeeTextInOrder(['Almeno 2 giorni', 'Alta idratazione', 'Alta digeribilità']);
    }
}
