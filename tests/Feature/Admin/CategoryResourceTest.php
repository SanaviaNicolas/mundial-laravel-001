<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\RelationManagers\ChildrenRelationManager;
use App\Filament\Resources\Categories\RelationManagers\ItemsRelationManager;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryResourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_list_shows_only_macro_categories_with_search_sort_and_visibility_filter(): void
    {
        $pizzas = Category::factory()->create(['name' => ['it' => 'Pizze', 'en' => 'Pizzas'], 'sort_order' => 1]);
        $drinks = Category::factory()->hidden()->create(['name' => ['it' => 'Bibite', 'en' => 'Drinks'], 'sort_order' => 2]);
        $classic = Category::factory()->for($pizzas, 'parent')->create(['name' => ['it' => 'Classiche']]);

        Livewire::test(ListCategories::class)
            ->assertCanSeeTableRecords([$pizzas, $drinks])
            ->assertCanNotSeeTableRecords([$classic])
            ->searchTable('bib')
            ->assertCanSeeTableRecords([$drinks])
            ->assertCanNotSeeTableRecords([$pizzas])
            ->searchTable('')
            ->sortTable('name')
            ->assertCanSeeTableRecords([$drinks, $pizzas], inOrder: true)
            ->filterTable('is_visible', false)
            ->assertCanSeeTableRecords([$drinks])
            ->assertCanNotSeeTableRecords([$pizzas]);
    }

    public function test_macro_categories_can_be_reordered(): void
    {
        $first = Category::factory()->create(['sort_order' => 1]);
        $second = Category::factory()->create(['sort_order' => 2]);

        Livewire::test(ListCategories::class)->call('reorderTable', [$second->id, $first->id]);

        $this->assertSame([$second->id, $first->id], Category::ordered()->pluck('id')->all());
    }

    public function test_create_a_macro_category(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name' => ['it' => 'Dolci', 'en' => 'Desserts'],
                'slug' => 'dolci',
                'description' => ['it' => 'Fatti in casa', 'en' => 'Homemade'],
                'is_visible' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::firstWhere('slug', 'dolci');
        $this->assertNull($category->parent_id);
        $this->assertSame('Homemade', $category->translate('description', 'en'));
    }

    public function test_slug_is_generated_from_the_italian_name_unique_and_well_formed(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => 'Tradizione napoletana']])
            ->assertFormSet(['slug' => 'tradizione-napoletana']);

        Category::factory()->create(['slug' => 'dolci']);

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => 'Dolci'], 'slug' => 'dolci'])
            ->call('create')
            ->assertHasFormErrors(['slug' => 'unique']);

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => 'X'], 'slug' => 'Non valido!'])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_italian_name_is_required(): void
    {
        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => ''], 'slug' => 'x'])
            ->call('create')
            ->assertHasFormErrors(['name.it' => 'required']);
    }

    public function test_the_parent_can_only_be_a_macro_category(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => 'Nipote'], 'slug' => 'nipote', 'parent_id' => $sub->id])
            ->call('create')
            ->assertHasFormErrors(['parent_id']);

        Livewire::test(CreateCategory::class)
            ->fillForm(['name' => ['it' => 'Figlia'], 'slug' => 'figlia', 'parent_id' => $macro->id])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertTrue(Category::firstWhere('slug', 'figlia')->parent->is($macro));
    }

    public function test_a_macro_with_subcategories_cannot_be_moved_under_another_category(): void
    {
        $macro = Category::factory()->create();
        Category::factory()->for($macro, 'parent')->create();
        $leaf = Category::factory()->create();

        Livewire::test(EditCategory::class, ['record' => $macro->getRouteKey()])
            ->assertFormFieldDisabled('parent_id');
        Livewire::test(EditCategory::class, ['record' => $leaf->getRouteKey()])
            ->assertFormFieldEnabled('parent_id');
    }

    public function test_edit_updates_the_category(): void
    {
        $category = Category::factory()->create(['name' => ['it' => 'Vecchio']]);

        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm(['name' => ['it' => 'Nuovo', 'en' => 'New'], 'is_visible' => false])
            ->call('save')
            ->assertHasNoFormErrors();

        $category->refresh();
        $this->assertSame('Nuovo', $category->translate('name', 'it'));
        $this->assertFalse($category->is_visible);
    }

    public function test_a_category_with_subcategories_or_items_cannot_be_deleted_from_the_panel(): void
    {
        $withChild = Category::factory()->create();
        Category::factory()->for($withChild, 'parent')->create();
        $withItem = Category::factory()->create();
        MenuItem::factory()->for($withItem)->create();
        $empty = Category::factory()->create();

        Livewire::test(EditCategory::class, ['record' => $withChild->getRouteKey()])->assertActionHidden('delete');
        Livewire::test(EditCategory::class, ['record' => $withItem->getRouteKey()])->assertActionHidden('delete');
        Livewire::test(EditCategory::class, ['record' => $empty->getRouteKey()])->callAction('delete');

        $this->assertModelMissing($empty);
        $this->assertModelExists($withChild);
        $this->assertModelExists($withItem);
    }

    public function test_subcategories_are_managed_and_reordered_inside_the_macro(): void
    {
        $macro = Category::factory()->create();
        $first = Category::factory()->for($macro, 'parent')->create(['sort_order' => 1]);
        $second = Category::factory()->for($macro, 'parent')->create(['sort_order' => 2]);
        Category::factory()->create();

        $manager = Livewire::test(ChildrenRelationManager::class, ['ownerRecord' => $macro, 'pageClass' => EditCategory::class])
            ->assertCanSeeTableRecords([$first, $second], inOrder: true)
            ->call('reorderTable', [$second->id, $first->id]);

        $this->assertSame([$second->id, $first->id], $macro->fresh()->children->pluck('id')->all());

        $manager->callAction(TestAction::make('create')->table(), [
            'name' => ['it' => 'Speciali', 'en' => 'Specials'],
            'slug' => 'speciali',
            'is_visible' => true,
        ])->assertHasNoFormErrors();

        $this->assertTrue(Category::firstWhere('slug', 'speciali')->parent->is($macro));
    }

    public function test_subcategories_are_not_offered_on_a_subcategory(): void
    {
        $macro = Category::factory()->create();
        $sub = Category::factory()->for($macro, 'parent')->create();

        $this->assertTrue(ChildrenRelationManager::canViewForRecord($macro, EditCategory::class));
        $this->assertFalse(ChildrenRelationManager::canViewForRecord($sub, EditCategory::class));
    }

    public function test_items_of_a_category_are_listed_and_reordered_inside_it(): void
    {
        $category = Category::factory()->create();
        $first = MenuItem::factory()->for($category)->create(['sort_order' => 1]);
        $second = MenuItem::factory()->for($category)->create(['sort_order' => 2]);
        $other = MenuItem::factory()->create();

        Livewire::test(ItemsRelationManager::class, ['ownerRecord' => $category, 'pageClass' => EditCategory::class])
            ->assertCanSeeTableRecords([$first, $second], inOrder: true)
            ->assertCanNotSeeTableRecords([$other])
            ->call('reorderTable', [$second->id, $first->id]);

        $this->assertSame([$second->id, $first->id], $category->fresh()->items->pluck('id')->all());
    }
}
