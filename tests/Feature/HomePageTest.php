<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_responds_ok(): void
    {
        $this->get('/')->assertOk();
    }
}
