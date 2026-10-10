<?php

namespace Tests\Feature\Menu;

use App\Menu\DatabaseMenu;
use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use App\Models\Tag;
use Tests\TestCase;

class DatabaseMenuTest extends TestCase
{
    public function test_sections_mirror_the_visible_category_tree_in_order(): void
    {
        $pizze = Category::factory()->create(['name' => ['it' => 'Pizze', 'en' => 'Pizzas'], 'slug' => 'pizze', 'sort_order' => 1]);
        $classiche = Category::factory()->for($pizze, 'parent')->create(['name' => ['it' => 'Classiche'], 'slug' => 'classiche', 'sort_order' => 2, 'description' => ['it' => 'Di sempre']]);
        $bianche = Category::factory()->for($pizze, 'parent')->create(['slug' => 'bianche', 'sort_order' => 1]);
        $baguette = Category::factory()->create(['slug' => 'baguette', 'sort_order' => 2]);
        MenuItem::factory()->for($classiche)->create(['name' => ['it' => 'Diavola'], 'sort_order' => 2]);
        MenuItem::factory()->for($classiche)->create(['name' => ['it' => 'Margherita'], 'sort_order' => 1]);
        MenuItem::factory()->for($bianche)->create();
        MenuItem::factory()->for($baguette)->create();

        app()->setLocale('en');
        $sections = (new DatabaseMenu)->sections();

        $this->assertSame(['pizze', 'baguette'], array_column($sections, 'slug'));
        $this->assertSame('Pizzas', $sections[0]->name);
        $this->assertSame(['bianche', 'classiche'], array_column($sections[0]->children, 'slug'));
        $this->assertSame('Classiche', $sections[0]->children[1]->name, 'falls back to Italian');
        $this->assertSame('Di sempre', $sections[0]->children[1]->description);
        $this->assertSame(['Margherita', 'Diavola'], array_column($sections[0]->children[1]->items, 'name'));
    }

    public function test_hidden_items_hidden_categories_and_empty_categories_are_left_out(): void
    {
        $hiddenMacro = Category::factory()->hidden()->create();
        MenuItem::factory()->for(Category::factory()->for($hiddenMacro, 'parent'))->create();
        $empty = Category::factory()->create(['slug' => 'vuota']);
        Category::factory()->for($empty, 'parent')->create();
        MenuItem::factory()->hidden()->for(Category::factory()->create(['slug' => 'nascoste']))->create();
        MenuItem::factory()->for(Category::factory()->create(['slug' => 'piena']))->create();

        $this->assertSame(['piena'], array_column((new DatabaseMenu)->sections(), 'slug'));
    }

    public function test_items_carry_price_texts_ingredients_and_tags(): void
    {
        $item = MenuItem::factory()->create([
            'price_cents' => 850,
            'description' => ['it' => 'Descrizione'],
            'notes' => ['it' => 'Base fritta'],
        ]);
        $line = fn (string $name, array $attributes = [], bool $frozen = false) => MenuItemIngredient::factory()->for($item)
            ->for(Ingredient::factory()->create(['name' => ['it' => $name], 'is_frozen' => $frozen]))
            ->create($attributes);
        $line('Basilico', ['sort_order' => 3, 'after_cooking' => true]);
        $line('Pomodoro', ['sort_order' => 1]);
        $line('Funghi', ['sort_order' => 2, 'section' => ['it' => 'Mezza pizza']], frozen: true);
        $item->tags()->attach(Tag::factory()->create(['slug' => 'novita', 'name' => ['it' => 'Novità']]));

        $voce = (new DatabaseMenu)->sections()[0]->items[0];

        $this->assertSame(850, $voce->price);
        $this->assertSame('Descrizione', $voce->description);
        $this->assertSame('Base fritta', $voce->notes);
        $this->assertSame(['novita' => 'Novità'], $voce->tags);
        $this->assertSame(['Pomodoro', 'Funghi', 'Basilico'], array_column($voce->ingredients, 'name'));
        $this->assertTrue($voce->ingredients[1]->frozen);
        $this->assertSame('Mezza pizza', $voce->ingredients[1]->section);
        $this->assertTrue($voce->ingredients[2]->afterCooking);
    }

    public function test_items_carry_their_effective_addons_with_the_addon_allergens(): void
    {
        $category = Category::factory()->create();
        $item = MenuItem::factory()->for($category)->create();
        $milk = Allergen::factory()->create(['name' => ['it' => 'Latte']]);
        $cheese = Addon::factory()->create(['name' => ['it' => 'Formaggio'], 'price_cents' => 150, 'sort_order' => 1]);
        $cheese->allergens()->attach($milk);
        $cheese->categories()->attach($category);
        Addon::factory()->free()->create(['name' => ['it' => 'Senza lattosio'], 'sort_order' => 2])->categories()->attach($category);
        Addon::factory()->create(['is_visible' => false])->categories()->attach($category);

        $addons = (new DatabaseMenu)->sections()[0]->items[0]->addons;

        $this->assertSame(['Formaggio', 'Senza lattosio'], array_column($addons, 'name'));
        $this->assertSame([150, 0], array_column($addons, 'price'));
        $this->assertSame(['Latte'], $addons[0]->allergens);
    }

    public function test_allergens_are_null_until_verified_then_listed_even_when_empty(): void
    {
        $category = Category::factory()->create();
        $unverified = MenuItem::factory()->for($category)->create(['sort_order' => 1]);
        $verified = MenuItem::factory()->for($category)->create(['sort_order' => 2]);
        $none = MenuItem::factory()->for($category)->create(['sort_order' => 3]);
        $gluten = Allergen::factory()->create(['name' => ['it' => 'Glutine']]);
        $unverified->allergens()->attach($gluten);
        $verified->allergens()->attach($gluten);
        $verified->verifyAllergens();
        $none->verifyAllergens();

        $items = (new DatabaseMenu)->sections()[0]->items;

        $this->assertNull($items[0]->allergens);
        $this->assertSame(['Glutine'], $items[1]->allergens);
        $this->assertSame([], $items[2]->allergens);
    }
}
