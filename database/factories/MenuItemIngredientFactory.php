<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItemIngredient>
 */
class MenuItemIngredientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_item_id' => MenuItem::factory(),
            'ingredient_id' => Ingredient::factory(),
            'sort_order' => 0,
            'after_cooking' => false,
            'section' => null,
        ];
    }
}
