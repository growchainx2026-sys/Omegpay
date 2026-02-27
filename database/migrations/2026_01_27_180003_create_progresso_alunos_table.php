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
        Schema::create('progresso_alunos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('produto_id');
            $table->integer('tempo_assistido')->default(0); // Em segundos
            $table->integer('tempo_total')->default(0); // Em segundos
            $table->boolean('concluido')->default(false);
            $table->integer('ultima_posicao')->default(0); // Última posição assistida em segundos
            $table->timestamps();

            $table->foreign('aluno_id')
                ->references('id')
                ->on('alunos')
                ->onDelete('cascade');

            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');

            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('cascade');

            // Evita duplicatas: um aluno só pode ter um progresso por vídeo
            $table->unique(['aluno_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progresso_alunos');
    }
};
