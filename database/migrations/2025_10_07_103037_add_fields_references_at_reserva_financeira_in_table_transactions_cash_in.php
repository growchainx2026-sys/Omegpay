<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions_cash_in', function (Blueprint $table) {
            $table->decimal('taxa_reserva', 10, 2)->nullable()->after('dias_recebimento')->change();
            $table->integer('dias_liberar_reserva')->nullable()->after('taxa_reserva');
            $table->boolean('taxa_reserva_resgatada')->default(true)->after('dias_liberar_reserva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions_cash_in', function (Blueprint $table) {
            $table->dropColumn('taxa_reserva');
            $table->dropColumn('dias_liberar_reserva');
            $table->dropColumn('taxa_reserva_resgatada');
        });
    }
};
