<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Tests\TestCase;

class MenuItemResourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    private function edit(MenuItem $item)
    {
        return Livewire::test(EditMenuItem::class, ['record' => $item->getRouteKey()]);
    }

    public function test_list_with_translated_search_and_sort(): void
    {
        $margherita = MenuItem::factory()->create(['name' => ['it' => 'Margherita', 'en' => 'Margherita']]);
        $marinara = MenuItem::factory()->create(['name' => ['it' => 'Marinara']]);

        Livewire::test(ListMenuItems::class)
            ->assertCanSeeTableRecords([$margherita, $marinara])
            ->searchTable('marg')
            ->assertCanSeeTableRecords([$margherita])
            ->assertCanNotSeeTableRecords([$marinara])
            ->searchTable('')
            ->sortTable('name', 'desc')
            ->assertCanSeeTableRecords([$marinara, $margherita], inOrder: true);
    }

    public function test_list_filters_by_category_including_subcategories_tag_visibility_and_verification(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();
        $inMacro = MenuItem::factory()->for($macro)->create();
        $inSub = MenuItem::factory()->for($sub)->create();
        $elsewhere = MenuItem::factory()->hidden()->create();
        $vegan = Tag::factory()->create();
        $inSub->tags()->attach($vegan);
        $elsewhere->verifyAllergens();

        $table = Livewire::test(ListMenuItems::class);

        $table->filterTable('category_id', $macro->id)
            ->assertCanSeeTableRecords([$inMacro, $inSub])
            ->assertCanNotSeeTableRecords([$elsewhere])
            ->removeTableFilters()
            ->filterTable('category_id', $sub->id)
            ->assertCanSeeTableRecords([$inSub])
            ->assertCanNotSeeTableRecords([$inMacro, $elsewhere])
            ->removeTableFilters()
            ->filterTable('tag', $vegan->id)
            ->assertCanSeeTableRecords([$inSub])
            ->assertCanNotSeeTableRecords([$inMacro, $elsewhere])
            ->removeTableFilters()
            ->filterTable('is_visible', false)
            ->assertCanSeeTableRecords([$elsewhere])
            ->assertCanNotSeeTableRecords([$inMacro, $inSub])
            ->removeTableFilters()
            ->filterTable('allergens_verified_at', false)
            ->assertCanSeeTableRecords([$inMacro, $inSub])
            ->assertCanNotSeeTableRecords([$elsewhere]);
    }

    public function test_create_a_complete_item(): void
    {
        $category = Category::factory()->create();
        $mozzarella = Ingredient::factory()->create();
        $basil = Ingredient::factory()->create();
        $gluten = Allergen::factory()->create();
        $vegetarian = Tag::factory()->create();

        Livewire::test(CreateMenuItem::class)
            ->fillForm([
                'category_id' => $category->id,
                'name' => ['it' => 'Margherita', 'en' => 'Margherita'],
                'description' => ['it' => 'Classica', 'en' => 'Classic'],
                'notes' => ['it' => 'Base fritta', 'en' => 'Fried base'],
                'price_cents' => '8.50',
                'is_visible' => true,
                'ingredientLines' => [
                    ['ingredient_id' => $mozzarella->id, 'after_cooking' => false, 'section' => ['it' => 'Mezza pizza', 'en' => 'Half pizza']],
                    ['ingredient_id' => $basil->id, 'after_cooking' => true, 'section' => ['it' => '', 'en' => '']],
                ],
                'allergens' => [$gluten->id],
                'tags' => [$vegetarian->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $item = MenuItem::first();
        $this->assertSame(850, $item->price_cents);
        $this->assertSame('Fried base', $item->translate('notes', 'en'));
        $this->assertTrue($item->category->is($category));
        $this->assertSame([$gluten->id], $item->allergens->pluck('id')->all());
        $this->assertSame([$vegetarian->id], $item->tags->pluck('id')->all());
        $lines = $item->ingredientLines;
        $this->assertSame([$mozzarella->id, $basil->id], $lines->pluck('ingredient_id')->all());
        $this->assertSame([false, true], $lines->pluck('after_cooking')->all());
        $this->assertSame('Half pizza', $lines[0]->translate('section', 'en'));
        $this->assertFalse($item->isAllergensVerified());
    }

    public function test_price_is_optional_but_name_and_category_are_required(): void
    {
        $category = Category::factory()->create();

        Livewire::test(CreateMenuItem::class)
            ->fillForm(['category_id' => $category->id, 'name' => ['it' => 'Pizza'], 'price_cents' => null])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertNull(MenuItem::first()->price_cents);

        Livewire::test(CreateMenuItem::class)
            ->fillForm(['category_id' => null, 'name' => ['it' => '']])
            ->call('create')
            ->assertHasFormErrors(['category_id' => 'required', 'name.it' => 'required']);
    }

    public function test_edit_loads_ingredients_in_order_and_saves_reordering(): void
    {
        $item = MenuItem::factory()->create();
        $first = MenuItemIngredient::factory()->for($item)->create(['sort_order' => 1]);
        $second = MenuItemIngredient::factory()->for($item)->create(['sort_order' => 2]);

        $component = $this->edit($item);
        $lines = $component->get('data.ingredientLines');
        $this->assertEquals([$first->ingredient_id, $second->ingredient_id], array_column($lines, 'ingredient_id'));

        $component->set('data.ingredientLines', array_reverse($lines))->call('save')->assertHasNoFormErrors();

        $this->assertSame([$second->ingredient_id, $first->ingredient_id], $item->fresh()->ingredientLines->pluck('ingredient_id')->all());
    }

    public function test_saving_an_unchanged_form_keeps_the_allergen_verification(): void
    {
        $item = MenuItem::factory()->create();
        MenuItemIngredient::factory()->for($item)->create(['sort_order' => 1]);
        MenuItemIngredient::factory()->for($item)->create(['sort_order' => 2]);
        $item->allergens()->attach(Allergen::factory()->create());
        $item->verifyAllergens();

        $this->edit($item)->call('save')->assertHasNoFormErrors();

        $this->assertTrue($item->fresh()->isAllergensVerified());
    }

    public function test_changing_ingredients_or_direct_allergens_from_the_form_resets_the_verification(): void
    {
        $item = MenuItem::factory()->create();
        MenuItemIngredient::factory()->for($item)->create(['sort_order' => 1]);
        $item->verifyAllergens();

        $this->edit($item)
            ->fillForm(['allergens' => [Allergen::factory()->create()->id]])
            ->call('save');
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'direct allergens');

        $item->verifyAllergens();
        $component = $this->edit($item);
        $lines = $component->get('data.ingredientLines');
        $lines[array_key_first($lines)]['ingredient_id'] = Ingredient::factory()->create()->id;
        $component->set('data.ingredientLines', $lines)->call('save');
        $this->assertFalse($item->fresh()->isAllergensVerified(), 'ingredient swapped');
    }

    public function test_verify_and_unverify_allergens_from_the_edit_page_and_the_table(): void
    {
        $this->travelTo(now()->startOfSecond());
        $item = MenuItem::factory()->create();

        $this->edit($item)->callAction('verifyAllergens')->assertHasNoActionErrors();
        $this->assertTrue($item->fresh()->allergens_verified_at->equalTo(now()));

        $this->edit($item)->assertActionHidden('verifyAllergens')->callAction('unverifyAllergens');
        $this->assertFalse($item->fresh()->isAllergensVerified());

        Livewire::test(ListMenuItems::class)
            ->callAction(TestAction::make('verifyAllergens')->table($item));
        $this->assertTrue($item->fresh()->isAllergensVerified());
    }

    public function test_the_edit_page_shows_the_effective_allergens_only_as_a_preview_of_their_status(): void
    {
        $milk = Allergen::factory()->create(['name' => ['it' => 'Latte', 'en' => 'Milk']]);
        $ingredient = Ingredient::factory()->create();
        $ingredient->allergens()->attach($milk);
        $item = MenuItem::factory()->create();
        MenuItemIngredient::factory()->for($item)->for($ingredient)->create();

        $this->edit($item)->assertSeeText('Latte')->assertSeeText('Non verificati');

        $item->verifyAllergens();

        $this->edit($item)->assertSeeText('Latte')->assertSeeText('Verificati');
    }

    public function test_addons_can_be_excluded_when_inherited_and_added_when_extra(): void
    {
        $category = Category::factory()->create();
        $inherited = Addon::factory()->create();
        $inherited->categories()->attach($category);
        $extra = Addon::factory()->create();
        $item = MenuItem::factory()->for($category)->create();

        $this->edit($item)
            ->assertSeeText($inherited->translate('name'))
            ->fillForm(['excludedAddons' => [$inherited->id], 'extraAddons' => [$extra->id]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame([$inherited->id], $item->excludedAddons()->pluck('addons.id')->all());
        $this->assertSame([$extra->id], $item->extraAddons()->pluck('addons.id')->all());
        $this->assertSame([$extra->id], $item->effectiveAddons()->pluck('id')->all());
    }

    public function test_an_item_can_be_deleted_from_the_edit_page(): void
    {
        $item = MenuItem::factory()->create();

        $this->edit($item)->callAction('delete');

        $this->assertModelMissing($item);
    }
}
