<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Allergens of an ingredient. Changing them invalidates the allergen verification
 * of every menu item using that ingredient.
 */
class AllergenIngredient extends Pivot
{
    public $incrementing = false;

    protected static function booted(): void
    {
        $unverify = fn (AllergenIngredient $pivot) => MenuItem::unverifyAllergensWhere(
            fn ($query) => $query->whereIn(
                'id',
                MenuItemIngredient::select('menu_item_id')->where('ingredient_id', $pivot->ingredient_id),
            ),
        );

        static::created($unverify);
        static::deleted($unverify);
    }
}
