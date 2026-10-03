<?php

namespace App\Support;

class Pages
{
    /** Relative URL of a page in a locale (default: the current one). */
    public static function url(string $page, ?string $locale = null): string
    {
        return config('site.pages.'.$page.'.uri.'.($locale ?? app()->getLocale()));
    }

    /** Key of the page being served (e.g. "menu"), taken from the route name ("it.menu"). */
    public static function current(): ?string
    {
        $name = request()->route()?->getName();

        return $name === null ? null : substr($name, strpos($name, '.') + 1);
    }

    /**
     * Relative URL of the current page in every locale.
     *
     * @return array<string, string>
     */
    public static function alternates(): array
    {
        $page = self::current();

        return $page === null ? [] : collect(config('app.locales'))
            ->mapWithKeys(fn (string $locale) => [$locale => self::url($page, $locale)])
            ->all();
    }
}
