<?php

namespace Tests\Feature\Menu;

use App\Menu\StaticMenu;
use Tests\TestCase;

class StaticMenuTest extends TestCase
{
    public function test_it_builds_the_same_contract_from_the_static_config(): void
    {
        config(['menu.tags' => ['novita' => ['it' => 'Novità', 'en' => 'New']]]);
        config(['menu.sections' => [
            'pizze' => [
                'name' => ['it' => 'Pizze', 'en' => 'Pizzas'],
                'children' => [
                    'classiche' => [
                        'name' => 'Classiche',
                        'description' => ['it' => 'Di sempre', 'en' => 'Timeless'],
                        'addons' => [['name' => ['it' => 'Bufala', 'en' => 'Buffalo mozzarella'], 'price' => 200]],
                        'items' => [[
                            'name' => 'Funghi',
                            'notes' => ['it' => 'Nota', 'en' => 'Note'],
                            'price' => 750,
                            'ingredients' => ['Pomodoro', ['name' => ['it' => 'Funghi', 'en' => 'Mushrooms'], 'frozen' => true], ['name' => 'Basilico', 'after' => true]],
                            'tags' => ['novita'],
                        ]],
                    ],
                    'vuota' => ['name' => 'Vuota', 'items' => []],
                ],
            ],
            'baguette' => ['name' => 'Baguette', 'items' => [['name' => 'Baguette prova', 'price' => null, 'ingredients' => []]]],
        ]]);

        app()->setLocale('en');
        $sections = (new StaticMenu)->sections();

        $this->assertSame(['pizze', 'baguette'], array_column($sections, 'slug'));
        $this->assertSame('Pizzas', $sections[0]->name);
        $this->assertSame(['classiche'], array_column($sections[0]->children, 'slug'), 'empty sections are left out');

        $item = $sections[0]->children[0]->items[0];
        $this->assertSame('Timeless', $sections[0]->children[0]->description);
        $this->assertSame(750, $item->price);
        $this->assertSame('Note', $item->notes);
        $this->assertSame(['novita' => 'New'], $item->tags);
        $this->assertSame(['Pomodoro', 'Mushrooms', 'Basilico'], array_column($item->ingredients, 'name'));
        $this->assertTrue($item->ingredients[1]->frozen);
        $this->assertTrue($item->ingredients[2]->afterCooking);
        $this->assertSame(['Buffalo mozzarella'], array_column($item->addons, 'name'), 'section addons apply to every item');
        $this->assertNull($item->allergens, 'static data is never verified');
        $this->assertNull($sections[1]->items[0]->price);
    }
}
