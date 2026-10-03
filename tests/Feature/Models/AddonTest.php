<?php

namespace Tests\Feature\Models;

use App\Models\Addon;
use App\Models\Category;
use App\Models\MenuItem;
use Tests\TestCase;

class AddonTest extends TestCase
{
    /**
     * @return list<int>
     */
    private function effectiveIds(MenuItem $item, bool $visibleOnly = true): array
    {
        return $item->effectiveAddons($visibleOnly)->pluck('id')->all();
    }

    public function test_price_supplement_is_in_cents_and_can_be_zero(): void
    {
        $this->assertSame(150, Addon::factory()->create(['price_cents' => 150])->fresh()->price_cents);
        $this->assertSame(0, Addon::factory()->free()->create()->fresh()->price_cents);
    }

    public function test_name_is_translatable(): void
    {
        $addon = Addon::factory()->create(['name' => ['it' => 'Cornicione ripieno', 'en' => 'Stuffed crust']]);

        $this->assertSame('Stuffed crust', $addon->translate('name', 'en'));
    }

    public function test_an_addon_assigned_to_a_category_applies_to_its_items(): void
    {
        $category = Category::factory()->create();
        $addon = Addon::factory()->create();
        $addon->categories()->attach($category);

        $this->assertSame([$addon->id], $this->effectiveIds(MenuItem::factory()->for($category)->create()));
        $this->assertSame([], $this->effectiveIds(MenuItem::factory()->create()));
    }

    public function test_an_addon_assigned_to_a_macro_applies_to_items_of_its_subcategories(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();
        $addon = Addon::factory()->create();
        $addon->categories()->attach($macro);

        $this->assertSame([$addon->id], $this->effectiveIds(MenuItem::factory()->for($sub)->create()));
        $this->assertSame([$addon->id], $this->effectiveIds(MenuItem::factory()->for($macro)->create()));
    }

    public function test_an_addon_assigned_to_a_subcategory_does_not_apply_to_the_macro_items(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();
        Addon::factory()->create()->categories()->attach($sub);

        $this->assertSame([], $this->effectiveIds(MenuItem::factory()->for($macro)->create()));
    }

    public function test_an_addon_can_be_linked_directly_to_an_item(): void
    {
        $item = MenuItem::factory()->create();
        $addon = Addon::factory()->create();
        $item->extraAddons()->attach($addon);

        $this->assertSame([$addon->id], $this->effectiveIds($item));
        $this->assertSame([$addon->id], $item->extraAddons->pluck('id')->all());
        $this->assertSame([], $item->excludedAddons->pluck('id')->all());
    }

    public function test_an_inherited_addon_can_be_excluded_from_a_specific_item(): void
    {
        $category = Category::factory()->create();
        $addon = Addon::factory()->create();
        $addon->categories()->attach($category);
        $pizza = MenuItem::factory()->for($category)->create();
        $calzone = MenuItem::factory()->for($category)->create();
        $calzone->excludedAddons()->attach($addon);

        $this->assertSame([$addon->id], $this->effectiveIds($pizza));
        $this->assertSame([], $this->effectiveIds($calzone));
        $this->assertSame([$addon->id], $calzone->excludedAddons->pluck('id')->all());
    }

    public function test_excluding_an_addon_that_is_not_inherited_adds_nothing(): void
    {
        $item = MenuItem::factory()->create();
        $item->excludedAddons()->attach(Addon::factory()->create());

        $this->assertSame([], $this->effectiveIds($item));
    }

    public function test_effective_addons_have_no_duplicates(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();
        $item = MenuItem::factory()->for($sub)->create();
        $addon = Addon::factory()->create();
        $addon->categories()->attach([$macro->id, $sub->id]);
        $item->extraAddons()->attach($addon);

        $this->assertSame([$addon->id], $this->effectiveIds($item));
    }

    public function test_effective_addons_are_ordered_and_hidden_ones_are_filtered_by_default(): void
    {
        $category = Category::factory()->create();
        $second = Addon::factory()->create(['sort_order' => 2]);
        $first = Addon::factory()->create(['sort_order' => 1]);
        $hidden = Addon::factory()->hidden()->create(['sort_order' => 3]);
        foreach ([$second, $first, $hidden] as $addon) {
            $addon->categories()->attach($category);
        }
        $item = MenuItem::factory()->for($category)->create();

        $this->assertSame([$first->id, $second->id], $this->effectiveIds($item));
        $this->assertSame([$first->id, $second->id, $hidden->id], $this->effectiveIds($item, visibleOnly: false));
    }

    public function test_deleting_an_addon_removes_its_links(): void
    {
        $category = Category::factory()->create();
        $item = MenuItem::factory()->for($category)->create();
        $addon = Addon::factory()->create();
        $addon->categories()->attach($category);
        $item->extraAddons()->attach($addon);

        $addon->delete();

        $this->assertSame(0, $this->app['db']->table('addon_category')->count());
        $this->assertSame(0, $this->app['db']->table('addon_menu_item')->count());
    }
}
