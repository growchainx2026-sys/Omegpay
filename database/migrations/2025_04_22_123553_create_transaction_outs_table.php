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
        Schema::create('transactions_cash_out', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('external_id',191)->nullable();
            $table->decimal('amount',10,2);
            $table->string('recebedor_name',191);
            $table->string('recebedor_cpf',191);
            $table->string('pixKeyType',191);
            $table->string('pixKey',191);
            $table->enum('status', ['pendente','pago','cancelado','revisao'])->default('pendente');
            $table->string('idTransaction',191);
            $table->string('end2end',191);
            $table->decimal('taxa_cash_out',10,2);
            $table->decimal('taxa_fixa',10,2)->nullable();
            $table->enum('type', ['cash', 'split']);
            $table->enum('plataforma', ['web', 'api']);
            $table->string('cash_out_liquido');
            $table->string('request_ip')->nullable();
            $table->string('request_domain')->nullable();
            $table->string('callbackUrl')->nullable();
            $table->string('adquirente_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions_cash_out');
    }
};
