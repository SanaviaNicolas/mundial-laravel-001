<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Filament\Support\Translated;
use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(self::fields(withParent: true));
    }

    /**
     * Fields of a category. The parent is left out when the form is used from a macro category,
     * which sets it by itself.
     *
     * @return array<int, mixed>
     */
    public static function fields(bool $withParent): array
    {
        return [
            ...($withParent ? [self::parent()] : []),
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
                Textarea::make("description.$locale")
                    ->label('Descrizione')
                    ->helperText('Facoltativa. Es. "con bordo alto".')
                    ->rows(2),
            ]),
            TextInput::make('slug')
                ->label('Identificativo nell\'indirizzo (slug)')
                ->helperText('Lettere minuscole, numeri e trattini. Si propone dal nome.')
                ->required()
                ->regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Toggle::make('is_visible')
                ->label('Visibile sul sito')
                ->helperText('Nascondendo una macrocategoria si nascondono anche le sue sottocategorie.')
                ->default(true),
        ];
    }

    private static function parent(): Select
    {
        return Select::make('parent_id')
            ->label('Macrocategoria')
            ->options(fn (?Category $record) => Category::query()
                ->whereNull('parent_id')
                ->when($record, fn ($query) => $query->whereKeyNot($record->getKey()))
                ->ordered()
                ->get()
                ->mapWithKeys(fn (Category $category) => [$category->id => $category->translate('name')])
                ->all())
            ->placeholder('Nessuna: è una macrocategoria')
            ->helperText('Le categorie hanno al massimo due livelli. Una macrocategoria che ha sottocategorie non può diventare sottocategoria.')
            ->disabled(fn (?Category $record) => $record?->children()->exists() ?? false);
    }
}
