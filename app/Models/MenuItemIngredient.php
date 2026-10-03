<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\MenuItemIngredientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An ingredient line of a menu item: ordered, optionally added after cooking,
 * optionally grouped in a named section (e.g. the two halves of a half-and-half item).
 *
 * @property array<string, string>|null $section
 * @property bool $after_cooking
 */
#[Fillable(['menu_item_id', 'ingredient_id', 'sort_order', 'after_cooking', 'section'])]
class MenuItemIngredient extends Model
{
    /** @use HasFactory<MenuItemIngredientFactory> */
    use HasFactory, HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['section'];

    protected static function booted(): void
    {
        static::saved(function (MenuItemIngredient $line) {
            if ($line->wasRecentlyCreated || $line->wasChanged('ingredient_id')) {
                MenuItem::unverifyAllergensWhere(fn ($query) => $query->whereKey($line->menu_item_id));
            }
        });

        static::deleted(function (MenuItemIngredient $line) {
            MenuItem::unverifyAllergensWhere(fn ($query) => $query->whereKey($line->menu_item_id));
        });
    }

    protected function casts(): array
    {
        return [
            'after_cooking' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<MenuItem, $this>
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * @return BelongsTo<Ingredient, $this>
     */
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
