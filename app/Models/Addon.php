<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Database\Factories\AddonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Optional extra or choice (added ingredient, stuffed crust, gluten-free dough, ...)
 * with a price supplement in cents, possibly zero.
 *
 * @property array<string, string> $name
 * @property int $price_cents
 */
#[Fillable(['name', 'price_cents', 'sort_order', 'is_visible'])]
class Addon extends Model
{
    /** @use HasFactory<AddonFactory> */
    use HasFactory, HasTranslations;

    protected array $translatable = ['name'];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    /**
     * Categories where the addon applies by default to every item (and, for a macro, to its subcategories).
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }
}
