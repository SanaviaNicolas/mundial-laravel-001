<?php

namespace App\Filament\Support;

use App\Models\TranslatableModel;
use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

/**
 * Building blocks for translatable (jsonb) attributes in Filament forms and tables.
 */
class Translated
{
    private const LANGUAGES = ['it' => 'Italiano', 'en' => 'Inglese'];

    /**
     * One tab per site language. The callback receives the locale and returns the fields of
     * that tab, named "attribute.locale" (e.g. "name.it"); the Italian ones are the required ones.
     *
     * @param  Closure(string): array<int, mixed>  $fields
     */
    public static function tabs(Closure $fields): Tabs
    {
        return Tabs::make('translations')
            ->tabs(array_map(
                fn (string $locale) => Tab::make(self::LANGUAGES[$locale] ?? strtoupper($locale))->schema($fields($locale)),
                config('app.locales'),
            ))
            ->columnSpanFull();
    }

    /**
     * Whether a field of the given locale is mandatory (Italian is the source language).
     */
    public static function isRequired(string $locale): bool
    {
        return $locale === config('app.fallback_locale');
    }

    /**
     * Text column showing the current-language value (with Italian fallback), searchable and sortable.
     */
    public static function column(string $attribute, string $label): TextColumn
    {
        return TextColumn::make($attribute)
            ->label($label)
            ->state(fn (TranslatableModel $record) => $record->translate($attribute))
            ->searchable(query: fn (Builder $query, string $search) => self::search($query, $attribute, $search))
            ->sortable(query: fn (Builder $query, string $direction) => self::sort($query, $attribute, $direction));
    }

    /**
     * @param  Builder<TranslatableModel>  $query
     * @return Builder<TranslatableModel>
     */
    private static function search(Builder $query, string $attribute, string $search): Builder
    {
        return $query->whereTranslationLike($attribute, $search);
    }

    /**
     * @param  Builder<TranslatableModel>  $query
     * @return Builder<TranslatableModel>
     */
    private static function sort(Builder $query, string $attribute, string $direction): Builder
    {
        return $query->orderByTranslation($attribute, $direction);
    }
}
