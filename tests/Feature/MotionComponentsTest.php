<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class MotionComponentsTest extends TestCase
{
    public function test_parole_wraps_each_word_with_an_increasing_delay_and_keeps_the_text(): void
    {
        $html = Blade::render('<x-parole text="Pizza napoletana fatta bene" :start="2" />');

        $this->assertSame(4, substr_count($html, 'class="parola"'));
        $this->assertStringContainsString('style="--i: 2">Pizza<', $html);
        $this->assertStringContainsString('style="--i: 5">bene<', $html);
        $this->assertSame('Pizza napoletana fatta bene', trim(preg_replace('/\s+/', ' ', strip_tags($html))));
    }

    public function test_marquee_is_a_decorative_loop_that_repeats_the_words_twice(): void
    {
        $html = Blade::render('<x-marquee :words="[\'Uno\', \'Due\']" />');

        $this->assertStringContainsString('aria-hidden="true"', $html);
        $this->assertStringContainsString('marquee-track', $html);
        $this->assertSame(2, substr_count($html, '>Uno<'));
    }

    public function test_home_has_a_single_marquee_band(): void
    {
        $this->assertSame(1, substr_count($this->get('/')->getContent(), 'marquee-track'));
    }

    public function test_button_rolls_its_label_with_css_only_and_no_pointer_gimmicks(): void
    {
        $html = Blade::render('<x-button href="/menu">Guarda il menù</x-button>');

        $this->assertStringContainsString('class="btn-text" data-label="Guarda il menù"><span>Guarda il menù</span></span>', $html);
        $this->assertSame(1, substr_count(strip_tags($html), 'Guarda il menù'));
        $this->assertStringNotContainsString('data-magnetic', $html);
    }

    public function test_pages_have_no_custom_cursor_or_tilting_cards(): void
    {
        $this->get('/menu')->assertDontSee('data-tilt', false);
        $this->assertStringNotContainsString("make('cursor')", file_get_contents(resource_path('js/app.js')));
    }

    public function test_home_has_the_scroll_driven_decorations_hidden_from_assistive_tech(): void
    {
        $this->get('/')
            ->assertSee('class="progress"', false)
            ->assertSee('marquee-track', false)
            ->assertSee('data-mouse', false)
            ->assertSee('class="curtain"', false);
    }

    public function test_story_pins_the_dough_facts_while_keeping_them_as_a_readable_list(): void
    {
        $this->get('/la-nostra-storia')
            ->assertSee('pin-track', false)
            ->assertSeeInOrder(['Almeno 2 giorni', 'di lievitazione', 'Alta idratazione', 'Alta digeribilità']);
    }

    public function test_only_the_home_page_declares_the_intro_curtain(): void
    {
        $this->get('/')->assertSee('<html lang="it" data-home', false);
        $this->get('/menu')->assertDontSee('<html lang="it" data-home', false);
    }
}
