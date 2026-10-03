<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    private function adminPath(): string
    {
        return '/'.config('admin.path');
    }

    public function test_admin_path_is_configured_and_not_a_well_known_default(): void
    {
        $this->assertNotEmpty(config('admin.path'));
        $this->assertNotContains(config('admin.path'), ['admin', 'cms', 'login', 'dashboard']);
    }

    public function test_admin_redirects_guests_to_custom_login_slug(): void
    {
        $this->get($this->adminPath())->assertRedirect(url($this->adminPath().'/accesso'));
    }

    public function test_default_admin_urls_do_not_exist(): void
    {
        $this->get('/admin')->assertNotFound();
        $this->get($this->adminPath().'/login')->assertNotFound();
    }

    public function test_admin_is_not_indexable_even_in_production(): void
    {
        $this->app['env'] = 'production';

        $this->get($this->adminPath().'/accesso')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow');
    }
}
