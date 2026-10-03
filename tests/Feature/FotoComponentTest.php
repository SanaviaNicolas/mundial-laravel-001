<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class FotoComponentTest extends TestCase
{
    private const NAME = 'prova-foto-test';

    protected function setUp(): void
    {
        parent::setUp();

        // public/images only holds git-ignored photos: it does not exist on a clean clone.
        if (! is_dir(public_path('images'))) {
            mkdir(public_path('images'), 0775, true);
        }
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob(public_path('images/'.self::NAME.'-*')) ?: []);

        parent::tearDown();
    }

    public function test_it_shows_a_labelled_placeholder_when_the_image_files_are_missing(): void
    {
        $html = Blade::render('<x-foto name="'.self::NAME.'" alt="Una pizza" />');

        $this->assertStringContainsString('Foto provvisoria', $html);
        $this->assertStringNotContainsString('<picture', $html);
    }

    public function test_it_renders_a_responsive_picture_when_the_image_files_exist(): void
    {
        foreach ([640, 1280, 1920, 2560] as $width) {
            touch(public_path('images/'.self::NAME.'-'.$width.'.webp'));
            touch(public_path('images/'.self::NAME.'-'.$width.'.avif'));
        }

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
        touch(public_path('images/'.self::NAME.'-640.webp'));

        $this->assertStringContainsString('loading="lazy"', Blade::render('<x-foto name="'.self::NAME.'" alt="" />'));
    }
}
