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
            $table->string('area_member_color_primary')->nullable()->default('#00d47f')->after('google_ads');
            $table->string('area_member_color_background')->nullable()->default('#ffffff')->after('area_member_color_primary');
            $table->string('area_member_color_sidebar')->nullable()->default('#ffffff')->after('area_member_color_background');
            $table->string('area_member_color_text')->nullable()->default('#000000')->after('area_member_color_sidebar');
            $table->string('area_member_background_image')->nullable()->default(NULL)->after('area_member_color_text');
            
            
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('area_member_color_primary');
            $table->dropColumn('area_member_color_background');
            $table->dropColumn('area_member_color_sidebar');
            $table->dropColumn('area_member_color_text');
            $table->dropColumn('area_member_background_image');
        });
    }
};
