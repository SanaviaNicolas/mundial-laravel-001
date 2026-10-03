<?php

namespace App\Support;

class Menu
{
    /**
     * Menu categories localized for the current locale. Texts in config/menu.php are either
     * plain strings (same in every language) or ['it' => ..., 'en' => ...]; prices are numbers.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function categories(): array
    {
        return collect(config('menu'))->map(fn (array $category) => [
            'nome' => self::text($category['nome']),
            'descrizione' => isset($category['descrizione']) ? self::text($category['descrizione']) : null,
            'voci' => collect($category['voci'])->map(fn (array $voce) => [
                'nome' => self::text($voce['nome']),
                'ingredienti' => self::text($voce['ingredienti']),
                'nota' => isset($voce['nota']) ? self::text($voce['nota']) : null,
                'prezzo' => self::price($voce['prezzo']),
                'badge' => $voce['badge'] ?? null,
            ])->all(),
        ])->all();
    }

    private static function text(string|array $value): string
    {
        return is_array($value) ? ($value[app()->getLocale()] ?? $value['it']) : $value;
    }

    private static function price(string|int|float $price): string
    {
        if (! is_numeric($price)) {
            return $price;
        }

        return '€ '.(app()->getLocale() === 'it' ? number_format((float) $price, 2, ',', '.') : number_format((float) $price, 2));
    }
}
