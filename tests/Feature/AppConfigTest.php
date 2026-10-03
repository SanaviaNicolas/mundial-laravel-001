<?php

namespace Tests\Feature;

use Tests\TestCase;

class AppConfigTest extends TestCase
{
    public function test_app_uses_italian_locale_and_timezone(): void
    {
        $this->assertSame('it', config('app.locale'));
        $this->assertSame('Europe/Rome', config('app.timezone'));
    }

    public function test_tests_run_against_the_dedicated_postgres_database(): void
    {
        $this->assertSame('pgsql', config('database.default'));
        $this->assertSame('mundial_testing', config('database.connections.pgsql.database'));
    }

    public function test_site_languages_are_italian_and_english(): void
    {
        $this->assertSame(['it', 'en'], config('app.locales'));
    }
}
