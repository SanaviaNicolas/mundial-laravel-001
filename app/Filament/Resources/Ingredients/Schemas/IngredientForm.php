<?php

namespace App\Filament\Resources\Ingredients\Schemas;

use App\Filament\Support\Fields;
use App\Filament\Support\Translated;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class IngredientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Translated::tabs(fn (string $locale) => [
                    TextInput::make("name.$locale")
                        ->label('Nome')
                        ->required(Translated::isRequired($locale))
                        ->maxLength(255),
                ]),
                Toggle::make('is_frozen')
                    ->label('Surgelato')
                    ->helperText('Sul sito compare un asterisco accanto all\'ingrediente, con la legenda dei surgelati.'),
                Fields::allergens('Allergeni contenuti nell\'ingrediente')
                    ->helperText('Se cambi questi allergeni, la verifica degli allergeni di tutte le voci che usano l\'ingrediente viene azzerata.')
                    ->columnSpanFull(),
            ]);
    }
}
