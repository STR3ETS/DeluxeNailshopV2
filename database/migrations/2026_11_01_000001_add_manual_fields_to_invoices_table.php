<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Handmatige facturen: een factuur zonder webshopbestelling, met eigen
 * klantgegevens en regels. Ze delen de tabel (en dus de doorlopende
 * nummering F2026-0001, F2026-0002, ...) met de facturen van bestellingen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->change();

            $table->string('name')->nullable()->after('number');
            $table->string('company')->nullable()->after('name');
            $table->string('email')->nullable()->after('company');
            $table->string('address')->nullable()->after('email');
            $table->string('postcode', 10)->nullable()->after('address');
            $table->string('city', 100)->nullable()->after('postcode');
            $table->string('country', 2)->nullable()->after('city');
            $table->string('vat_number', 30)->nullable()->after('country');
            $table->json('lines')->nullable()->after('vat_number');
            $table->decimal('total', 10, 2)->nullable()->after('lines');
            $table->boolean('paid')->default(true)->after('total');
            $table->text('note')->nullable()->after('paid');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['name', 'company', 'email', 'address', 'postcode', 'city', 'country', 'vat_number', 'lines', 'total', 'paid', 'note']);
        });
    }
};
