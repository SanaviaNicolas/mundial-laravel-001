<?php

namespace Tests\Feature;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_sitemap_lists_every_page_in_every_language_with_its_alternates(): void
    {
        $response = $this->get('/sitemap.xml')->assertOk();

        $this->assertStringStartsWith('application/xml', $response->headers->get('Content-Type'));
        $xml = simplexml_load_string($response->getContent());
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->registerXPathNamespace('x', 'http://www.w3.org/1999/xhtml');
        $locs = array_map('strval', $xml->xpath('//s:url/s:loc'));

        foreach (config('site.pages') as $page) {
            foreach ($page['uri'] as $uri) {
                $this->assertContains(url($uri), $locs);
            }
        }

        $menu = $xml->xpath('//s:url[s:loc="'.url('/menu').'"]/x:link');
        $this->assertSame(['it', 'en', 'x-default'], array_map(fn ($link) => (string) $link['hreflang'], $menu));
        $this->assertSame(url('/en/menu'), (string) $menu[1]['href']);
    }
}
