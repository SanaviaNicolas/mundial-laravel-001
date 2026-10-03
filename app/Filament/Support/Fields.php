<?php

namespace App\Filament\Support;

use App\Models\Allergen;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

/**
 * Form fields shared by several admin resources.
 */
class Fields
{
    /**
     * The 14 EU allergens as checkboxes bound to an `allergens` BelongsToMany relationship.
     */
    public static function allergens(string $label = 'Allergeni'): CheckboxList
    {
        return CheckboxList::make('allergens')
            ->label($label)
            ->relationship(
                'allergens',
                modifyQueryUsing: fn (Builder $query) => $query->orderBy('allergens.sort_order'),
            )
            ->getOptionLabelFromRecordUsing(fn (Allergen $allergen) => $allergen->translate('name'))
            ->columns(2);
    }

    /**
     * Price typed in euros, stored in cents (null when left empty and not required).
     */
    public static function price(string $name, string $label, bool $required = false): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->minValue(0)
            ->step(0.01)
            ->prefix('€')
            ->required($required)
            ->formatStateUsing(fn ($state) => $state === null ? null : $state / 100)
            ->dehydrateStateUsing(fn ($state) => blank($state) ? null : (int) round((float) $state * 100));
    }
}
