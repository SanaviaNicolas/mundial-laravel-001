<?php

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Free label (vegano, piccante, novita, stagionale, ...) with a stable slug.
 *
 * @property array<string, string> $name
 */
#[Fillable(['slug', 'name'])]
class Tag extends TranslatableModel
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    protected array $translatable = ['name'];

    /**
     * @return BelongsToMany<MenuItem, $this>
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class);
    }
}
