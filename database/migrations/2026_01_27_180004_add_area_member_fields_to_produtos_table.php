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
            // Modo claro/escuro
            $table->boolean('area_member_white_mode')->default(false)->after('area_member_banner');
            
            // Logo da área de membros (se diferente do logo padrão)
            $table->string('area_member_logo')->nullable()->after('area_member_white_mode');
            
            // Texto de boas-vindas personalizado
            $table->text('area_member_welcome_text')->nullable()->after('area_member_logo');
            
            // Configurações de gamificação
            $table->boolean('area_member_gamification_enabled')->default(true)->after('area_member_welcome_text');
            $table->json('area_member_gamification_settings')->nullable()->after('area_member_gamification_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn([
                'area_member_white_mode',
                'area_member_logo',
                'area_member_welcome_text',
                'area_member_gamification_enabled',
                'area_member_gamification_settings',
            ]);
        });
    }
};
