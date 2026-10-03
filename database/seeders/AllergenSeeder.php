<?php

namespace Database\Seeders;

use App\Models\Allergen;
use Illuminate\Database\Seeder;

/**
 * The 14 allergens of EU Regulation 1169/2011 (annex II). Idempotent: safe to run
 * on every deploy (`php artisan db:seed --class=AllergenSeeder --force`).
 */
class AllergenSeeder extends Seeder
{
    private const ALLERGENS = [
        'gluten' => ['Cereali contenenti glutine', 'Cereals containing gluten'],
        'crustaceans' => ['Crostacei', 'Crustaceans'],
        'eggs' => ['Uova', 'Eggs'],
        'fish' => ['Pesce', 'Fish'],
        'peanuts' => ['Arachidi', 'Peanuts'],
        'soybeans' => ['Soia', 'Soybeans'],
        'milk' => ['Latte (incluso lattosio)', 'Milk (including lactose)'],
        'nuts' => ['Frutta a guscio', 'Tree nuts'],
        'celery' => ['Sedano', 'Celery'],
        'mustard' => ['Senape', 'Mustard'],
        'sesame' => ['Semi di sesamo', 'Sesame seeds'],
        'sulphites' => ['Anidride solforosa e solfiti', 'Sulphur dioxide and sulphites'],
        'lupin' => ['Lupini', 'Lupin'],
        'molluscs' => ['Molluschi', 'Molluscs'],
    ];

    public function run(): void
    {
        $rows = [];
        $order = 0;

        foreach (self::ALLERGENS as $key => [$it, $en]) {
            $rows[] = [
                'key' => $key,
                'name' => json_encode(['it' => $it, 'en' => $en], JSON_UNESCAPED_UNICODE),
                'sort_order' => ++$order,
            ];
        }

        Allergen::upsert($rows, ['key'], ['name', 'sort_order']);
    }
}
