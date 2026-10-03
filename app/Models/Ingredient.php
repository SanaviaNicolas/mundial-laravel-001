<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Reusable ingredient. Frozen ones are marked with an asterisk on the public site.
 *
 * @property array<string, string> $name
 * @property bool $is_frozen
 */
#[Fillable(['name', 'is_frozen'])]
class Ingredient extends Model
{
    /** @use HasFactory<IngredientFactory> */
    use HasFactory, HasTranslations;

    protected array $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'is_frozen' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Allergen, $this, AllergenIngredient>
     */
    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class)->using(AllergenIngredient::class);
    }
}
