<?php

namespace Tests\Feature\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

/**
 * @property array<string, string> $name
 */
class TranslatableStub extends Model
{
    use HasTranslations;

    protected array $translatable = ['name', 'note'];
}

class HasTranslationsTest extends TestCase
{
    private function model(array $name, array $note = []): TranslatableStub
    {
        return (new TranslatableStub)->forceFill(['name' => $name, 'note' => $note]);
    }

    public function test_translatable_attributes_are_cast_to_arrays(): void
    {
        $model = $this->model(['it' => 'Pizza', 'en' => 'Pizza']);

        $this->assertSame(['it' => 'Pizza', 'en' => 'Pizza'], $model->name);
    }

    public function test_translate_returns_the_requested_locale(): void
    {
        $model = $this->model(['it' => 'Bibita', 'en' => 'Drink']);

        $this->assertSame('Drink', $model->translate('name', 'en'));
        $this->assertSame('Bibita', $model->translate('name', 'it'));
    }

    public function test_translate_uses_the_app_locale_by_default(): void
    {
        $model = $this->model(['it' => 'Bibita', 'en' => 'Drink']);

        app()->setLocale('en');

        $this->assertSame('Drink', $model->translate('name'));
    }

    public function test_translate_falls_back_to_italian_when_the_locale_is_missing_or_blank(): void
    {
        $this->assertSame('Bibita', $this->model(['it' => 'Bibita'])->translate('name', 'en'));
        $this->assertSame('Bibita', $this->model(['it' => 'Bibita', 'en' => '  '])->translate('name', 'en'));
    }

    public function test_translate_returns_null_when_nothing_is_available(): void
    {
        $this->assertNull($this->model(['it' => 'x'], [])->translate('note', 'en'));
    }
}
