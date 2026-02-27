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
        Schema::table('fcm', function (Blueprint $table) {
            $table->string('firebase_config')->nullable()->default(NULL)->after('measurementId');
            $table->string('title')->default('Venda realizada com sucesso!')->after('firebase_config');
            $table->string('body')->default('Você recebeu um pix no valor de {valor}')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcm', function (Blueprint $table) {
            $table->dropColumn('firebase_config');
            $table->dropColumn('title');
            $table->dropColumn('body');
        });
    }
};
