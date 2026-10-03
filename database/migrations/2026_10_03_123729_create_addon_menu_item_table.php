<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_menu_item', function (Blueprint $table) {
            $table->foreignId('addon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_excluded')->default(false);
            $table->primary(['addon_id', 'menu_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_menu_item');
    }
};
