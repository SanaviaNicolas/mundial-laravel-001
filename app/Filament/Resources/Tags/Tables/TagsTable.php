<?php

namespace App\Filament\Resources\Tags\Tables;

use App\Filament\Support\Translated;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('slug')->label('Slug')->searchable(),
                TextColumn::make('items_count')->label('Voci')->counts('items'),
            ])
            ->defaultSort('id')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
