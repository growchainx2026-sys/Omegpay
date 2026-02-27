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
        Schema::create('efis', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable(); 
            $table->string('client_secret')->nullable(); 
            $table->string('chave_pix')->nullable(); 
            $table->string('identificador_conta')->nullable(); 
            $table->string('gateway_id')->nullable(); 
            $table->string('cert')->nullable(); 
            $table->decimal('taxa_pix_cash_in', 10, 2)->default(5.00); 
            $table->decimal('taxa_pix_cash_out', 10, 2)->default(5.00); 
            $table->decimal('billet_tx_fixed', 10, 2)->default(5.00); 
            $table->decimal('billet_tx_percent', 10, 2)->default(5.00); 
            $table->decimal('card_tx_percent', 10, 2)->default(5.00); 
            $table->decimal('card_tx_fixed', 10, 2)->default(5.00); 
            $table->decimal('billet_days_availability', 10, 2)->default(5.00); 
            $table->integer('card_days_availability')->default(20); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('efis');
    }
};
