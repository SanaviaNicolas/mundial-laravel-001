<?php

namespace Tests\Feature;

use App\Menu\MenuSource;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Blade;
use Tests\Concerns\FakesPhotos;
use Tests\TestCase;

class MenuPageTest extends TestCase
{
    use FakesPhotos;

    /**
     * @param  array<string, mixed>  $item
     */
    private function oneItem(array $item, array $section = []): void
    {
        config(['menu.sections' => ['prova' => ['name' => 'Categoria prova', 'items' => [$item + ['name' => 'Pizza prova', 'price' => 750, 'ingredients' => []]]] + $section]]);
    }

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

    public function test_macro_categories_are_h2_and_their_subcategories_h3_with_items_one_level_below(): void
    {
        config(['menu.sections' => [
            'pizze' => ['name' => 'Pizze', 'children' => [
                'classiche' => ['name' => 'Classiche', 'items' => [['name' => 'Diavola', 'price' => 800, 'ingredients' => []]]],
            ]],
            'baguette' => ['name' => 'Baguette', 'items' => [['name' => 'Baguette prova', 'price' => 700, 'ingredients' => []]]],
        ]]);

        $html = $this->get('/menu')->getContent();

        $this->assertMatchesRegularExpression('/<section id="pizze"[^>]*>.*<h2[^>]*>Pizze<\/h2>/s', $html);
        $this->assertMatchesRegularExpression('/<section id="classiche"[^>]*>.*<h3[^>]*>Classiche<\/h3>.*<h4[^>]*>Diavola<\/h4>/s', $html);
        $this->assertMatchesRegularExpression('/<section id="baguette"[^>]*>.*<h2[^>]*>Baguette<\/h2>.*<h3[^>]*>Baguette prova<\/h3>/s', $html);
    }

    public function test_navigation_lists_and_numbers_the_categories_that_hold_items(): void
    {
        $response = $this->get('/menu')
            ->assertSee('popovertarget="categorie-menu"', false)
            ->assertSee('id="categorie-menu" popover', false)
            ->assertSeeInOrder(['01', 'Tradizione napoletana', '02', 'Le classiche']);

        foreach (app(MenuSource::class)->sections() as $section) {
            foreach ($section->leaves() as $leaf) {
                $response->assertSee('href="#'.$leaf->slug.'" data-name="'.$leaf->name.'"', false);
            }
        }
    }

    public function test_items_show_ingredients_after_cooking_ingredients_notes_and_price(): void
    {
        $this->oneItem(['description' => 'Descrizione prova', 'notes' => 'Base fritta', 'ingredients' => ['Pomodoro', 'Mozzarella', ['name' => 'Basilico', 'after' => true]]]);

        $this->get('/menu')
            ->assertSeeText('Pomodoro, Mozzarella')
            ->assertSeeText('A fine cottura: Basilico')
            ->assertSeeText('Descrizione prova')
            ->assertSeeText('Base fritta')
            ->assertSeeText('€ 7,50');
    }

    public function test_ingredient_sections_are_shown_as_titled_groups(): void
    {
        $this->oneItem(['ingredients' => [
            ['name' => 'Mozzarella', 'section' => 'Mezza pizza'],
            ['name' => 'Rucola', 'section' => 'Mezzo panuozzo', 'after' => true],
        ]]);

        $this->get('/menu')->assertSeeTextInOrder(['Mezza pizza', 'Mozzarella', 'Mezzo panuozzo', 'A fine cottura: Rucola']);
    }

    public function test_frozen_ingredients_get_an_asterisk_and_the_page_a_legend(): void
    {
        $this->oneItem(['ingredients' => [['name' => 'Gamberetti', 'frozen' => true]]]);
        $this->get('/menu')->assertSeeText('Gamberetti*')->assertSeeText('* Prodotto surgelato');

        $this->oneItem(['ingredients' => ['Gamberetti']]);
        $this->get('/menu')->assertDontSeeText('Prodotto surgelato');
    }

    public function test_a_missing_price_shows_nothing(): void
    {
        $this->oneItem(['price' => null]);

        $this->get('/menu')->assertSeeText('Pizza prova')->assertDontSee('€');
    }

    public function test_menu_has_no_todo_markers_nor_zero_prices(): void
    {
        $this->get('/menu')->assertDontSee('TODO')->assertDontSee('00,00');
    }

    public function test_tags_are_shown_on_the_item_by_their_name(): void
    {
        config(['menu.tags' => ['piccante' => ['it' => 'Piccante', 'en' => 'Spicy']]]);
        $this->oneItem(['tags' => ['piccante']]);

        $this->get('/menu')->assertSeeText('Piccante');
    }

    public function test_highlight_tags_feature_the_item_without_a_photo(): void
    {
        $this->fakePhoto('categoria-prova');
        $this->oneItem(['tags' => ['pizza-del-mese']]);

        $html = $this->get('/menu')->assertSeeText('In evidenza')->assertSeeText('Pizza del mese')->getContent();

        // The category photo is shown once, next to the category, never on the featured card.
        $this->assertSame(1, substr_count($html, 'images/categoria-prova-640.webp 640w'));
    }

    public function test_without_highlight_tags_there_is_no_featured_section(): void
    {
        $this->oneItem([]);

        $this->get('/menu')->assertDontSeeText('In evidenza');
    }

