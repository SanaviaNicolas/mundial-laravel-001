<?php

namespace App\Filament\Resources\Addons\Schemas;

use App\Filament\Support\Fields;
use App\Filament\Support\Translated;
use App\Models\Category;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddonForm
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
                Fields::price('price_cents', 'Supplemento di prezzo', required: true)
                    ->helperText('Anche 0 per le scelte senza supplemento (es. senza lattosio).'),
                Toggle::make('is_visible')->label('Visibile sul sito')->default(true),
                CheckboxList::make('categories')
                    ->label('Vale di default per tutte le voci di queste categorie')
                    ->helperText('Scegliendo una macrocategoria vale anche per le sue sottocategorie. Da una singola voce si può poi escludere.')
                    ->relationship('categories', 'id')
                    ->options(fn () => Category::pathOptions())
                    ->columns(2)
                    ->columnSpanFull(),
                Fields::allergens('Allergeni che l\'aggiunta porta con sé')
                    ->helperText('Il sito li mostra a parte ("con questa aggiunta contiene…"), senza unirli agli allergeni della voce.')
                    ->columnSpanFull(),
            ]);
    }
}
