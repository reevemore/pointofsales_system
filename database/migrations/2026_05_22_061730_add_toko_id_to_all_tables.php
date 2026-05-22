<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // users — admin & kasir milik toko tertentu
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });

        // categories
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });

        // products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });

        // members
        Schema::table('members', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });

        // transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });

        // stock_mutations
        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->foreignId('toko_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('stores')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });

        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->dropForeign(['toko_id']);
            $table->dropColumn('toko_id');
        });
    }
};