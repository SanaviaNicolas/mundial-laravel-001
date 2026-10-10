<?php

namespace App\Menu;

/**
 * Menu read from config/menu.php, shaped like the admin data (see that file). Used until the
 * menu is filled in Filament; static data is never allergen-verified.
 */
final class StaticMenu implements MenuSource
{
    public function sections(): array
    {
        return Section::withoutEmpty($this->build(config('menu.sections'), []));
    }

    /**
     * @param  array<string, array<string, mixed>>  $sections
     * @param  list<array<string, mixed>>  $inherited  addons of the macro category
     * @return list<Section>
     */
    private function build(array $sections, array $inherited): array
    {
        $built = [];

        foreach ($sections as $slug => $section) {
            $addons = [...$inherited, ...$section['addons'] ?? []];

            $built[] = new Section(
                $slug,
                $this->text($section['name']),
                $this->text($section['description'] ?? null),
                array_map(fn (array $item) => $this->item($item, $addons), $section['items'] ?? []),
                $this->build($section['children'] ?? [], $addons),
            );
        }

        return $built;
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<array<string, mixed>>  $addons
     */
    private function item(array $item, array $addons): Item
    {
        return new Item(
            $this->text($item['name']),
            $this->text($item['description'] ?? null),
            $this->text($item['notes'] ?? null),
            $item['price'] ?? null,
            array_map($this->ingredient(...), $item['ingredients'] ?? []),
            collect($item['tags'] ?? [])->mapWithKeys(fn (string $slug) => [$slug => $this->text(config("menu.tags.$slug"))])->all(),
            array_map(fn (array $addon) => new Addon($this->text($addon['name']), $addon['price']), [...$addons, ...$item['addons'] ?? []]),
            null,
        );
    }

    /**
     * A line is a name (string or translations) or ['name' => ..., 'frozen' => , 'after' => , 'section' => ].
     *
     * @param  string|array<string, mixed>  $line
     */
    private function ingredient(string|array $line): Ingredient
    {
        if (! is_array($line) || ! isset($line['name'])) {
            return new Ingredient($this->text($line));
        }

        return new Ingredient($this->text($line['name']), $line['frozen'] ?? false, $line['after'] ?? false, $this->text($line['section'] ?? null));
    }

    /**
     * A text is a plain string (same in every language) or ['it' => ..., 'en' => ...] with Italian fallback.
     *
     * @param  string|array<string, string>|null  $value
     */
    private function text(string|array|null $value): ?string
    {
        return is_array($value) ? ($value[app()->getLocale()] ?? $value['it']) : $value;
    }
}
