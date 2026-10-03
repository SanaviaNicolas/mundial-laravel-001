<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Addons\Pages\CreateAddon;
use App\Filament\Resources\Addons\Pages\EditAddon;
use App\Filament\Resources\Addons\Pages\ListAddons;
use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class AddonResourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_list_with_translated_search_sort_and_visibility_filter(): void
    {
        $crust = Addon::factory()->create(['name' => ['it' => 'Cornicione ripieno', 'en' => 'Stuffed crust']]);
        $gluten = Addon::factory()->hidden()->create(['name' => ['it' => 'Senza glutine']]);

        Livewire::test(ListAddons::class)
            ->assertCanSeeTableRecords([$crust, $gluten])
            ->searchTable('corn')
            ->assertCanSeeTableRecords([$crust])
            ->assertCanNotSeeTableRecords([$gluten])
            ->searchTable('')
            ->filterTable('is_visible', false)
            ->assertCanSeeTableRecords([$gluten])
            ->assertCanNotSeeTableRecords([$crust]);
    }

    public function test_drag_and_drop_reordering_updates_sort_order(): void
    {
        $first = Addon::factory()->create(['sort_order' => 1]);
        $second = Addon::factory()->create(['sort_order' => 2]);

        Livewire::test(ListAddons::class)
            ->assertCanSeeTableRecords([$first, $second], inOrder: true)
            ->call('reorderTable', [$second->id, $first->id]);

        $this->assertSame([$second->id, $first->id], Addon::ordered()->pluck('id')->all());
    }

    public function test_create_converts_the_euro_price_to_cents_and_links_categories_and_allergens(): void
    {
        $category = Category::factory()->create();
        $milk = Allergen::factory()->create();

        Livewire::test(CreateAddon::class)
            ->fillForm([
                'name' => ['it' => 'Aggiunta bufala', 'en' => 'Extra buffalo'],
                'price_cents' => '1.50',
                'is_visible' => true,
                'categories' => [$category->id],
                'allergens' => [$milk->id],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $addon = Addon::first();
        $this->assertSame(150, $addon->price_cents);
        $this->assertSame([$category->id], $addon->categories->pluck('id')->all());
        $this->assertSame([$milk->id], $addon->allergens->pluck('id')->all());
    }

    public function test_a_zero_supplement_is_allowed_but_not_a_negative_or_missing_one(): void
    {
        Livewire::test(CreateAddon::class)
            ->fillForm(['name' => ['it' => 'Senza lattosio'], 'price_cents' => '0'])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertSame(0, Addon::first()->price_cents);

        Livewire::test(CreateAddon::class)
            ->fillForm(['name' => ['it' => 'X'], 'price_cents' => '-1'])
            ->call('create')
            ->assertHasFormErrors(['price_cents']);

        Livewire::test(CreateAddon::class)
            ->fillForm(['name' => ['it' => 'X'], 'price_cents' => null])
            ->call('create')
            ->assertHasFormErrors(['price_cents' => 'required']);
    }

    public function test_edit_shows_the_price_in_euros(): void
    {
        $addon = Addon::factory()->create(['price_cents' => 250]);

        Livewire::test(EditAddon::class, ['record' => $addon->getRouteKey()])
            ->assertFormSet(['price_cents' => 2.5])
            ->fillForm(['price_cents' => '3.20'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(320, $addon->fresh()->price_cents);
    }

    public function test_italian_name_is_required(): void
    {
        Livewire::test(CreateAddon::class)
            ->fillForm(['name' => ['it' => ''], 'price_cents' => '1'])
            ->call('create')
            ->assertHasFormErrors(['name.it' => 'required']);
    }
}
