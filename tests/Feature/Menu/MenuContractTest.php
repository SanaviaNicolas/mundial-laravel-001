<?php

namespace Tests\Feature\Menu;

use App\Menu\Addon;
use App\Menu\DatabaseMenu;
use App\Menu\Ingredient;
use App\Menu\Item;
use App\Menu\MenuSource;
use App\Menu\Price;
use App\Menu\Section;
use App\Menu\StaticMenu;
use Tests\TestCase;

class MenuContractTest extends TestCase
{
    private function item(array $ingredients = [], array $addons = [], ?array $allergens = null): Item
    {
        return new Item('Prova', null, null, 700, $ingredients, [], $addons, $allergens);
    }

    public function test_ingredients_are_grouped_by_section_and_split_by_after_cooking(): void
    {
        $item = $this->item([
            new Ingredient('Mozzarella', false, false, 'Mezza pizza'),
            new Ingredient('Stracciatella', false, true, 'Mezza pizza'),
            new Ingredient('Rucola', false, true, 'Mezzo panuozzo'),
        ]);

        $groups = $item->ingredientGroups();

        $this->assertSame(['Mezza pizza', 'Mezzo panuozzo'], array_column($groups, 'section'));
        $this->assertSame(['Mozzarella'], array_column($groups[0]['cooked'], 'name'));
        $this->assertSame(['Stracciatella'], array_column($groups[0]['after'], 'name'));
        $this->assertSame([], $groups[1]['cooked']);
    }

    public function test_a_section_knows_its_leaves_and_shared_addons(): void
    {
        $addon = new Addon('Bufala', 200, []);
        $sub = new Section('classiche', 'Classiche', null, [$this->item(addons: [$addon]), $this->item(addons: [$addon])]);
        $mixed = new Section('bianche', 'Bianche', null, [$this->item(addons: [$addon]), $this->item()]);
        $macro = new Section('pizze', 'Pizze', null, [], [$sub, $mixed]);

        $this->assertSame(['classiche', 'bianche'], array_column($macro->leaves(), 'slug'));
        $this->assertSame([$addon], $sub->sharedAddons());
        $this->assertSame([], $mixed->sharedAddons());
        $this->assertTrue($sub->allergensUnverified());
        $this->assertFalse((new Section('x', 'X', null, [$this->item(allergens: [])]))->allergensUnverified());
    }

    public function test_highlight_tags_come_in_order_of_importance(): void
    {
        $item = new Item('Prova', null, null, 700, [], ['vegetariano' => 'Vegetariana', 'novita' => 'Novità', 'pizza-del-mese' => 'Pizza del mese'], [], null);

        $this->assertSame(['pizza-del-mese' => 'Pizza del mese', 'novita' => 'Novità'], $item->highlights());
        $this->assertSame([], $this->item()->highlights());
    }

    public function test_prices_are_formatted_per_locale_and_missing_prices_stay_missing(): void
    {
        $this->assertSame('€ 8,50', Price::format(850));
        $this->assertNull(Price::format(null));

        app()->setLocale('en');
        $this->assertSame('€ 8.50', Price::format(850));
    }

    public function test_the_source_comes_from_config(): void
    {
        $this->assertInstanceOf(StaticMenu::class, app(MenuSource::class));

        config(['menu.source' => 'database']);

        $this->assertInstanceOf(DatabaseMenu::class, app(MenuSource::class));
    }
}
