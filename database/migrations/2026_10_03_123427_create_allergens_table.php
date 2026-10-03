<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->jsonb('name');
            $table->unsignedSmallInteger('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergens');
    }
};
