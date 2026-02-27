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
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('taxa_cash_in_fixa',10,2)->default(0.00)->after('taxa_cash_out');
            $table->decimal('taxa_cash_out_fixa',10,2)->default(0.00)->after('taxa_cash_in_fixa');
            $table->decimal('taxa_reserva',10,2)->default(2.00)->after('taxa_cash_out_fixa');
            $table->decimal('deposito_minimo',10,2)->default(5.00)->after('taxa_reserva');
            $table->decimal('deposito_maximo',10,2)->default(1000.00)->after('deposito_minimo');
            $table->decimal('saque_minimo',10,2)->default(5.00)->after('deposito_maximo');
            $table->decimal('saque_maximo',10,2)->default(1000.00)->after('saque_minimo');
            $table->integer('saques_dia')->default(5)->after('saque_maximo') ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
