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
        Schema::table('produtos', function (Blueprint $table) {
            // Garante que a coluna só será criada se ainda não existir
            if (!Schema::hasColumn('produtos', 'area_member_white_mode')) {
                $table->boolean('area_member_white_mode')
                    ->default(false)
                    ->after('area_member_banner');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            // Só tenta remover se a coluna existir
            if (Schema::hasColumn('produtos', 'area_member_white_mode')) {
                $table->dropColumn('area_member_white_mode');
            }
        });
    }
};

