<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Ingredients\Pages\CreateIngredient;
use App\Filament\Resources\Ingredients\Pages\EditIngredient;
use App\Filament\Resources\Ingredients\Pages\ListIngredients;
use App\Models\Allergen;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class IngredientResourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_list_with_translated_search_sort_and_frozen_filter(): void
    {
        $shrimp = Ingredient::factory()->frozen()->create(['name' => ['it' => 'Gamberetti', 'en' => 'Shrimp']]);
        $basil = Ingredient::factory()->create(['name' => ['it' => 'Basilico']]);

        Livewire::test(ListIngredients::class)
            ->assertCanSeeTableRecords([$shrimp, $basil])
            ->searchTable('gamb')
            ->assertCanSeeTableRecords([$shrimp])
            ->assertCanNotSeeTableRecords([$basil])
            ->searchTable('')
            ->sortTable('name')
            ->assertCanSeeTableRecords([$basil, $shrimp], inOrder: true)
            ->filterTable('is_frozen', true)
            ->assertCanSeeTableRecords([$shrimp])
            ->assertCanNotSeeTableRecords([$basil]);
    }

    public function test_create_with_translations_frozen_flag_and_allergens(): void
    {
        $milk = Allergen::factory()->create();
        $eggs = Allergen::factory()->create();

        Livewire::test(CreateIngredient::class)
            ->fillForm([
                'name' => ['it' => 'Fior di latte', 'en' => 'Mozzarella'],
                'is_frozen' => true,
                'allergens' => [$milk->id, $eggs->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $ingredient = Ingredient::first();
        $this->assertTrue($ingredient->is_frozen);
        $this->assertEquals(['it' => 'Fior di latte', 'en' => 'Mozzarella'], $ingredient->name);
        $this->assertEqualsCanonicalizing([$milk->id, $eggs->id], $ingredient->allergens->pluck('id')->all());
    }

    public function test_italian_name_is_required(): void
    {
        Livewire::test(CreateIngredient::class)
            ->fillForm(['name' => ['it' => '', 'en' => 'Basil']])
            ->call('create')
            ->assertHasFormErrors(['name.it' => 'required']);
    }

    public function test_editing_the_allergens_resets_the_verification_of_the_items_using_the_ingredient(): void
    {
        $ingredient = Ingredient::factory()->create();
        $item = MenuItem::factory()->create();
        MenuItemIngredient::factory()->for($item)->for($ingredient)->create();
        $item->verifyAllergens();
        $gluten = Allergen::factory()->create();

        Livewire::test(EditIngredient::class, ['record' => $ingredient->getRouteKey()])
            ->fillForm(['allergens' => [$gluten->id]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse($item->fresh()->isAllergensVerified());
    }

    public function test_an_ingredient_in_use_cannot_be_deleted_from_the_panel(): void
    {
        $used = MenuItemIngredient::factory()->create()->ingredient;
        $free = Ingredient::factory()->create();

        Livewire::test(EditIngredient::class, ['record' => $used->getRouteKey()])->assertActionHidden('delete');

        Livewire::test(EditIngredient::class, ['record' => $free->getRouteKey()])
            ->callAction('delete');

        $this->assertModelMissing($free);
        $this->assertModelExists($used);
    }
}
