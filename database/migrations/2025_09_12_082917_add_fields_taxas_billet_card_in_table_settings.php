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
            $table->decimal('card_taxa_percent', 10, 2)->default(5)->after('taxa_cash_out_fixa');
            $table->decimal('card_taxa_fixed', 10, 2)->default(5)->after('card_taxa_percent');
            $table->decimal('billet_taxa_percent', 10, 2)->default(5)->after('card_taxa_fixed');
            $table->decimal('billet_taxa_fixed', 10, 2)->default(5)->after('billet_taxa_percent');
            $table->integer('card_days_to_release')->default(30)->after('billet_taxa_fixed');
            $table->integer('billet_days_to_release')->default(3)->after('card_days_to_release');
        });

         Schema::table('transactions_cash_in', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'pago', 'cancelado', 'revisao', 'reservado', 'estorno'])->default('pendente')->after('end2end')->change();
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('card_taxa_percent');
            $table->dropColumn('card_taxa_fixed');
            $table->dropColumn('billet_taxa_percent');
            $table->dropColumn('billet_taxa_fixed');
            $table->dropColumn('card_days_to_release');
            $table->dropColumn('billet_days_to_release');
        });
    }
};
