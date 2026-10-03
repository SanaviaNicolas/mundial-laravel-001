<?php

namespace App\Filament\Resources\MenuItems\Tables;

use App\Filament\Resources\MenuItems\Actions\AllergenVerification;
use App\Filament\Support\Translated;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Tag;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['category.parent', 'tags']))
            ->columns([
                Translated::column('name', 'Nome'),
                TextColumn::make('category')
                    ->label('Categoria')
                    ->state(fn (MenuItem $record) => $record->category->pathLabel()),
                TextColumn::make('price_cents')->label('Prezzo')->money('EUR', divideBy: 100)->placeholder('—')->sortable(),
                TextColumn::make('tags')
                    ->label('Tag')
                    ->state(fn (MenuItem $record) => $record->tags->map(fn (Tag $tag) => $tag->translate('name'))->all())
                    ->badge(),
                IconColumn::make('allergens_verified')
                    ->label('Allergeni verificati')
                    ->state(fn (MenuItem $record) => $record->isAllergensVerified())
                    ->boolean(),
                IconColumn::make('is_visible')->label('Visibile')->boolean(),
            ])
            ->defaultSort('id')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Categoria')
                    ->options(fn () => Category::pathOptions())
                    ->query(fn (Builder $query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereIn('category_id', Category::query()->whereKey($data['value'])->orWhere('parent_id', $data['value'])->select('id'))
                        : $query),
                SelectFilter::make('tag')
                    ->label('Tag')
                    ->options(fn () => Tag::query()->orderBy('id')->get()->mapWithKeys(fn (Tag $tag) => [$tag->id => $tag->translate('name')])->all())
                    ->query(fn (Builder $query, array $data) => filled($data['value'] ?? null)
                        ? $query->whereHas('tags', fn (Builder $tags) => $tags->whereKey($data['value']))
                        : $query),
                TernaryFilter::make('is_visible')->label('Visibile'),
                TernaryFilter::make('allergens_verified_at')
                    ->label('Allergeni')
                    ->nullable()
                    ->placeholder('Tutti')
                    ->trueLabel('Verificati')
                    ->falseLabel('Da verificare'),
            ])
            ->recordActions([
                EditAction::make(),
                AllergenVerification::verify(),
            ]);
    }
}
