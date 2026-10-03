<?php

namespace App\Filament\Support;

use App\Models\Allergen;
use Filament\Forms\Components\CheckboxList;
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
}
