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
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable(false);
            $table->decimal('desconto',10,2)->nullable(false)->default(5);
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_termino')->nullable();
            $table->boolean('aplicar_orderbumps')->default(false);
            $table->unsignedBigInteger('produto_id')->nullable();

            
            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
