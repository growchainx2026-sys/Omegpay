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
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pendente', 'enviado', 'falhou'])->default('pendente');
            $table->string('message')->nullable()->default(NULL);

            // Criar as colunas antes da foreign
            $table->unsignedBigInteger('transaction_cash_in_id')->nullable();
            $table->unsignedBigInteger('transaction_cash_out_id')->nullable();

            // Definir as foreign keys
            $table->foreign('transaction_cash_in_id')
                ->references('id')
                ->on('transactions_cash_in')
                ->onDelete('set null');

            $table->foreign('transaction_cash_out_id')
                ->references('id')
                ->on('transactions_cash_out')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callbacks');
    }
};
