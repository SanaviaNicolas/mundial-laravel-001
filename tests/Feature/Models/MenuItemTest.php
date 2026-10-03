<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class MenuItemTest extends TestCase
{
    public function test_an_item_belongs_to_a_category_and_has_no_page_slug(): void
    {
        $item = MenuItem::factory()->create();

        $this->assertInstanceOf(Category::class, $item->category);
        $this->assertArrayNotHasKey('slug', $item->getAttributes());
    }

    public function test_an_item_can_live_in_a_macro_category_or_a_subcategory(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();

        $this->assertTrue(MenuItem::factory()->for($macro)->create()->category->is($macro));
        $this->assertTrue(MenuItem::factory()->for($sub)->create()->category->is($sub));
    }

    public function test_price_is_stored_in_cents_and_can_be_missing(): void
    {
        $this->assertSame(850, MenuItem::factory()->create(['price_cents' => 850])->fresh()->price_cents);
        $this->assertNull(MenuItem::factory()->withoutPrice()->create()->fresh()->price_cents);
    }

    public function test_text_fields_are_translatable(): void
    {
        $item = MenuItem::factory()->create([
            'name' => ['it' => 'Pizza', 'en' => 'Pizza'],
            'description' => ['it' => 'Buona', 'en' => 'Good'],
            'notes' => ['it' => 'Base fritta', 'en' => 'Fried base'],
        ]);

        $this->assertSame('Good', $item->translate('description', 'en'));
        $this->assertSame('Fried base', $item->translate('notes', 'en'));
    }

    public function test_ordered_scope_sorts_by_sort_order_then_id(): void
    {
        $b = MenuItem::factory()->create(['sort_order' => 2]);
        $a = MenuItem::factory()->create(['sort_order' => 1]);

        $this->assertSame([$a->id, $b->id], MenuItem::ordered()->pluck('id')->all());
    }

    public function test_category_items_are_ordered(): void
    {
        $category = Category::factory()->create();
        $second = MenuItem::factory()->for($category)->create(['sort_order' => 2]);
        $first = MenuItem::factory()->for($category)->create(['sort_order' => 1]);

        $this->assertSame([$first->id, $second->id], $category->items->pluck('id')->all());
    }

    public function test_an_item_is_visible_only_if_it_and_its_categories_are_visible(): void
    {
        $visible = MenuItem::factory()->create();
        MenuItem::factory()->hidden()->create();
        MenuItem::factory()->for(Category::factory()->hidden())->create();
        $hiddenMacro = Category::factory()->hidden()->create();
        MenuItem::factory()->for(Category::factory()->for($hiddenMacro, 'parent'))->create();

        $this->assertSame([$visible->id], MenuItem::visible()->pluck('id')->all());
    }

    public function test_ingredient_lines_are_ordered_and_keep_the_after_cooking_flag_and_section(): void
    {
        $item = MenuItem::factory()->create();
        $basil = Ingredient::factory()->create();
        $cheese = Ingredient::factory()->create();
        MenuItemIngredient::factory()->for($item)->for($basil)->create(['sort_order' => 2, 'after_cooking' => true]);
        MenuItemIngredient::factory()->for($item)->for($cheese)->create([
            'sort_order' => 1,
            'section' => ['it' => 'Mezza pizza', 'en' => 'Half pizza'],
        ]);

        $lines = $item->ingredientLines;

        $this->assertSame([$cheese->id, $basil->id], $lines->pluck('ingredient_id')->all());
        $this->assertFalse($lines[0]->after_cooking);
        $this->assertTrue($lines[1]->after_cooking);
        $this->assertSame('Half pizza', $lines[0]->translate('section', 'en'));
        $this->assertNull($lines[1]->translate('section'));
        $this->assertEqualsCanonicalizing([$basil->id, $cheese->id], $item->ingredients->pluck('id')->all());
    }

    public function test_frozen_flag_and_translatable_name_on_ingredients(): void
    {
        $ingredient = Ingredient::factory()->frozen()->create(['name' => ['it' => 'Gamberetti', 'en' => 'Shrimp']]);

        $this->assertTrue($ingredient->fresh()->is_frozen);
        $this->assertFalse(Ingredient::factory()->create()->is_frozen);
        $this->assertSame('Shrimp', $ingredient->translate('name', 'en'));
    }

    public function test_tags_have_a_unique_slug_and_can_be_attached_to_items(): void
    {
        $item = MenuItem::factory()->create();
        $tag = Tag::factory()->create(['slug' => 'vegano', 'name' => ['it' => 'Vegano', 'en' => 'Vegan']]);
        $item->tags()->attach($tag);

        $this->assertTrue($item->tags->first()->is($tag));
        $this->assertTrue($tag->items->first()->is($item));
        $this->assertSame('Vegan', $tag->translate('name', 'en'));

        $this->expectException(QueryException::class);
        Tag::factory()->create(['slug' => 'vegano']);
    }

    public function test_deleting_an_item_removes_its_ingredient_lines_and_tag_links(): void
    {
        $item = MenuItem::factory()->create();
        MenuItemIngredient::factory()->for($item)->create();
        $item->tags()->attach(Tag::factory()->create());

        $item->delete();

        $this->assertSame(0, MenuItemIngredient::count());
        $this->assertSame(0, $this->app['db']->table('menu_item_tag')->count());
    }

    public function test_an_ingredient_in_use_cannot_be_deleted(): void
    {
        $line = MenuItemIngredient::factory()->create();

        $this->expectException(QueryException::class);

        $line->ingredient->delete();
    }

    public function test_a_category_with_items_cannot_be_deleted(): void
    {
        $item = MenuItem::factory()->create();

        $this->expectException(QueryException::class);

        $item->category->delete();
    }
}
