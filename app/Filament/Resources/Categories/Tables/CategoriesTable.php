<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Filament\Support\Translated;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('parent_id'))
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('children_count')->label('Sottocategorie')->counts('children'),
                TextColumn::make('items_count')->label('Voci')->counts('items'),
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
