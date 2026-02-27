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
       Schema::create('transactions_cash_in', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 191)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('client_name',191);
            $table->string('client_cpf',191);
            $table->string('end2end')->nullable();
            $table->enum('status', ['pendente','pago','cancelado','revisao'])->default('pendente');
            $table->string('idTransaction',191);
            $table->decimal('cash_in_liquido',10,2);
            $table->string('qrcode_pix',500);
            $table->string('paymentcode',500);
            $table->longText('paymentCodeBase64');
            $table->string('adquirente_ref',191);
            $table->decimal('taxa_cash_in',10,2)->nullable();
            $table->decimal('taxa_fixa',10,2)->nullable();
            $table->string('executor_ordem',191);
            $table->string('descricao_transacao',191);
            $table->string('request_ip')->nullable();
            $table->string('request_domain')->nullable();
            $table->enum('type', ['cash', 'split']);
            $table->enum('plataforma', ['web', 'api']);
            $table->string('callbackUrl')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_cash_in');
    }
};
