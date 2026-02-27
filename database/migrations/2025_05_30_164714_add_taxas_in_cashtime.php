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
        Schema::table('cashtime', function (Blueprint $table) {
            $table->decimal('taxa_cash_in', 10,2)->default(0.00)->after('url_cash_out')->comment('Taxa de Cash In');
            $table->decimal('taxa_cash_out', 10,2)->default(0.00)->after('taxa_cash_in')->comment('Taxa de Cash Out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cashtime', function (Blueprint $table) {
            $table->dropColumn(['taxa_cash_in', 'taxa_cash_out']);
        });
    }
};
