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
        Schema::table('cupons', function (Blueprint $table) {
            $table->integer('usage')->default(0)->after('aplicar_orderbumps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupons', function (Blueprint $table) {
            $table->dropColumn('usage');
        });
    }
};
