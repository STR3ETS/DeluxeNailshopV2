<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->timestamps();
        });

        // Backfill: bestellingen die al betaald zijn krijgen direct een factuur,
        // op volgorde van plaatsing en met de besteldatum als factuurdatum.
        $betaald = DB::table('orders')
            ->whereIn('status', ['betaald', 'verzonden', 'afgerond'])
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $volgnummers = [];

        foreach ($betaald as $order) {
            $jaar = date('Y', strtotime($order->created_at));
            $volgnummers[$jaar] = ($volgnummers[$jaar] ?? 0) + 1;

            DB::table('invoices')->insert([
                'order_id'   => $order->id,
                'number'     => 'F'.$jaar.'-'.str_pad((string) $volgnummers[$jaar], 4, '0', STR_PAD_LEFT),
                'created_at' => $order->created_at,
                'updated_at' => $order->created_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
