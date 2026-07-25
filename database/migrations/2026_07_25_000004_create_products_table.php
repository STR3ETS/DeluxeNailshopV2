<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 50);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 50)->index();
            $table->string('subcategory', 50)->nullable()->index();
            $table->decimal('price', 8, 2);
            $table->decimal('old_price', 8, 2)->nullable();
            $table->string('badge', 30)->nullable();
            $table->boolean('badge_gold')->default(false);
            $table->string('bg_from', 9)->nullable();
            $table->string('bg_to', 9)->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->json('kenmerken')->nullable();
            $table->json('gebruiksaanwijzing')->nullable();
            $table->string('inhoud', 50)->nullable();
            $table->unsignedInteger('reviews')->default(0);
            $table->unsignedInteger('voorraad')->default(0);
            $table->boolean('actief')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
