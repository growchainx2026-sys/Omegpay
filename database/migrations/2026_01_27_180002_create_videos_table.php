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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sessao_id');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('url_youtube'); // URL completa do YouTube
            $table->integer('duracao')->nullable(); // Duração em segundos
            $table->integer('ordem')->default(0);
            $table->boolean('status')->default(true);
            $table->string('thumbnail')->nullable(); // URL da thumbnail customizada
            $table->timestamps();

            $table->foreign('sessao_id')
                ->references('id')
                ->on('sessoes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
