<?php

namespace Tests\Feature\Models;

use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AllergenLogicTest extends TestCase
{
    private Allergen $gluten;

    private Allergen $milk;

    private Allergen $eggs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gluten = Allergen::factory()->create(['key' => 'gluten', 'sort_order' => 1]);
        $this->milk = Allergen::factory()->create(['key' => 'milk', 'sort_order' => 2]);
        $this->eggs = Allergen::factory()->create(['key' => 'eggs', 'sort_order' => 3]);
    }

    private function itemWithIngredient(Allergen ...$allergens): MenuItem
    {
        $item = MenuItem::factory()->create();
        $ingredient = Ingredient::factory()->create();
        $ingredient->allergens()->attach(collect($allergens)->pluck('id'));
        MenuItemIngredient::factory()->for($item)->for($ingredient)->create();

        return $item;
    }

    /**
     * @return list<string>
     */
    private function keys(iterable $allergens): array
    {
        return collect($allergens)->pluck('key')->all();
    }

    public function test_effective_allergens_are_the_union_of_ingredient_and_direct_allergens(): void
    {
        $item = $this->itemWithIngredient($this->milk, $this->gluten);
        $item->allergens()->attach([$this->gluten->id, $this->eggs->id]);

        $this->assertSame(['gluten', 'milk', 'eggs'], $this->keys($item->effectiveAllergens()));
    }

    public function test_effective_allergens_cover_ingredients_only_or_direct_only_or_nothing(): void
    {
        $this->assertSame(['milk'], $this->keys($this->itemWithIngredient($this->milk)->effectiveAllergens()));

        $drink = MenuItem::factory()->create();
        $drink->allergens()->attach($this->eggs);
        $this->assertSame(['eggs'], $this->keys($drink->effectiveAllergens()));

        $this->assertCount(0, MenuItem::factory()->create()->effectiveAllergens());
    }

    public function test_an_unverified_item_never_exposes_its_allergens(): void
    {
        $item = $this->itemWithIngredient($this->milk);

        $this->assertFalse($item->isAllergensVerified());
        $this->assertNull($item->publicAllergens());
        $this->assertNull(MenuItem::factory()->create()->publicAllergens());
    }

    public function test_a_verified_item_without_allergens_means_no_allergens(): void
    {
        $item = MenuItem::factory()->create();
        $item->verifyAllergens();

        $this->assertTrue($item->isAllergensVerified());
        $this->assertNotNull($item->publicAllergens());
        $this->assertCount(0, $item->publicAllergens());
    }

    public function test_a_verified_item_exposes_its_effective_allergens(): void
    {
        $item = $this->itemWithIngredient($this->milk);
        $item->verifyAllergens();

        $this->assertSame(['milk'], $this->keys($item->publicAllergens()));
    }

    public function test_verifying_sets_the_date_to_now_and_can_be_undone(): void
    {
        $this->travelTo(now()->startOfSecond());
        $item = MenuItem::factory()->create();

        $item->verifyAllergens();
        $this->assertTrue($item->fresh()->allergens_verified_at->equalTo(now()));

        $item->unverifyAllergens();
        $this->assertNull($item->fresh()->allergens_verified_at);
    }

    public function test_changing_ingredient_lines_resets_the_verification(): void
    {
        $item = $this->itemWithIngredient($this->milk);

        $item->verifyAllergens();
        $line = MenuItemIngredient::factory()->for($item)->create();
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'line added');

        $item->verifyAllergens();
        $line->update(['ingredient_id' => Ingredient::factory()->create()->id]);
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'ingredient swapped');

        $item->verifyAllergens();
        $line->delete();
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'line removed');
    }

    public function test_reordering_or_flagging_lines_keeps_the_verification(): void
    {
        $item = $this->itemWithIngredient($this->milk);
        $item->verifyAllergens();

        $item->ingredientLines->first()->update(['sort_order' => 5, 'after_cooking' => true]);

        $this->assertTrue($item->fresh()->isAllergensVerified());
    }

    public function test_changing_direct_allergens_resets_the_verification(): void
    {
        $item = MenuItem::factory()->create();

        $item->verifyAllergens();
        $item->allergens()->attach($this->gluten);
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'attached');

        $item->verifyAllergens();
        $item->allergens()->sync([$this->gluten->id]);
        $this->assertTrue($item->fresh()->isAllergensVerified(), 'unchanged sync');

        $item->allergens()->detach($this->gluten);
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'detached');
    }

    public function test_changing_an_ingredient_allergens_resets_all_items_using_it_with_one_query(): void
    {
        $ingredient = Ingredient::factory()->create();
        $users = MenuItem::factory()->count(3)->create();
        foreach ($users as $item) {
            MenuItemIngredient::factory()->for($item)->for($ingredient)->create();
        }
        $unrelated = MenuItem::factory()->create();
        foreach ([...$users, $unrelated] as $item) {
            $item->verifyAllergens();
        }

        DB::enableQueryLog();
        $ingredient->allergens()->attach($this->eggs);
        $updates = collect(DB::getQueryLog())->filter(fn ($q) => str_starts_with($q['query'], 'update "menu_items"'));
        DB::disableQueryLog();

        $this->assertCount(1, $updates);
        $users->each(fn (MenuItem $item) => $this->assertFalse($item->fresh()->isAllergensVerified()));
        $this->assertTrue($unrelated->fresh()->isAllergensVerified());

        foreach ($users as $item) {
            $item->verifyAllergens();
        }
        $ingredient->allergens()->detach($this->eggs);
        $users->each(fn (MenuItem $item) => $this->assertFalse($item->fresh()->isAllergensVerified()));
    }

    public function test_addon_allergens_are_separate_from_the_effective_allergens(): void
    {
        $category = Category::factory()->create();
        $item = MenuItem::factory()->for($category)->create();
        $withAllergen = Addon::factory()->create();
        $withAllergen->allergens()->attach([$this->milk->id, $this->gluten->id]);
        $plain = Addon::factory()->create();
        $hidden = Addon::factory()->hidden()->create();
        $hidden->allergens()->attach($this->eggs);
        foreach ([$withAllergen, $plain, $hidden] as $addon) {
            $addon->categories()->attach($category);
        }
        $item->verifyAllergens();

        $addonAllergens = $item->addonAllergens();

        $this->assertSame([$withAllergen->id], $addonAllergens->keys()->all());
        $this->assertSame(['gluten', 'milk'], $this->keys($addonAllergens[$withAllergen->id]));
        $this->assertCount(0, $item->publicAllergens());
        $this->assertCount(0, $item->effectiveAllergens());
    }

    public function test_deleting_an_ingredient_or_item_cleans_the_allergen_links(): void
    {
        $item = $this->itemWithIngredient($this->milk);
        $item->allergens()->attach($this->gluten);

        $item->ingredientLines()->delete();
        Ingredient::query()->delete();
        $item->delete();

        $this->assertSame(0, DB::table('allergen_ingredient')->count());
        $this->assertSame(0, DB::table('allergen_menu_item')->count());
    }
}
