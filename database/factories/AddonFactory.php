<?php

namespace Database\Factories;

use App\Models\Addon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Addon>
 */
class AddonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ['it' => $name, 'en' => $name],
            'price_cents' => fake()->numberBetween(50, 300),
            'sort_order' => 0,
            'is_visible' => true,
        ];
    }

    public function free(): static
    {
        return $this->state(['price_cents' => 0]);
    }

    public function hidden(): static
    {
        return $this->state(['is_visible' => false]);
    }
}
