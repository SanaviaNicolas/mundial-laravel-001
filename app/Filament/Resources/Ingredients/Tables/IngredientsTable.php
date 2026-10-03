<?php

namespace App\Filament\Resources\Ingredients\Tables;

use App\Filament\Support\Translated;
use App\Models\Allergen;
use App\Models\Ingredient;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class IngredientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Translated::column('name', 'Nome'),
                IconColumn::make('is_frozen')->label('Surgelato')->boolean(),
                TextColumn::make('allergens')
                    ->label('Allergeni')
                    ->state(fn (Ingredient $record) => $record->allergens->sortBy('sort_order')
                        ->map(fn (Allergen $allergen) => $allergen->translate('name'))->all())
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('menu_items_count')->label('Voci')->counts('menuItems'),
            ])
            ->defaultSort('id')
            ->filters([
                TernaryFilter::make('is_frozen')->label('Surgelato'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with('allergens'));
    }
}
