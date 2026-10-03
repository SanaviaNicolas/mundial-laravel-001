<?php

namespace App\Filament\Resources\Ingredients\Pages;

use App\Filament\Resources\Ingredients\IngredientResource;
use App\Models\Ingredient;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditIngredient extends EditRecord
{
    protected static string $resource = IngredientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->hidden(fn (Ingredient $record) => $record->menuItems()->exists()),
        ];
    }
}
