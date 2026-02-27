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
        Schema::create('transfeeras', function (Blueprint $table) {
            $table->id();
            $table->string("token")->nullable()->default(NULL);
            $table->string("secret")->nullable()->default(NULL);
            $table->string("url")->nullable()->default('https://api.transfeera.com');
            $table->string("url_cash_in")->nullable()->default('https://api.transfeera.com/pix/qrcode/collection/immediate');
            $table->string("url_cash_out")->nullable()->default('https://api.transfeera.com/pix/qrcode/collection/immediate');
            $table->string("tenant_name")->nullable()->default(NULL);
            $table->string("tenant_email")->nullable()->default(NULL);
            $table->string("tenant_keypix")->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfeeras');
    }
};
