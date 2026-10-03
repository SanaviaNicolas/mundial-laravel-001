<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use App\Filament\Support\Fields;
use App\Filament\Support\Translated;
use App\Models\Addon;
use App\Models\Allergen;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\MenuItem;
use App\Models\Tag;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Voce')->schema([
                    Select::make('category_id')
                        ->label('Categoria')
                        ->options(fn () => Category::pathOptions())
                        ->searchable()
                        ->required()
                        ->live(),
                    Translated::tabs(fn (string $locale) => [
                        TextInput::make("name.$locale")
                            ->label('Nome')
                            ->required(Translated::isRequired($locale))
                            ->maxLength(255),
                        Textarea::make("description.$locale")
                            ->label('Descrizione')
                            ->rows(2),
                        TextInput::make("notes.$locale")
                            ->label('Note')
                            ->helperText('Es. riconoscimenti, "base fritta", "mezza pizza e mezzo calzone".')
                            ->maxLength(255),
                    ]),
                    Fields::price('price_cents', 'Prezzo')
                        ->helperText('Lascia vuoto se il prezzo non è ancora noto: sul sito non comparirà.'),
                    Toggle::make('is_visible')->label('Visibile sul sito')->default(true),
                ])->columns(2),

                Section::make('Ingredienti')->schema([
                    Repeater::make('ingredientLines')
                        ->label('Ingredienti, nell\'ordine in cui compaiono')
                        ->relationship()
                        ->orderColumn('sort_order')
                        ->defaultItems(0)
                        ->addActionLabel('Aggiungi ingrediente')
                        ->schema([
                            Select::make('ingredient_id')
                                ->label('Ingrediente')
                                ->options(fn () => self::ingredientOptions())
                                ->searchable()
                                ->required()
                                ->helperText('Manca un ingrediente? Crealo prima in "Ingredienti", con i suoi allergeni.'),
                            Toggle::make('after_cooking')->label('A fine cottura / dopo la cottura'),
                            Grid::make(2)->schema([
                                TextInput::make('section.it')->label('Sezione (italiano)')->maxLength(100),
                                TextInput::make('section.en')->label('Sezione (inglese)')->maxLength(100),
                            ])->columnSpanFull(),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(fn (array $data) => self::cleanSection($data))
                        ->mutateRelationshipDataBeforeSaveUsing(fn (array $data) => self::cleanSection($data))
                        ->helperText('La "sezione" serve solo per le voci in due parti (es. "Mezza pizza" / "Mezzo panuozzo"): gli ingredienti con la stessa sezione vengono raggruppati.')
                        ->columnSpanFull(),
                ]),

                Section::make('Allergeni')->schema([
                    Fields::allergens('Allergeni diretti della voce')
                        ->helperText('Solo per ciò che non viene dagli ingredienti: bevande, glutine dell\'impasto, ecc.')
                        ->columnSpanFull(),
                    Placeholder::make('effective_allergens')
                        ->label('Allergeni effettivi (ingredienti + diretti)')
                        ->content(fn (?MenuItem $record) => self::allergenSummary($record))
                        ->hidden(fn (string $operation) => $operation === 'create')
                        ->columnSpanFull(),
                ]),

                Section::make('Aggiunte')->schema([
                    Placeholder::make('effective_addons')
                        ->label('Aggiunte valide per questa voce')
                        ->content(fn (?MenuItem $record) => self::addonSummary($record))
                        ->hidden(fn (string $operation) => $operation === 'create')
                        ->columnSpanFull(),
                    CheckboxList::make('excludedAddons')
                        ->label('Escludi queste aggiunte ereditate dalla categoria')
                        ->relationship('excludedAddons', 'id')
                        ->options(fn (Get $get) => self::addonOptions(Addon::query()->forCategory(self::categoryId($get))))
                        ->columns(2),
                    Select::make('extraAddons')
                        ->label('Aggiunte extra solo per questa voce')
                        ->multiple()
                        ->relationship('extraAddons', 'id')
                        ->options(fn (Get $get) => self::addonOptions(
                            Addon::query()->whereNotIn('addons.id', Addon::query()->forCategory(self::categoryId($get))->select('addons.id')),
                        )),
                ]),

                Section::make('Tag')->schema([
                    Select::make('tags')
                        ->label('Tag')
                        ->multiple()
                        ->relationship('tags', 'id')
                        ->options(fn () => Tag::query()->orderBy('id')->get()->mapWithKeys(fn (Tag $tag) => [$tag->id => $tag->translate('name')])->all())
                        ->preload(),
                ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function ingredientOptions(): array
    {
        return Ingredient::query()->get()
            ->mapWithKeys(fn (Ingredient $ingredient) => [$ingredient->id => $ingredient->translate('name')])
            ->sort()
            ->all();
    }

    /**
     * @param  Builder<Addon>  $query
     * @return array<int, string>
     */
    private static function addonOptions($query): array
    {
        return $query->ordered()->get()
            ->mapWithKeys(fn (Addon $addon) => [$addon->id => $addon->translate('name')])
            ->all();
    }

    private static function categoryId(Get $get): ?int
    {
        $id = $get('category_id');

        return $id === null || $id === '' ? null : (int) $id;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function cleanSection(array $data): array
    {
        $data['section'] = array_filter($data['section'] ?? [], fn ($text) => filled($text)) ?: null;

        return $data;
    }

    private static function allergenSummary(?MenuItem $record): string
    {
        if ($record === null) {
            return '';
        }

        $record = $record->fresh();
        $names = $record->effectiveAllergens()
            ->map(fn (Allergen $allergen) => $allergen->translate('name'))
            ->implode(', ');
        $list = $names === '' ? 'nessun allergene elencato' : $names;
        $status = $record->isAllergensVerified()
            ? 'Verificati il '.$record->allergens_verified_at->format('d/m/Y H:i').': il sito li mostra.'
            : 'Non verificati: il sito non mostra gli allergeni e invita a chiedere al personale. Un elenco vuoto non significa "nessun allergene".';

        return "$list — $status";
    }

    private static function addonSummary(?MenuItem $record): string
    {
        if ($record === null) {
            return '';
        }

        /** @var Collection<int, Addon> $addons */
        $addons = $record->effectiveAddons(visibleOnly: false);

        return $addons->isEmpty()
            ? 'Nessuna'
            : $addons->map(fn (Addon $addon) => $addon->translate('name').' (+ € '.number_format($addon->price_cents / 100, 2, ',', '.').')'.($addon->is_visible ? '' : ' — nascosta'))->implode(', ');
    }
}
