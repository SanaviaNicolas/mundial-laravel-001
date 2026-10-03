<?php

namespace App\Filament\Resources\Addons;

use App\Filament\Resources\Addons\Pages\CreateAddon;
use App\Filament\Resources\Addons\Pages\EditAddon;
use App\Filament\Resources\Addons\Pages\ListAddons;
use App\Filament\Resources\Addons\Schemas\AddonForm;
use App\Filament\Resources\Addons\Tables\AddonsTable;
use App\Filament\Resources\MenuResource;
use App\Models\Addon;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AddonResource extends MenuResource
{
    protected static ?string $model = Addon::class;

    protected static ?string $modelLabel = 'aggiunta';

    protected static ?string $pluralModelLabel = 'aggiunte';

    protected static ?int $navigationSort = 40;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    public static function form(Schema $schema): Schema
    {
        return AddonForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAddons::route('/'),
            'create' => CreateAddon::route('/create'),
            'edit' => EditAddon::route('/{record}/edit'),
        ];
    }
}