    public function test_addons_shared_by_every_item_are_shown_once_on_the_category(): void
    {
        config(['menu.sections' => ['prova' => [
            'name' => 'Categoria prova',
            'addons' => [['name' => 'Bufala', 'price' => 200], ['name' => 'Senza lattosio', 'price' => 0]],
            'items' => [
                ['name' => 'Uno', 'price' => 700, 'ingredients' => []],
                ['name' => 'Due', 'price' => 800, 'ingredients' => []],
            ],
        ]]]);

        $html = $this->get('/menu')
            ->assertSeeText('+ € 2,00')
            ->assertSeeText('senza supplemento')
            ->assertSeeText('Per gli allergeni delle aggiunte chiedi al personale.')
            ->getContent();

        $this->assertSame(1, substr_count($html, 'Bufala'));
        $this->assertStringNotContainsString('+ € 0,00', $html);
    }

    public function test_addons_that_differ_are_shown_on_each_item(): void
    {
        config(['menu.sections' => ['prova' => [
            'name' => 'Categoria prova',
            'items' => [
                ['name' => 'Uno', 'price' => 700, 'ingredients' => [], 'addons' => [['name' => 'Bufala', 'price' => 200]]],
                ['name' => 'Due', 'price' => 800, 'ingredients' => []],
            ],
        ]]]);

        $this->get('/menu')->assertSeeTextInOrder(['Uno', 'Bufala', 'Due']);
    }

    public function test_unverified_allergens_are_never_listed_and_invite_to_ask_the_staff(): void
    {
        $this->oneItem([]);

        $this->get('/menu')
            ->assertSeeText('Per informazioni sugli allergeni chiedi al personale.')
            ->assertDontSeeText('Nessun allergene');
    }

    public function test_verified_allergens_are_listed_from_the_database_source(): void
    {
        config(['menu.source' => 'database']);
        $category = Category::factory()->create(['name' => ['it' => 'Dal database'], 'slug' => 'dal-database']);
        $listed = MenuItem::factory()->for($category)->create(['name' => ['it' => 'Con glutine'], 'sort_order' => 1]);
        $listed->allergens()->attach(Allergen::factory()->create(['name' => ['it' => 'Cereali contenenti glutine']]));
        $listed->verifyAllergens();
        MenuItem::factory()->for($category)->create(['name' => ['it' => 'Senza'], 'sort_order' => 2])->verifyAllergens();
        MenuItem::factory()->for($category)->create(['name' => ['it' => 'Da verificare'], 'sort_order' => 3]);

        $this->get('/menu')
            ->assertSeeTextInOrder([
                'Con glutine', 'Allergeni: Cereali contenenti glutine',
                'Senza', 'Nessun allergene',
                'Da verificare', 'Allergeni: chiedi al personale',
            ]);
    }

    public function test_menu_states_the_dough_qualities(): void
    {
        $this->get('/menu')->assertSeeTextInOrder(['Almeno 2 giorni', 'Alta idratazione', 'Alta digeribilità']);
    }

    public function test_each_category_is_illustrated_by_its_own_photo(): void
    {
        $this->fakePhoto('categoria-prova');
        $this->oneItem([]);

        $this->get('/menu')->assertSee('images/categoria-prova-640.webp 640w', false);
    }

    public function test_items_are_not_separated_by_dotted_leaders_or_rules(): void
    {
        $this->oneItem([]);

        $this->get('/menu')->assertDontSee('border-dotted', false)->assertDontSee('divide-y', false);
    }

    public function test_featured_cards_name_their_category_link_to_it_and_put_the_pizza_of_the_month_first(): void
    {
        config(['menu.sections' => ['prova' => ['name' => 'Categoria prova', 'items' => [
            ['name' => 'La scelta', 'price' => 700, 'ingredients' => [], 'tags' => ['la-piu-scelta']],
            ['name' => 'Del mese', 'price' => 800, 'ingredients' => [], 'tags' => ['pizza-del-mese']],
        ]]]]);

        $featured = str($this->get('/menu')->getContent())->after('In evidenza')->before('categorie-menu');

        $this->assertStringContainsString('href="#prova"', $featured);
        $this->assertStringContainsString('Categoria prova', $featured);
        $this->assertLessThan(strpos($featured, 'La scelta'), strpos($featured, 'Del mese'));
    }

    public function test_the_most_chosen_badge_has_white_text_on_dark_blue_for_contrast(): void
    {
        $html = Blade::render('<x-badge slug="la-piu-scelta" nome="La più scelta" />');

        $this->assertStringContainsString('bg-blu-scuro text-white', $html);
    }

    public function test_categories_are_numbered_without_counting_their_dishes(): void
    {
        $this->get('/menu')->assertDontSeeText('proposte')->assertSeeText('01 / 09');
    }

    public function test_featured_cards_are_light(): void
    {
        $this->oneItem(['tags' => ['pizza-del-mese']]);

        $featured = str($this->get('/menu')->getContent())->after('In evidenza')->before('categorie-menu');

        $this->assertStringContainsString('rounded-2xl bg-white', $featured);
        $this->assertStringNotContainsString('rounded-2xl bg-ink', $featured);
    }
}
