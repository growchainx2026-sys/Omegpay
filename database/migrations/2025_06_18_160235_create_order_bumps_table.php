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
        Schema::create('order_bumps', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_de',10,2)->default(0);
            $table->decimal('valor_por',10,2)->default(0);
            $table->string('call_to_action')->default('SIM, EU ACEITO ESSA OFERTA ESPECIAL!');
            $table->string('product_name')->nullable()->default(NULL);
            $table->string('product_description')->nullable()->default('Adcione a compra');
            $table->unsignedBigInteger('produto_id');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_bumps');
    }
};
