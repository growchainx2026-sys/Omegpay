<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->unsignedInteger('liberar_em_dias')->nullable()->after('liberar_em')->comment('Liberar X dias após o aluno ter acesso ao curso');
        });

        Schema::table('sessoes', function (Blueprint $table) {
            $table->unsignedInteger('liberar_em_dias')->nullable()->after('liberar_em')->comment('Liberar X dias após o aluno ter acesso ao curso');
        });
    }

    public function down(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn('liberar_em_dias');
        });

        Schema::table('sessoes', function (Blueprint $table) {
            $table->dropColumn('liberar_em_dias');
        });
    }
};
