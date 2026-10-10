<?php

namespace App\Menu;

/**
 * Optional extra with a supplement in cents (0 = no supplement). Its allergens are shown apart
 * ("with this addon it contains...") and an empty list does NOT mean "no allergens".
 */
final readonly class Addon
{
    /**
     * @param  list<string>  $allergens
     */
    public function __construct(
        public string $name,
        public int $price,
        public array $allergens = [],
    ) {}
}
