<?php

namespace App\Filament\Resources\Addons\Tables;

use App\Filament\Support\Translated;
use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AddonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['categories', 'allergens']))
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('price_cents')->label('Supplemento')->money('EUR', divideBy: 100),
                TextColumn::make('categories')
                    ->label('Categorie')
                    ->state(fn (Addon $record) => $record->categories->map(fn (Category $category) => $category->pathLabel())->all())
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('allergens')
                    ->label('Allergeni')
                    ->state(fn (Addon $record) => $record->allergens->map(fn (Allergen $allergen) => $allergen->translate('name'))->all())
                    ->badge()
                    ->placeholder('—'),
                IconColumn::make('is_visible')->label('Visibile')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->filters([
                TernaryFilter::make('is_visible')->label('Visibile'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
