<?php

namespace App\Menu;

/**
 * A menu category: a macro category with its own items and/or subcategories (two levels at most).
 */
final readonly class Section
{
    /**
     * @param  list<Item>  $items
     * @param  list<Section>  $children
     */
    public function __construct(
        public string $slug,
        public string $name,
        public ?string $description,
        public array $items,
        public array $children = [],
    ) {}

    /**
     * Drops the sections without items, also when all their subcategories are empty.
     *
     * @param  list<Section>  $sections
     * @return list<Section>
     */
    public static function withoutEmpty(array $sections): array
    {
        $kept = [];

        foreach ($sections as $section) {
            $section = new self($section->slug, $section->name, $section->description, $section->items, self::withoutEmpty($section->children));

            if ($section->items !== [] || $section->children !== []) {
                $kept[] = $section;
            }
        }

        return $kept;
    }

    /**
     * Sections that hold items directly, in page order: the anchors of the menu navigation.
     *
     * @return list<Section>
     */
    public function leaves(): array
    {
        return array_merge($this->items === [] ? [] : [$this], ...array_map(fn (Section $child) => $child->leaves(), $this->children));
    }

    /**
     * Addons that every item of the section offers (shown once, at the top of the section);
     * empty when the items differ.
     *
     * @return list<Addon>
     */
    public function sharedAddons(): array
    {
        $first = $this->items[0]->addons ?? [];

        foreach ($this->items as $item) {
            if ($item->addons != $first) {
                return [];
            }
        }

        return $first;
    }

    /**
     * No item of the section has verified allergens: the "ask the staff" notice is shown once.
     */
    public function allergensUnverified(): bool
    {
        foreach ($this->items as $item) {
            if ($item->allergens !== null) {
                return false;
            }
        }

        return true;
    }
}
