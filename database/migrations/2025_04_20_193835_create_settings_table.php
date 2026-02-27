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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('software_name')->default('SoftBanking');
            $table->string('logo_light')->default('logo_azul.png');
            $table->string('logo_dark')->default('logo_branca.png');
            $table->string('favicon_light')->default('icon_azul.png');
            $table->string('favicon_dark')->default('icon_branco.png');
            $table->decimal('taxa_cash_in', 10,2)->default(5.00);
            $table->decimal('taxa_cash_out', 10,2)->default(5.00);
            $table->string('adquirente_default')->default('royalbanking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
