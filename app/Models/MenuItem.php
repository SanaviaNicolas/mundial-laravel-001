<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A dish or drink. Price in cents, null while the price is not known.
 *
 * @property array<string, string> $name
 * @property array<string, string>|null $description
 * @property array<string, string>|null $notes
 * @property int|null $price_cents
 */
#[Fillable(['category_id', 'name', 'description', 'notes', 'price_cents', 'sort_order', 'is_visible'])]
class MenuItem extends Model
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory, HasTranslations;

    protected array $translatable = ['name', 'description', 'notes'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ingredient lines in display order (with after-cooking flag and section).
     *
     * @return HasMany<MenuItemIngredient, $this>
     */
    public function ingredientLines(): HasMany
    {
        return $this->hasMany(MenuItemIngredient::class)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return BelongsToMany<Ingredient, $this>
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients');
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Visible on the public site: the item and its category chain must be visible.
     *
     * @param  Builder<static>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true)
            ->whereIn('category_id', Category::visible()->select('id'));
    }
}
