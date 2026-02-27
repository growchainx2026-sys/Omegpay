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
        Schema::create('xgate', function (Blueprint $table) {
            $table->id();
            $table->string('secret')->nullable()->default(null);
            $table->decimal('taxa_cash_in', 10, 2)->nullable()->default(0);
            $table->decimal('taxa_cash_out', 10, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xgate');
    }
};
