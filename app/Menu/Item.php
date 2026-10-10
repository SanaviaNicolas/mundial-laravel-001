<?php

namespace App\Menu;

/**
 * A dish or drink. `price` in cents, null while unknown (then nothing is shown).
 * `allergens` is null while NOT verified: never read it as "no allergens".
 */
final readonly class Item
{
    /** Tags shown as highlight badges and collected in the "In evidenza" section of the menu. */
    public const HIGHLIGHTS = ['pizza-del-mese', 'la-piu-scelta', 'novita', 'stagionale'];

    /**
     * @param  list<Ingredient>  $ingredients
     * @param  array<string, string>  $tags  slug => name
     * @param  list<Addon>  $addons
     * @param  list<string>|null  $allergens
     */
    public function __construct(
        public string $name,
        public ?string $description,
        public ?string $notes,
        public ?int $price,
        public array $ingredients,
        public array $tags,
        public array $addons,
        public ?array $allergens,
    ) {}

    public function isHighlighted(): bool
    {
        return array_intersect(array_keys($this->tags), self::HIGHLIGHTS) !== [];
    }

    /**
     * Ingredients grouped by section (in order of appearance; a single null section for normal
     * items), each split into the ones baked with the item and the ones added after cooking.
     *
     * @return list<array{section: string|null, cooked: list<Ingredient>, after: list<Ingredient>}>
     */
    public function ingredientGroups(): array
    {
        $groups = [];

        foreach ($this->ingredients as $ingredient) {
            $groups[$ingredient->section] ??= ['section' => $ingredient->section, 'cooked' => [], 'after' => []];
            $groups[$ingredient->section][$ingredient->afterCooking ? 'after' : 'cooked'][] = $ingredient;
        }

        return array_values($groups);
    }
}
