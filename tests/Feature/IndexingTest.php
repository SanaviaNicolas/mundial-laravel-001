<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexingTest extends TestCase
{
    public function test_non_production_pages_are_noindex(): void
    {
        $this->app['env'] = 'staging';

        $this->get('/')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_production_pages_are_indexable(): void
    {
        $this->app['env'] = 'production';

        $this->get('/')
            ->assertHeaderMissing('X-Robots-Tag')
            ->assertDontSee('name="robots"', false);
    }

    public function test_robots_txt_in_production_points_to_sitemap_and_hides_admin(): void
    {
        $this->app['env'] = 'production';

        $response = $this->get('/robots.txt')->assertOk();

        $this->assertStringStartsWith('text/plain', $response->headers->get('Content-Type'));
        $response->assertSeeText('User-agent: *')
            ->assertSeeText('Disallow: /admin')
            ->assertSeeText('Sitemap: '.url('/sitemap.xml'));
        $this->assertDoesNotMatchRegularExpression('#^Disallow: /$#m', $response->getContent());
    }

    public function test_robots_txt_outside_production_blocks_everything(): void
    {
        $this->app['env'] = 'staging';

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSeeText('User-agent: *')
            ->assertSeeText('Disallow: /')
            ->assertDontSeeText('Sitemap:');
    }
}
