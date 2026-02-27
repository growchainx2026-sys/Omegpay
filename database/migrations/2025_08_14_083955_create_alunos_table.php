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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default('Aluno');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cpf')->unique();
            $table->string('celular')->nullable()->default(NULL);
            $table->string('cep')->nullable()->default(NULL);
            $table->string('street')->nullable()->default(NULL);
            $table->string('uf')->nullable()->default(NULL);
            $table->string('city')->nullable()->default(NULL);
            $table->string('address')->nullable()->default(NULL);
            $table->string('status')->nullable()->default(NULL);

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
        Schema::dropIfExists('alunos');
    }
};
