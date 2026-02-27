<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions_cash_in', function (Blueprint $table) {
            $table->enum('method', ['pix', 'billet', 'card'])->default('pix');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions_cash_in', function (Blueprint $table) {
            $table->dropColumn('method');
        });
    }
};
