<?php

namespace App\Support;

use App\Menu\Item;
use App\Menu\Section;
use Illuminate\Support\Carbon;

/**
 * schema.org data (JSON-LD) built from the same data as the visible page: the restaurant on
 * every page, the menu on the menu page.
 */
class StructuredData
{
    /** Tags that map to a schema.org diet. */
    private const DIETS = [
        'vegetariano' => 'https://schema.org/VegetarianDiet',
        'vegano' => 'https://schema.org/VeganDiet',
        'senza-glutine' => 'https://schema.org/GlutenFreeDiet',
    ];

    /**
     * @return array<string, mixed>
     */
    public static function restaurant(): array
    {
        $address = config('site.address_parts');

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            '@id' => url('/').'#restaurant',
            'name' => config('app.name'),
            'alternateName' => 'Mundial 82',
            'url' => url(Pages::url('home')),
            'image' => asset('brand/og-image.png'),
            'telephone' => '+39'.preg_replace('/\D/', '', config('site.phone')),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address['street'],
                'postalCode' => $address['postal_code'],
                'addressLocality' => $address['locality'],
                'addressRegion' => $address['region'],
                'addressCountry' => $address['country'],
            ],
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => config('site.geo.lat'), 'longitude' => config('site.geo.lng')],
            'hasMap' => config('site.map_url'),
            'servesCuisine' => __('site.cuisine'),
            'acceptsReservations' => true,
            'hasMenu' => url(Pages::url('menu')),
            'sameAs' => array_column(config('site.social'), 'url'),
            'openingHoursSpecification' => collect(config('site.hours'))->whereNotNull('opens')->map(fn (array $row) => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $row['day_of_week'],
                'opens' => $row['opens'],
                // schema.org has no 24:00: closing at midnight is the last minute of the day.
                'closes' => $row['closes'] === '00:00' ? '23:59' : $row['closes'],
            ])->values()->all(),
            'specialOpeningHoursSpecification' => collect(config('site.closures'))
                ->filter(fn (array $closure) => Carbon::parse($closure['al'])->endOfDay()->isFuture())
                ->map(fn (array $closure) => ['@type' => 'OpeningHoursSpecification', 'opens' => '00:00', 'closes' => '00:00', 'validFrom' => $closure['dal'], 'validThrough' => $closure['al']])
                ->values()->all(),
        ]);
    }

    /**
     * @param  list<Section>  $sections
     * @return array<string, mixed>
     */
    public static function menu(array $sections): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Menu',
            '@id' => url(Pages::url('menu')).'#menu',
            'name' => __('site.menu.h1'),
            'url' => url(Pages::url('menu')),
            'inLanguage' => app()->getLocale(),
            'hasMenuSection' => array_map(self::section(...), $sections),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function section(Section $section): array
    {
        return array_filter([
            '@type' => 'MenuSection',
            'name' => $section->name,
            'description' => $section->description,
            'hasMenuItem' => array_map(self::item(...), $section->items),
            'hasMenuSection' => array_map(self::section(...), $section->children),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private static function item(Item $item): array
    {
        $ingredients = implode(', ', array_column($item->ingredients, 'name'));

        return array_filter([
            '@type' => 'MenuItem',
            'name' => $item->name,
            'description' => $item->description ?? ($ingredients ?: null),
            'offers' => $item->price === null ? null : ['@type' => 'Offer', 'price' => number_format($item->price / 100, 2, '.', ''), 'priceCurrency' => 'EUR'],
            'suitableForDiet' => collect(self::DIETS)->only(array_keys($item->tags))->first(),
        ]);
    }

    /** The data as a JSON-LD script tag; `<` is escaped so the JSON cannot close the tag. */
    public static function script(array $data): string
    {
        return '<script type="application/ld+json">'.json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG).'</script>';
    }
}
