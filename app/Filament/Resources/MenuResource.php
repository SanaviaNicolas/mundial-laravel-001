<?php

namespace App\Filament\Resources;

use App\Models\TranslatableModel;
use Filament\Resources\Resource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

/**
 * Base for the admin resources of the menu: same navigation group and a record title
 * taken from the translated name.
 */
abstract class MenuResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Menù';

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return $record instanceof TranslatableModel ? $record->translate('name') : null;
    }
}
