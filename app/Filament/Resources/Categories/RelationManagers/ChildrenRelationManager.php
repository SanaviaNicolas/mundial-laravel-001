<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Support\Translated;
use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $title = 'Sottocategorie';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof Category && $ownerRecord->parent_id === null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components(CategoryForm::fields(withParent: false));
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('slug')->label('Slug'),
                TextColumn::make('items_count')->label('Voci')->counts('items'),
                IconColumn::make('is_visible')->label('Visibile')->boolean(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()->label('Nuova sottocategoria'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('open')
                    ->label('Voci')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Category $record) => CategoryResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
