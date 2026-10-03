<?php

namespace App\Models\Concerns;

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
}
