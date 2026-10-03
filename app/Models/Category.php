<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Menu category on two levels: macro categories (no parent) and subcategories.
 *
 * @property array<string, string> $name
 * @property array<string, string>|null $description
 */
#[Fillable(['parent_id', 'name', 'slug', 'description', 'sort_order', 'is_visible'])]
class Category extends TranslatableModel
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected array $translatable = ['name', 'description'];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if ($category->parent_id === null) {
                return;
            }

            $isSelf = $category->parent_id === $category->getKey();
            $parentIsSub = Category::whereKey($category->parent_id)->whereNotNull('parent_id')->exists();
            $hasChildren = $category->exists && $category->children()->exists();

            if ($isSelf || $parentIsSub || $hasChildren) {
                throw new DomainException('Categories can be nested on two levels at most.');
            }
        });
    }

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return HasMany<Category, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->ordered();
    }

    /**
     * @return HasMany<MenuItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->ordered();
    }

    /**
     * "Macro › Sub" for a subcategory, just the name for a macro category.
     */
    public function pathLabel(?string $locale = null): string
    {
        $name = (string) $this->translate('name', $locale);

        return $this->parent_id === null ? $name : $this->parent->pathLabel($locale).' › '.$name;
    }

    /**
     * Every category as [id => path label], in tree order (each macro followed by its subcategories).
     *
     * @return array<int, string>
     */
    public static function pathOptions(): array
    {
        $options = [];

        foreach (self::query()->whereNull('parent_id')->with('children')->ordered()->get() as $macro) {
            $options[$macro->id] = $macro->pathLabel();

            foreach ($macro->children as $child) {
                $options[$child->id] = $child->setRelation('parent', $macro)->pathLabel();
            }
        }

        return $options;
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Visible on the public site: hiding a macro category hides its subcategories too.
     *
     * @param  Builder<static>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true)->where(
            fn (Builder $query) => $query
                ->whereNull('parent_id')
                ->orWhereIn('parent_id', self::query()->where('is_visible', true)->select('id')),
        );
    }
}
