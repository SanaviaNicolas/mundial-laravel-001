<?php

namespace App\Menu;

final class Price
{
    /** "€ 8,50" (Italian) or "€ 8.50" (English); null when the price is unknown. */
    public static function format(?int $cents): ?string
    {
        if ($cents === null) {
            return null;
        }

        return '€ '.(app()->getLocale() === 'it' ? number_format($cents / 100, 2, ',', '.') : number_format($cents / 100, 2));
    }
}
