<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('type', 10); // 'procent' of 'bedrag'
            $table->decimal('waarde', 8, 2);
            $table->decimal('min_bedrag', 8, 2)->nullable();
            $table->date('verloopt_op')->nullable();
            $table->boolean('actief')->default(true);
            $table->unsignedInteger('gebruikt')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
