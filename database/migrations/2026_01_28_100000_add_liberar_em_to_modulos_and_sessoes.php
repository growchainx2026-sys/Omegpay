<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dateTime('liberar_em')->nullable()->after('status');
        });

        Schema::table('sessoes', function (Blueprint $table) {
            $table->dateTime('liberar_em')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn('liberar_em');
        });

        Schema::table('sessoes', function (Blueprint $table) {
            $table->dropColumn('liberar_em');
        });
    }
};
