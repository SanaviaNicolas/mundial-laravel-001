<?php

namespace App\Menu;

use App\Models\Addon as AddonModel;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\MenuItemIngredient;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Menu managed in Filament (models in app/Models): visible categories and items in the admin
 * order, texts in the current locale with Italian fallback, effective addons and public allergens.
 */
final class DatabaseMenu implements MenuSource
{
    public function sections(): array
    {
        // Only visible macro categories are loaded, so their subcategories and items just need
        // their own visibility flag (the whole chain is then visible, as in MenuItem::visible()).
        $items = fn (Relation $query) => $query->where('is_visible', true)->with(['ingredientLines.ingredient', 'tags']);
        $children = fn (Relation $query) => $query->where('is_visible', true)->with(['items' => $items]);

        $macros = Category::query()
            ->visible()
            ->whereNull('parent_id')
            ->ordered()
            ->with(['items' => $items, 'children' => $children])
            ->get();

        return Section::withoutEmpty($macros->map($this->section(...))->all());
    }

    private function section(Category $category): Section
    {
        return new Section(
            $category->slug,
            (string) $category->translate('name'),
            $category->translate('description'),
            $category->items->map($this->item(...))->all(),
            $category->relationLoaded('children') ? $category->children->map($this->section(...))->all() : [],
        );
    }

    private function item(MenuItem $item): Item
    {
        return new Item(
            (string) $item->translate('name'),
            $item->translate('description'),
            $item->translate('notes'),
            $item->price_cents,
            $item->ingredientLines->map(fn (MenuItemIngredient $line) => new Ingredient(
                (string) $line->ingredient->translate('name'),
                $line->ingredient->is_frozen,
                $line->after_cooking,
                $line->translate('section'),
            ))->all(),
            $item->tags->mapWithKeys(fn ($tag) => [$tag->slug => (string) $tag->translate('name')])->all(),
            $item->effectiveAddons()->load('allergens')->map(fn (AddonModel $addon) => new Addon(
                (string) $addon->translate('name'),
                $addon->price_cents,
                $this->names($addon->allergens),
            ))->all(),
            ($allergens = $item->publicAllergens()) === null ? null : $this->names($allergens),
        );
    }

    /**
     * @param  iterable<Allergen>  $allergens
     * @return list<string>
     */
    private function names(iterable $allergens): array
    {
        return collect($allergens)->map(fn (Allergen $allergen) => (string) $allergen->translate('name'))->values()->all();
    }
}
