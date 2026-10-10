<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReviewsTest extends TestCase
{
    public function test_without_quotes_the_home_only_invites_to_read_the_google_reviews(): void
    {
        config(['site.reviews' => []]);

        $html = $this->get('/')->assertSeeText('Dicono di noi')->assertSeeText('Leggi le recensioni su Google')->getContent();

        $this->assertStringContainsString('href="'.e(config('site.map_url')).'" target="_blank" rel="noopener"', $html);
        $this->assertStringNotContainsString('<blockquote', $html);
    }

    public function test_real_quotes_are_shown_with_author_and_source(): void
    {
        config(['site.reviews' => [
            ['testo' => ['it' => 'Pizza buonissima.', 'en' => 'Great pizza.'], 'autore' => 'Maria R.', 'fonte' => 'Google'],
        ]]);

        $this->get('/')
            ->assertSee('<blockquote', false)
            ->assertSeeText('Pizza buonissima.')
            ->assertSeeText('Maria R.')
            ->assertSeeText('Google');

        $this->get('/en')->assertSeeText('Great pizza.')->assertSeeText('What people say');
    }

    public function test_the_site_ships_without_invented_reviews(): void
    {
        $this->assertSame([], (require config_path('site.php'))['reviews']);
    }
}
