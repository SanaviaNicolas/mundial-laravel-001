<?php

namespace Database\Factories;

use App\Models\Allergen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Allergen>
 */
class AllergenFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $word = fake()->unique()->word();

        return [
            'key' => $word,
            'name' => ['it' => $word, 'en' => $word],
            'sort_order' => fake()->unique()->numberBetween(100, 9999),
        ];
    }
}
