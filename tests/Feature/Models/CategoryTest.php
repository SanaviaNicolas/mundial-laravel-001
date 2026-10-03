<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use DomainException;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function test_factory_creates_a_macro_category_and_a_subcategory(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();

        $this->assertNull($macro->parent_id);
        $this->assertTrue($sub->parent->is($macro));
        $this->assertTrue($macro->children->first()->is($sub));
    }

    public function test_depth_is_limited_to_two_levels(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();

        $this->expectException(DomainException::class);

        Category::factory()->for($sub, 'parent')->create();
    }

    public function test_a_macro_with_subcategories_cannot_become_a_subcategory(): void
    {
        $macro = Category::factory()->create();
        Category::factory()->for($macro, 'parent')->create();
        $other = Category::factory()->create();

        $this->expectException(DomainException::class);

        $macro->update(['parent_id' => $other->id]);
    }

    public function test_a_category_cannot_be_its_own_parent(): void
    {
        $macro = Category::factory()->create();

        $this->expectException(DomainException::class);

        $macro->update(['parent_id' => $macro->id]);
    }

    public function test_ordered_scope_sorts_by_sort_order_then_id(): void
    {
        $b = Category::factory()->create(['sort_order' => 2]);
        $a = Category::factory()->create(['sort_order' => 1]);
        $c = Category::factory()->create(['sort_order' => 2]);

        $this->assertSame([$a->id, $b->id, $c->id], Category::ordered()->pluck('id')->all());
    }

    public function test_children_are_ordered(): void
    {
        $macro = Category::factory()->create();
        $second = Category::factory()->for($macro, 'parent')->create(['sort_order' => 2]);
        $first = Category::factory()->for($macro, 'parent')->create(['sort_order' => 1]);

        $this->assertSame([$first->id, $second->id], $macro->children->pluck('id')->all());
    }

    public function test_hiding_a_macro_hides_its_subcategories(): void
    {
        $visibleMacro = Category::factory()->create();
        $visibleSub = Category::factory()->for($visibleMacro, 'parent')->create();
        Category::factory()->for($visibleMacro, 'parent')->hidden()->create();
        $hiddenMacro = Category::factory()->hidden()->create();
        Category::factory()->for($hiddenMacro, 'parent')->create();

        $this->assertEqualsCanonicalizing(
            [$visibleMacro->id, $visibleSub->id],
            Category::visible()->pluck('id')->all(),
        );
    }

    public function test_slug_is_unique(): void
    {
        Category::factory()->create(['slug' => 'pizze']);

        $this->expectException(QueryException::class);

        Category::factory()->create(['slug' => 'pizze']);
    }

    public function test_a_category_with_subcategories_cannot_be_deleted(): void
    {
        $macro = Category::factory()->create();
        Category::factory()->for($macro, 'parent')->create();

        $this->expectException(QueryException::class);

        $macro->delete();
    }

    public function test_name_and_description_are_translatable(): void
    {
        $category = Category::factory()->create([
            'name' => ['it' => 'Bibite', 'en' => 'Drinks'],
            'description' => ['it' => 'Fredde', 'en' => 'Cold'],
        ]);

        $this->assertSame('Drinks', $category->translate('name', 'en'));
        $this->assertSame('Cold', $category->translate('description', 'en'));
    }

    public function test_path_label_shows_the_macro_and_the_subcategory(): void
    {
        $macro = Category::factory()->create(['name' => ['it' => 'Pizze', 'en' => 'Pizzas']]);
        $sub = Category::factory()->for($macro, 'parent')->create(['name' => ['it' => 'Classiche', 'en' => 'Classic']]);

        $this->assertSame('Pizze', $macro->pathLabel());
        $this->assertSame('Pizze › Classiche', $sub->pathLabel());
        $this->assertSame('Pizzas › Classic', $sub->pathLabel('en'));
    }

    public function test_path_options_list_every_category_in_tree_order(): void
    {
        $drinks = Category::factory()->create(['name' => ['it' => 'Bibite'], 'sort_order' => 2]);
        $pizzas = Category::factory()->create(['name' => ['it' => 'Pizze'], 'sort_order' => 1]);
        $classic = Category::factory()->for($pizzas, 'parent')->create(['name' => ['it' => 'Classiche'], 'sort_order' => 1]);
        $soft = Category::factory()->for($drinks, 'parent')->create(['name' => ['it' => 'Analcolici'], 'sort_order' => 1]);

        $this->assertSame([
            $pizzas->id => 'Pizze',
            $classic->id => 'Pizze › Classiche',
            $drinks->id => 'Bibite',
            $soft->id => 'Bibite › Analcolici',
        ], Category::pathOptions());
    }
}
