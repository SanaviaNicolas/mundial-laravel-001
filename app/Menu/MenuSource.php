<?php

namespace App\Menu;

/**
 * Where the public menu comes from: the static config (until the admin is filled in) or the
 * database managed in Filament. Both return the same contract, already in the current locale,
 * filtered for visibility, ordered and without empty sections (docs/frontend/dati-menu.md).
 */
interface MenuSource
{
    /**
     * @return list<Section>
     */
    public function sections(): array;
}
