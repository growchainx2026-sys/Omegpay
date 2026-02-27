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
            $table->string('adquirencia_pix')->nullable()->default('efi');
            $table->string('adquirencia_billet')->nullable()->default('efi');
            $table->string('adquirencia_card')->nullable()->default('efi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('adquirencia_pix');
            $table->dropColumn('adquirencia_billet');
            $table->dropColumn('adquirencia_card');
        });
    }
};
