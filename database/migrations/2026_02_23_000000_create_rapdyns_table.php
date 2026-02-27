<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rapdyns', function (Blueprint $table) {
            $table->id();
            $table->string('url_base')->default('https://app.rapdyn.io/api');
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('webhook_token_deposit')->nullable();
            $table->string('webhook_token_withdraw')->nullable();
            $table->decimal('taxa_cash_in', 10, 2)->default(0);
            $table->decimal('taxa_cash_out', 10, 2)->default(0);
            $table->timestamps();
        });

        DB::table('rapdyns')->insert([
            'url_base' => 'https://app.rapdyn.io/api',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapdyns');
    }
};
