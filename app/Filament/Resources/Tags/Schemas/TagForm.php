<?php

namespace App\Filament\Resources\Tags\Schemas;

use App\Filament\Support\Translated;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->label('Identificativo (slug)')
                    ->helperText('Stabile: non si può cambiare dopo la creazione (es. vegano, senza-glutine, novita).')
                    ->required()
                    ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabledOn('edit'),
                Translated::tabs(fn (string $locale) => [
                    TextInput::make("name.$locale")
                        ->label('Nome')
                        ->required(Translated::isRequired($locale))
                        ->maxLength(255)
                        ->when(
                            Translated::isRequired($locale),
                            fn (TextInput $input) => $input
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, ?string $state, callable $set) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        ),
                ]),
            ]);
    }
}
