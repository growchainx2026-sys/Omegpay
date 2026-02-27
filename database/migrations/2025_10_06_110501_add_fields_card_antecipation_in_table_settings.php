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
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('card_days_to_anticipation_opt1')->default(0);
            $table->decimal('card_tx_to_anticipation_opt1', 10, 2)->default(0.00);
            $table->integer('card_days_to_anticipation_opt2')->default(0);
            $table->decimal('card_tx_to_anticipation_opt2', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('card_days_to_anticipation_opt1');
            $table->dropColumn('card_tx_to_anticipation_opt1');
            $table->dropColumn('card_days_to_anticipation_opt2');
            $table->dropColumn('card_tx_to_anticipation_opt2');
        });
    }
};
