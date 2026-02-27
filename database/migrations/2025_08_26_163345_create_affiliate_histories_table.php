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
        Schema::create('affiliate_histories', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10,2)->default(0)->comment('Comissão de afiliado');
            $table->enum('status', ['pendente','pago','cancelado','revisao'])->default('pendente')->comment('Status da comissão');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('pedido_id')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('pedido_id')
                ->references('id')
                ->on('pedidos')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_histories');
    }
};
