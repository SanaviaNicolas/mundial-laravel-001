<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\Concerns\FakesPhotos;
use Tests\TestCase;

class FotoComponentTest extends TestCase
{
    use FakesPhotos;

    private const NAME = 'prova-foto-test';

    public function test_it_shows_a_labelled_placeholder_when_the_image_files_are_missing(): void
    {
        $html = Blade::render('<x-foto name="'.self::NAME.'" alt="Una pizza" />');

        $this->assertStringContainsString('Foto provvisoria', $html);
        $this->assertStringNotContainsString('<picture', $html);
    }

    public function test_it_renders_a_responsive_picture_when_the_image_files_exist(): void
    {
        $this->fakePhoto(self::NAME);

        $html = Blade::render('<x-foto name="'.self::NAME.'" alt="Una pizza" :eager="true" />');

        $this->assertStringContainsString('<picture', $html);
        $this->assertStringContainsString('type="image/avif"', $html);
        $this->assertStringContainsString('type="image/webp"', $html);
        $this->assertStringContainsString('images/'.self::NAME.'-640.avif 640w', $html);
        $this->assertStringContainsString('alt="Una pizza"', $html);
        $this->assertStringContainsString('fetchpriority="high"', $html);
        $this->assertStringNotContainsString('Foto provvisoria', $html);
    }

    public function test_it_lazy_loads_by_default(): void
    {
        $this->fakePhoto(self::NAME, ['webp']);

        $this->assertStringContainsString('loading="lazy"', Blade::render('<x-foto name="'.self::NAME.'" alt="" />'));
    }
}
