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

    public function test_home_visit_panel_shows_the_static_map(): void
    {
        $html = $this->get('/')->getContent();
        $panel = substr($html, strpos($html, 'Vieni a trovarci'));

        $this->assertStringContainsString('mappa/mappa-800.avif 800w', substr($panel, 0, strpos($panel, '</section>')));
    }

    public function test_standalone_links_are_not_underlined(): void
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(resource_path('views')));

        foreach ($files as $file) {
            if ($file->isFile()) {
                $this->assertStringNotContainsString('link-line', file_get_contents($file->getPathname()), $file->getPathname());
            }
        }
    }

    public function test_the_current_page_is_marked_with_a_pill_in_the_navigation(): void
    {
        $this->assertMatchesRegularExpression('/<a href="\/menu" class="[^"]*aria-\[current=page\]:bg-current\/15[^"]*"\s+aria-current="page"\s*>/', $this->get('/menu')->getContent());
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
    public function test_closing_blocks_are_rounded_panels_set_apart_from_the_footer(string $uri, string $title): void
    {
        $html = $this->get($uri)->getContent();
        $band = substr($html, strrpos(substr($html, 0, strpos($html, $title)), '<section'));
        $band = substr($band, 0, strpos($band, '</section>'));

        $this->assertStringContainsString('rounded-2xl bg-crema-scuro', $band);
        $this->assertStringContainsString('bg-pomodoro text-white', $band, 'the call button keeps its primary style');
    }
}
