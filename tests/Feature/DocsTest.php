<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class DocsTest extends TestCase
{
    public function test_internal_markdown_links_resolve(): void
    {
        $broken = [];

        foreach ($this->markdownFiles() as $file) {
            preg_match_all('/\]\(([^)\s]+)\)/', file_get_contents($file), $matches);

            foreach ($matches[1] as $link) {
                if (preg_match('#^(https?:|mailto:|tel:|\#)#', $link)) {
                    continue;
                }

                $path = dirname($file).'/'.explode('#', $link)[0];

                if (! file_exists($path)) {
                    $broken[] = str_replace($this->root().'/', '', $file).' -> '.$link;
                }
            }
        }

        $this->assertSame([], $broken, 'Broken internal links');
    }

    public function test_every_doc_is_linked_from_the_docs_index(): void
    {
        $index = file_get_contents($this->root().'/docs/README.md');
        $missing = [];

        foreach ($this->markdownFiles() as $file) {
            $relative = str_replace($this->root().'/docs/', '', $file);

            if (str_starts_with($file, $this->root().'/docs/') && $relative !== 'README.md' && ! str_contains($index, "]({$relative})")) {
                $missing[] = $relative;
            }
        }

        $this->assertSame([], $missing, 'Docs missing from docs/README.md');
    }

    /** @return list<string> */
    private function markdownFiles(): array
    {
        $files = [$this->root().'/README.md'];

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->root().'/docs', \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->getExtension() === 'md') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function root(): string
    {
        return dirname(__DIR__, 2);
    }
}
