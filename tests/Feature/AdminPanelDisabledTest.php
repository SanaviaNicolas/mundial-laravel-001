<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminPanelDisabledTest extends TestCase
{
    private string|false $originalAdminPath;

    protected function setUp(): void
    {
        $this->originalAdminPath = getenv('ADMIN_PATH');
        putenv('ADMIN_PATH=');
        $_ENV['ADMIN_PATH'] = $_SERVER['ADMIN_PATH'] = '';

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        putenv('ADMIN_PATH='.$this->originalAdminPath);
        $_ENV['ADMIN_PATH'] = $_SERVER['ADMIN_PATH'] = $this->originalAdminPath;
    }

    public function test_panel_is_disabled_when_admin_path_is_missing(): void
    {
        $this->assertEmpty(config('admin.path'));

        foreach (['/admin', '/admin/login', '/login', '/accesso'] as $url) {
            $this->get($url)->assertNotFound();
        }
    }
}
