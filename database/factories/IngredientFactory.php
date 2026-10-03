<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ['it' => $name, 'en' => $name],
            'is_frozen' => false,
        ];
    }

    public function frozen(): static
    {
        return $this->state(['is_frozen' => true]);
    }
}
