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
        Schema::table('pagarme', function (Blueprint $table) {
            $table->decimal('1x',10,2)->default(4.39)->after('tx_card_percent');
            $table->decimal('2x',10,2)->default(8.16)->after('1x');
            $table->decimal('3x',10,2)->default(9.89)->after('2x');
            $table->decimal('4x',10,2)->default(11.59)->after('3x');
            $table->decimal('5x',10,2)->default(13.29)->after('4x');
            $table->decimal('6x',10,2)->default(14.99)->after('5x');
            $table->decimal('7x',10,2)->default(16.76)->after('6x');
            $table->decimal('8x',10,2)->default(18.49)->after('7x');
            $table->decimal('9x',10,2)->default(20.19)->after('8x');
            $table->decimal('10x',10,2)->default(21.89)->after('9x');
            $table->decimal('11x',10,2)->default(23.59)->after('10x');
            $table->decimal('12x',10,2)->default(25.29)->after('11x');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagarme', function (Blueprint $table) {
            //
        });
    }
};
