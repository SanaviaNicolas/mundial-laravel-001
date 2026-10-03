<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Models\Tag;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class TagResourceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_list_shows_tags_with_search_and_sort_on_the_translated_name(): void
    {
        $vegan = Tag::factory()->create(['slug' => 'vegano', 'name' => ['it' => 'Vegano', 'en' => 'Vegan']]);
        $spicy = Tag::factory()->create(['slug' => 'piccante', 'name' => ['it' => 'Piccante']]);

        Livewire::test(ListTags::class)
            ->assertCanSeeTableRecords([$vegan, $spicy])
            ->searchTable('picc')
            ->assertCanSeeTableRecords([$spicy])
            ->assertCanNotSeeTableRecords([$vegan])
            ->searchTable('')
            ->sortTable('name', 'desc')
            ->assertCanSeeTableRecords([$vegan, $spicy], inOrder: true);
    }

    public function test_create_a_tag_with_translations(): void
    {
        Livewire::test(CreateTag::class)
            ->fillForm(['name' => ['it' => 'Novità', 'en' => 'New'], 'slug' => 'novita'])
            ->call('create')
            ->assertHasNoFormErrors();

        $tag = Tag::firstWhere('slug', 'novita');
        $this->assertEquals(['it' => 'Novità', 'en' => 'New'], $tag->name);
    }

    public function test_create_requires_the_italian_name_and_a_unique_well_formed_slug(): void
    {
        Tag::factory()->create(['slug' => 'vegano']);

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => ['it' => '', 'en' => 'Vegan'], 'slug' => 'vegano'])
            ->call('create')
            ->assertHasFormErrors(['name.it' => 'required', 'slug' => 'unique']);

        Livewire::test(CreateTag::class)
            ->fillForm(['name' => ['it' => 'X'], 'slug' => 'Non valido!'])
            ->call('create')
            ->assertHasFormErrors(['slug']);
    }

    public function test_edit_updates_names_but_never_the_slug(): void
    {
        $tag = Tag::factory()->create(['slug' => 'vegano', 'name' => ['it' => 'Vegano']]);

        Livewire::test(EditTag::class, ['record' => $tag->getRouteKey()])
            ->assertFormSet(['slug' => 'vegano', 'name.it' => 'Vegano'])
            ->fillForm(['slug' => 'altro', 'name' => ['it' => 'Vegana', 'en' => 'Vegan']])
            ->call('save')
            ->assertHasNoFormErrors();

        $tag->refresh();
        $this->assertSame('vegano', $tag->slug);
        $this->assertEquals(['it' => 'Vegana', 'en' => 'Vegan'], $tag->name);
    }
}
