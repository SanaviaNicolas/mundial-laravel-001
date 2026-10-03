<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\AllergenFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return BelongsToMany<Ingredient, $this, AllergenIngredient>
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->using(AllergenIngredient::class);
    }

    /**
     * @return BelongsToMany<MenuItem, $this, AllergenMenuItem>
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class)->using(AllergenMenuItem::class);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
