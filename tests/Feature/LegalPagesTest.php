<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    /** @return array<string, array{string, string}> */
    public static function pages(): array
    {
        return [
            'privacy' => ['/privacy', 'Privacy policy | Visciano 82'],
            'cookie' => ['/cookie', 'Cookie policy | Visciano 82'],
            'privacy en' => ['/en/privacy', 'Privacy policy | Visciano 82'],
            'cookie en' => ['/en/cookies', 'Cookie policy | Visciano 82'],
        ];
    }

    #[DataProvider('pages')]
    public function test_legal_pages_respond_with_title_and_exactly_one_h1(string $uri, string $title): void
    {
        $response = $this->get($uri)
            ->assertOk()
            ->assertSee('<title>'.$title.'</title>', false)
            ->assertSee('<meta name="description" content="', false);

        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $response->getContent()));
    }

    public function test_privacy_policy_names_the_data_controller_from_site_config(): void
    {
        config(['site.company' => 'Pizzeria Prova S.r.l.', 'site.vat' => '01234567890', 'site.email' => 'info@example.com']);

        $this->get('/privacy')
            ->assertSeeText('Titolare del trattamento')
            ->assertSeeText('Pizzeria Prova S.r.l.')
            ->assertSeeText('01234567890')
            ->assertSeeText('info@example.com')
            ->assertSeeText('Via Cadiceto, 30030 Vigonovo VE')
            ->assertSeeText('Garante');
    }

    public function test_missing_legal_data_is_shown_as_a_recognizable_placeholder(): void
    {
        config(['site.company' => null, 'site.email' => null]);

        $this->get('/privacy')->assertSeeText('dato da completare');
    }

    public function test_cookie_policy_lists_the_technical_cookies_actually_set(): void
    {
        config(['session.cookie' => 'prova-session', 'session.lifetime' => 120]);

        $this->get('/cookie')
            ->assertSeeText('prova-session')
            ->assertSeeText('XSRF-TOKEN')
            ->assertSeeText('2 ore');
    }
}
