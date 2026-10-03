<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use InvalidArgumentException;
use Tests\TestCase;

class TranslationQueryTest extends TestCase
{
    private function category(string $it, ?string $en = null): Category
    {
        return Category::factory()->create(['name' => array_filter(['it' => $it, 'en' => $en])]);
    }

    public function test_search_matches_the_requested_locale(): void
    {
        $drinks = $this->category('Bibite', 'Drinks');
        $this->category('Dolci', 'Desserts');

        $this->assertSame([$drinks->id], Category::whereTranslationLike('name', 'drin', 'en')->pluck('id')->all());
    }

    public function test_search_falls_back_to_italian_when_the_translation_is_missing(): void
    {
        $baguette = $this->category('Baguette');

        $this->assertSame([$baguette->id], Category::whereTranslationLike('name', 'bagu', 'en')->pluck('id')->all());
    }

    public function test_search_is_case_insensitive_and_escapes_wildcards(): void
    {
        $this->category('Pizze');

        $this->assertCount(1, Category::whereTranslationLike('name', 'PIZ')->get());
        $this->assertCount(0, Category::whereTranslationLike('name', '%')->get());
    }

    public function test_order_uses_the_locale_with_italian_fallback(): void
    {
        $zucchine = $this->category('Zucchine', 'Courgettes');
        $baguette = $this->category('Baguette');
        $mele = $this->category('Mele', 'Apples');

        $this->assertSame(
            [$mele->id, $baguette->id, $zucchine->id],
            Category::orderByTranslation('name', 'asc', 'en')->pluck('id')->all(),
        );
        $this->assertSame(
            [$zucchine->id, $mele->id, $baguette->id],
            Category::orderByTranslation('name', 'desc', 'it')->pluck('id')->all(),
        );
    }

    public function test_only_translatable_attributes_are_accepted(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Category::orderByTranslation('slug')->get();
    }
}
