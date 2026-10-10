<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
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
    public function test_closing_bands_are_tomato_red_to_stand_apart_from_the_footer(string $uri, string $title): void
    {
        $html = $this->get($uri)->getContent();
        $band = substr($html, strrpos(substr($html, 0, strpos($html, $title)), '<section'));
        $band = substr($band, 0, strpos($band, '</section>'));

        $this->assertStringContainsString('bg-pomodoro ', $band);
        $this->assertStringContainsString('bg-white text-pomodoro-scuro', $band, 'the call button turns white on red');
    }

    public function test_the_light_button_variant_is_white_with_dark_tomato_text(): void
    {
        $this->assertStringContainsString('bg-white text-pomodoro-scuro', Blade::render('<x-button href="/" variant="light">Chiama</x-button>'));
    }
}
