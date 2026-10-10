<?php

namespace Tests\Feature;

use Tests\TestCase;

class StoriaPageTest extends TestCase
{
    public function test_storia_responds_ok_with_title_and_description(): void
    {
        $this->get('/la-nostra-storia')
            ->assertOk()
            ->assertSee('<title>La nostra storia — pizza napoletana di famiglia | Visciano 82</title>', false)
            ->assertSee('<meta name="description" content="', false);
    }

    public function test_storia_has_exactly_one_h1(): void
    {
        $this->assertSame(1, preg_match_all('/<h1[\s>]/i', $this->get('/la-nostra-storia')->getContent()));
    }

    public function test_storia_explains_the_dough(): void
    {
        $this->get('/la-nostra-storia')
            ->assertSee('lievitazione')
            ->assertSee('idratazione')
            ->assertSee('digeribilità');
    }

    public function test_storia_does_not_show_todo_markers(): void
    {
        $this->get('/la-nostra-storia')->assertDontSee('TODO');
    }
}
