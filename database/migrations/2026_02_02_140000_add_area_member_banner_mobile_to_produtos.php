<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->string('area_member_banner_mobile')->nullable()->after('area_member_banner')->comment('Banner mobile 768x400 exibido em dispositivos móveis');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('area_member_banner_mobile');
        });
    }
};
