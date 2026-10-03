<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Support\Translated;
use App\Models\MenuItem;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Voci';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('price_cents')->label('Prezzo')->money('EUR', divideBy: 100)->placeholder('—'),
                IconColumn::make('allergens_verified')
                    ->label('Allergeni verificati')
                    ->state(fn (MenuItem $record) => $record->isAllergensVerified())
                    ->boolean(),
                IconColumn::make('is_visible')->label('Visibile')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->headerActions([
                Action::make('add')->label('Nuova voce')->url(MenuItemResource::getUrl('create')),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Modifica')
                    ->icon('heroicon-o-pencil-square')
                    ->url(fn (MenuItem $record) => MenuItemResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
