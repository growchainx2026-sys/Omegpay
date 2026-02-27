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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained('links')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('codigo_voucher')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_cpf')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_telefone')->nullable();
            $table->decimal('valor', 12, 2)->default(0);
            $table->string('status')->default('pendente');
            $table->string('payment_method')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->text('descricao')->nullable();
            $table->string('ativacao')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions_cash_in')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
