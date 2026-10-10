<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExternalLinksTest extends TestCase
{
    public function test_every_external_link_opens_in_a_new_tab_safely(): void
    {
        foreach (array_keys(config('site.pages')) as $page) {
            foreach (config('app.locales') as $locale) {
                $html = $this->get(config("site.pages.$page.uri.$locale"))->getContent();

                preg_match_all('/<a\s[^>]*href="https?:\/\/(?!'.preg_quote(parse_url(config('app.url'), PHP_URL_HOST), '/').')[^"]*"[^>]*>/', $html, $links);

                foreach ($links[0] as $link) {
                    $this->assertStringContainsString('target="_blank"', $link, "$page/$locale: $link");
                    $this->assertMatchesRegularExpression('/rel="[^"]*noopener/', $link, "$page/$locale: $link");
                }
            }
        }
    }

    public function test_screen_readers_are_told_that_a_new_tab_opens(): void
    {
        $this->get('/contatti')
            ->assertSee('aria-label="Instagram (si apre in una nuova scheda)"', false)
            ->assertSeeText('(si apre in una nuova scheda)');
    }
}
