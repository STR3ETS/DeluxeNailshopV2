<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Maximaal aantal keer dat een code gebruikt mag worden (null = onbeperkt)
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->unsignedInteger('max_gebruik')->nullable()->after('gebruikt');
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->foreignId('discount_code_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');

        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropColumn('max_gebruik');
        });
    }
};
