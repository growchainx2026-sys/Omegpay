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
        Schema::create('cashtime', function (Blueprint $table) {
            $table->id();
            $table->string('secret')->nullable();
            $table->string('url_cash_in')->default('https://api.cashtime.com.br/v1/transactions');
            $table->string('url_cash_out')->default('https://api.cashtime.com.br/v1/request/withdraw');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashtime');
    }
};
