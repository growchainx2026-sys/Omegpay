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
        Schema::table('witetec', function (Blueprint $table) {
            $table->decimal('taxa_cash_in',10,2)->default(5)->after('api_token');
            $table->decimal('taxa_cash_out',10,2)->default(5)->after('taxa_cash_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('witetec', function (Blueprint $table) {
            $table->dropColumn('taxa_cash_in');
            $table->dropColumn('taxa_cash_out');
        });
    }
};
