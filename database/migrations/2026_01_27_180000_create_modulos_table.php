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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('icone')->nullable(); // Nome do ícone (lucide-react)
            $table->integer('ordem')->default(0);
            $table->boolean('status')->default(true);
            $table->string('capa')->nullable(); // URL da imagem de capa
            $table->timestamps();

            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
