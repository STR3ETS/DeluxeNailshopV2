<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('levering', 20)->default('bezorgen')->after('country');
            $table->string('discount_code', 50)->nullable()->after('shipping');
            $table->decimal('discount', 8, 2)->default(0)->after('discount_code');

            // Bij afhalen is er geen bezorgadres
            $table->string('address')->nullable()->change();
            $table->string('postcode')->nullable()->change();
            $table->string('city')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['levering', 'discount_code', 'discount']);
        });
    }
};
