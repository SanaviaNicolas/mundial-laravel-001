<?php

namespace App\Http\Controllers;

use App\Support\Pages;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /** Every page in every language, each with its hreflang alternates (same data as the routes). */
    public function __invoke(): Response
    {
        $locales = config('app.locales');
        $urls = [];

        foreach (array_keys(config('site.pages')) as $page) {
            $links = array_map(fn (string $locale) => ['hreflang' => $locale, 'href' => url(Pages::url($page, $locale))], $locales);
            $links[] = ['hreflang' => 'x-default', 'href' => url(Pages::url($page, $locales[0]))];

            foreach ($locales as $locale) {
                $urls[] = ['loc' => url(Pages::url($page, $locale)), 'links' => $links];
            }
        }

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
