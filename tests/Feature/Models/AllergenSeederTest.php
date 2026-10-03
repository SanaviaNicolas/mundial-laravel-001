<?php

namespace Tests\Feature\Models;

use App\Models\Allergen;
use Database\Seeders\AllergenSeeder;
use Tests\TestCase;

class AllergenSeederTest extends TestCase
{
    public function test_migrations_do_not_insert_any_data(): void
    {
        $this->assertSame(0, Allergen::count());
    }

    public function test_seeder_creates_exactly_the_14_eu_allergens(): void
    {
        $this->seed(AllergenSeeder::class);

        $this->assertSame(14, Allergen::count());
        $this->assertSame(
            ['gluten', 'crustaceans', 'eggs', 'fish', 'peanuts', 'soybeans', 'milk', 'nuts', 'celery', 'mustard', 'sesame', 'sulphites', 'lupin', 'molluscs'],
            Allergen::ordered()->pluck('key')->all(),
        );
    }

    public function test_every_allergen_has_italian_and_english_names(): void
    {
        $this->seed(AllergenSeeder::class);

        Allergen::all()->each(function (Allergen $allergen) {
            $this->assertNotEmpty($allergen->translate('name', 'it'), $allergen->key);
            $this->assertNotEmpty($allergen->name['en'] ?? null, $allergen->key);
        });
    }

    public function test_seeder_is_idempotent(): void
    {
        $this->seed(AllergenSeeder::class);
        $ids = Allergen::ordered()->pluck('id', 'key')->all();

        Allergen::where('key', 'milk')->update(['name' => json_encode(['it' => 'sbagliato'])]);
        $this->seed(AllergenSeeder::class);

        $this->assertSame(14, Allergen::count());
        $this->assertSame($ids, Allergen::ordered()->pluck('id', 'key')->all());
        $this->assertNotSame('sbagliato', Allergen::where('key', 'milk')->first()->translate('name', 'it'));
    }

    public function test_factory_creates_an_allergen(): void
    {
        $allergen = Allergen::factory()->create();

        $this->assertNotEmpty($allergen->key);
        $this->assertNotEmpty($allergen->translate('name'));
    }
}
