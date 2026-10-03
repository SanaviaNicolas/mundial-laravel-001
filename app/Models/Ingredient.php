<?php

namespace App\Models;

use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Reusable ingredient. Frozen ones are marked with an asterisk on the public site.
 *
 * @property array<string, string> $name
 * @property bool $is_frozen
 */
#[Fillable(['name', 'is_frozen'])]
class Ingredient extends TranslatableModel
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory;

    protected array $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'is_frozen' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<MenuItem, $this>
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients');
    }

    /**
     * @return BelongsToMany<Allergen, $this, AllergenIngredient>
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class)->using(AllergenIngredient::class);
    }
}
