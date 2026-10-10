<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tests\TestCase;

class VisualRulesTest extends TestCase
{
    public function test_no_shadows_are_used_anywhere(): void
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));

        foreach ($files as $file) {
            if ($file->isFile()) {
                $this->assertDoesNotMatchRegularExpression('/\bshadow\b|shadow-|drop-shadow/', file_get_contents($file->getPathname()), $file->getPathname());
            }
        }

        $this->assertDoesNotMatchRegularExpression('/box-shadow|drop-shadow/', file_get_contents(resource_path('css/app.css')));
    }

    /** @return array<string, array{string, string}> */
    public static function closingBands(): array
    {
        return [
            'home: vieni a trovarci' => ['/', 'Vieni a trovarci'],
            'menu: hai scelto' => ['/menu', 'Hai scelto?'],
        ];
    }

    #[DataProvider('closingBands')]
    public function test_closing_bands_are_a_soft_tomato_tint_to_stand_apart_from_the_footer(string $uri, string $title): void
    {
        $html = $this->get($uri)->getContent();
        $band = substr($html, strrpos(substr($html, 0, strpos($html, $title)), '<section'));
        $band = substr($band, 0, strpos($band, '</section>'));

        $this->assertStringContainsString('bg-pomodoro-chiaro text-ink', $band);
        $this->assertStringContainsString('bg-pomodoro text-white', $band, 'the call button keeps its primary style');
    }
}
