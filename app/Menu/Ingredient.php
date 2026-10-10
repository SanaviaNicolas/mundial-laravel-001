<?php

namespace App\Menu;

/** An ingredient line of an item; frozen ones get an asterisk and the page a legend. */
final readonly class Ingredient
{
    public function __construct(
        public string $name,
        public bool $frozen = false,
        public bool $afterCooking = false,
        public ?string $section = null,
    ) {}
}
