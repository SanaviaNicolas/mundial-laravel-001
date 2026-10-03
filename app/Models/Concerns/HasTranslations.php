<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

/**
 * Translatable attributes stored as a jsonb map {"it": "...", "en": "..."}.
 *
 * Models declare `protected array $translatable = ['name', ...]`.
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(array_fill_keys($this->translatable, 'array'));
    }

    /**
     * Text in the given (or current) locale, falling back to the fallback locale.
     */
    public function translate(string $attribute, ?string $locale = null): ?string
    {
        $translations = $this->getAttribute($attribute) ?? [];

        foreach ([$locale ?? app()->getLocale(), config('app.fallback_locale')] as $candidate) {
            $text = trim((string) ($translations[$candidate] ?? ''));

            if ($text !== '') {
                return $text;
            }
        }

        return null;
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeWhereTranslationLike(Builder $query, string $attribute, string $term, ?string $locale = null): void
    {
        $query->whereRaw(
            $this->translationExpression($attribute).' ILIKE ?',
            [...$this->translationBindings($locale), '%'.addcslashes($term, '\\%_').'%'],
        );
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrderByTranslation(Builder $query, string $attribute, string $direction = 'asc', ?string $locale = null): void
    {
        $query->orderByRaw(
            $this->translationExpression($attribute).' '.(strtolower($direction) === 'desc' ? 'DESC' : 'ASC'),
            $this->translationBindings($locale),
        );
    }

    /**
     * SQL text of an attribute in the given locale, falling back to the fallback locale.
     */
    private function translationExpression(string $attribute): string
    {
        if (! in_array($attribute, $this->translatable, true)) {
            throw new InvalidArgumentException("[$attribute] is not a translatable attribute.");
        }

        $column = $this->getConnection()->getQueryGrammar()->wrap($this->qualifyColumn($attribute));

        return "COALESCE(NULLIF(TRIM($column->>?), ''), TRIM($column->>?))";
    }

    /**
     * @return list<string>
     */
    private function translationBindings(?string $locale): array
    {
        return [$locale ?? app()->getLocale(), config('app.fallback_locale')];
    }
}
