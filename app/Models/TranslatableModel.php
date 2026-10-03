<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

/**
 * Base of the models with translatable (jsonb) attributes.
 */
abstract class TranslatableModel extends Model
{
    use HasTranslations;

    /**
     * @var list<string>
     */
    protected array $translatable = [];
}
