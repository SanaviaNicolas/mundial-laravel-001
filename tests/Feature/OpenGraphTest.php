<?php

namespace Tests\Feature;

use Tests\TestCase;

class OpenGraphTest extends TestCase
{
    public function test_pages_have_open_graph_and_twitter_tags_matching_title_description_and_canonical(): void
    {
        $this->get('/menu')
            ->assertSee('<meta property="og:type" content="website">', false)
            ->assertSee('<meta property="og:site_name" content="Visciano 82">', false)
            ->assertSee('<meta property="og:title" content="Menù — pizze, panuozzi e cucina | Visciano 82">', false)
            ->assertSee('<meta property="og:description" content="Il menù di Visciano 82:', false)
            ->assertSee('<meta property="og:url" content="'.url('/menu').'">', false)
            ->assertSee('<meta property="og:locale" content="it_IT">', false)
            ->assertSee('<meta property="og:locale:alternate" content="en_GB">', false)
            ->assertSee('<meta property="og:image" content="'.asset('brand/og-image.png').'">', false)
            ->assertSee('<meta property="og:image:width" content="1200">', false)
            ->assertSee('<meta property="og:image:height" content="630">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);

        $this->get('/en/menu')
            ->assertSee('<meta property="og:locale" content="en_GB">', false)
            ->assertSee('<meta property="og:locale:alternate" content="it_IT">', false);
    }

    public function test_the_share_image_exists_with_the_declared_size(): void
    {
        $this->assertSame([1200, 630], array_slice(getimagesize(public_path('brand/og-image.png')), 0, 2));
    }
}
