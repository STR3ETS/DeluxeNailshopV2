<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('address')->nullable()->after('phone');
            $table->string('postcode', 10)->nullable()->after('address');
            $table->string('city', 100)->nullable()->after('postcode');
            $table->string('country', 2)->default('NL')->after('city');
            $table->text('note')->nullable()->after('country');
            $table->decimal('shipping', 8, 2)->default(0)->after('total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'postcode', 'city', 'country', 'note', 'shipping']);
        });
    }
};
