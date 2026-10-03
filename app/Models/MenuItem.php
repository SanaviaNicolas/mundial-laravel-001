<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;

/**
 * A dish or drink. Price in cents, null while the price is not known.
 *
 * @property array<string, string> $name
 * @property array<string, string>|null $description
 * @property array<string, string>|null $notes
 * @property int|null $price_cents
 * @property Carbon|null $allergens_verified_at
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
            'allergens_verified_at' => 'datetime',
        ];
    }

    /**
     * Resets, with a single query, the allergen verification of the items matched by the callback.
     *
     * @param  callable(Builder<MenuItem>): mixed  $constraint
     */
    public static function unverifyAllergensWhere(callable $constraint): void
    {
        $query = self::query();
        $constraint($query);
        $query->update(['allergens_verified_at' => null]);
    }

    /**
     * Allergens attached directly to the item (drinks, extras such as the dough gluten).
     *
     * @return BelongsToMany<Allergen, $this, AllergenMenuItem>
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class)->using(AllergenMenuItem::class);
    }

    /**
     * The restaurant confirmed that the effective allergen list is complete.
     * An empty list does NOT mean "no allergens" unless this is true.
     */
    public function isAllergensVerified(): bool
    {
        return $this->allergens_verified_at !== null;
    }

    public function verifyAllergens(): void
    {
        $this->writeAllergensVerifiedAt(now());
    }

    public function unverifyAllergens(): void
    {
        $this->writeAllergensVerifiedAt(null);
    }

    /**
     * Written with a query: an in-memory copy that is out of date (e.g. after an automatic
     * reset) must still persist the change.
     */
    private function writeAllergensVerifiedAt(?Carbon $at): void
    {
        self::whereKey($this->getKey())->update(['allergens_verified_at' => $at]);

        $this->setAttribute('allergens_verified_at', $at)->syncOriginalAttribute('allergens_verified_at');
    }

    /**
     * Allergens of the item: union of its ingredients' allergens and its direct allergens.
     *
     * @return Collection<int, Allergen>
     */
    public function effectiveAllergens(): Collection
    {
        return Allergen::query()
            ->where(fn (Builder $query) => $query
                ->whereHas('ingredients', fn (Builder $ingredients) => $ingredients
                    ->whereIn('ingredients.id', $this->ingredients()->select('ingredients.id')))
                ->orWhereHas('menuItems', fn (Builder $items) => $items->whereKey($this->getKey())))
            ->ordered()
            ->get();
    }

    /**
     * Allergens that may be shown to the public: null while NOT verified (never read it as
     * "no allergens"); an empty collection when verified and the item has none.
     *
     * @return Collection<int, Allergen>|null
     */
    public function publicAllergens(): ?Collection
    {
        return $this->isAllergensVerified() ? $this->effectiveAllergens() : null;
    }

    /**
     * Allergens brought in by the visible effective addons ("with this addon it contains..."),
     * keyed by addon id; addons without allergens are left out. Never merged into the item allergens.
     *
     * @return SupportCollection<int<0, max>, Collection<int, Allergen>>
     */
    public function addonAllergens(): SupportCollection
    {
        return $this->effectiveAddons()
            ->load('allergens')
            ->filter(fn (Addon $addon) => $addon->allergens->isNotEmpty())
            ->mapWithKeys(fn (Addon $addon) => [$addon->id => $addon->allergens]);
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
     * Addons linked directly to the item (extras).
     *
     * @return BelongsToMany<Addon, $this>
     */
    public function extraAddons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class)->withPivotValue('is_excluded', false);
    }

    /**
     * Addons explicitly excluded from the item (even if inherited from its category).
     *
     * @return BelongsToMany<Addon, $this>
     */
    public function excludedAddons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class)->withPivotValue('is_excluded', true);
    }

    /**
     * Addons offered with this item: those of its category and of its macro category,
     * plus the directly linked ones, minus the explicit exclusions. No duplicates.
     *
     * @return Collection<int, Addon>
     */
    public function effectiveAddons(bool $visibleOnly = true): Collection
    {
        $categoryIds = array_filter([$this->category_id, $this->category->parent_id]);

        return Addon::query()
            ->where(fn (Builder $query) => $query
                ->whereHas('categories', fn (Builder $categories) => $categories->whereIn('categories.id', $categoryIds))
                ->orWhereIn('addons.id', $this->extraAddons()->select('addons.id')))
            ->whereNotIn('addons.id', $this->excludedAddons()->select('addons.id'))
            ->when($visibleOnly, fn (Builder $query) => $query->visible())
            ->ordered()
            ->get();
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
