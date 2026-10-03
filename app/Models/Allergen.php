<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\AllergenFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One of the 14 allergens of EU Regulation 1169/2011. Fixed list, loaded by AllergenSeeder.
 *
 * @property array<string, string> $name
 */
#[Fillable(['key', 'name', 'sort_order'])]
class Allergen extends Model
{
    /** @use HasFactory<AllergenFactory> */
    use HasFactory, HasTranslations;

    public $timestamps = false;

    protected array $translatable = ['name'];

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
