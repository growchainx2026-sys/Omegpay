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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('aluno_id')->nullable()->after('produto_id');

            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('aluno_id')
                ->references('id')
                ->on('alunos')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['aluno_id']);
            $table->dropColumn('aluno_id');
        });
    }
};
