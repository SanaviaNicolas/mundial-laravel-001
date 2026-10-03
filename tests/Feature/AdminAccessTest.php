<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    public function test_any_authenticated_user_can_open_the_panel_even_in_production(): void
    {
        $this->app['env'] = 'production';

        $this->actingAs(User::factory()->create())
            ->get('/'.config('admin.path'))
            ->assertOk();
    }

    public function test_guests_cannot_open_the_panel(): void
    {
        $this->get('/'.config('admin.path'))->assertRedirect();
    }
}
