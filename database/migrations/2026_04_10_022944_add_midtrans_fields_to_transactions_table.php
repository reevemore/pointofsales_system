<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom untuk menyimpan order_id dari Midtrans
            $table->string('midtrans_order_id')->nullable()->unique()->after('id');

            // Status pembayaran: pending | paid | failed
            $table->enum('payment_status', ['pending', 'paid', 'failed'])
                  ->default('pending')
                  ->after('midtrans_order_id');

            // Metode: tunai | transfer | qris
            // Jika sudah ada kolom payment_method di tabel kamu, skip ini
            $table->string('payment_method')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['midtrans_order_id', 'payment_status', 'payment_method']);
        });
    }
};