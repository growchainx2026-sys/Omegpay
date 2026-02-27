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
        Schema::table('users', function (Blueprint $table) {
            $table->string('logo')->nullable()->default('/images/logo_light.png')->after('avatar');
            $table->string('software_color')->nullable()->default('#c62160');
            $table->string('software_color_background')->nullable()->default('#ffffff');
            $table->string('software_color_sidebar')->nullable()->default('#ffffff');
            $table->string('software_color_text')->nullable()->default('#000000');
            $table->string('logo_light')->nullable()->default('/images/logo_light.png');
            $table->string('favicon_light')->nullable()->default('/images/logo_light.png');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('logo');         
            $table->dropColumn('software_color');
            $table->dropColumn('software_color_background');
            $table->dropColumn('software_color_sidebar');
            $table->dropColumn('software_color_text');
            $table->dropColumn('logo_light');
            $table->dropColumn('favicon_light');
        });
    }
};
