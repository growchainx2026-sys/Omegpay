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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('software_color_background', 20)->default('#EAE9E3');
            $table->string('software_color_sidebar', 20)->default('#FFFFFF');
            $table->string('software_color_text', 20)->default('#808080');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('software_color_background');
            $table->dropColumn('software_color_sidebar');
            $table->dropColumn('software_color_text');
        });
    }
};
