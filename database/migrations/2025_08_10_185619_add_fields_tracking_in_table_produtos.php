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
            $table->string('meta_ads')->nullable()->default(null)->after('methods');
            $table->string('utmfy')->nullable()->default(null)->after('meta_ads');
            $table->string('google_ads')->nullable()->default(null)->after('utmfy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('meta_ads');
            $table->dropColumn('utmfy');
            $table->dropColumn('google_ads');
        });
    }
};
