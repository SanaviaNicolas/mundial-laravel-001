<?php

namespace Tests\Feature;

use Tests\TestCase;

class MenuPageTest extends TestCase
{
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
        $categories = config('menu');

        $this->assertCount(9, $categories);
        $this->assertSame(count($categories), preg_match_all('/<h2[\s>]/i', $response->getContent()));

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
}
