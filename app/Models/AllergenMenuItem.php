<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Allergens attached directly to a menu item. Changing them invalidates its allergen verification.
 */
class AllergenMenuItem extends Pivot
{
    public $incrementing = false;

    protected static function booted(): void
    {
        $unverify = fn (AllergenMenuItem $pivot) => MenuItem::unverifyAllergensWhere(
            fn ($query) => $query->whereKey($pivot->menu_item_id),
        );

        static::created($unverify);
        static::deleted($unverify);
    }
}
