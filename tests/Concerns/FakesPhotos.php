<?php

namespace Tests\Concerns;

/**
 * Creates empty photo files in public/images (git-ignored, missing on a clean clone)
 * and removes them after each test.
 */
trait FakesPhotos
{
    /** @var list<string> */
    private array $fakePhotos = [];

    protected function fakePhoto(string $name, array $extensions = ['webp', 'avif']): void
    {
        if (! is_dir(public_path('images'))) {
            mkdir(public_path('images'), 0775, true);
        }

        foreach ([640, 1280, 1920, 2560] as $width) {
            foreach ($extensions as $extension) {
                touch($this->fakePhotos[] = public_path("images/{$name}-{$width}.{$extension}"));
            }
        }
    }

    protected function tearDown(): void
    {
        array_map('unlink', array_filter($this->fakePhotos, 'file_exists'));

        parent::tearDown();
    }
}
