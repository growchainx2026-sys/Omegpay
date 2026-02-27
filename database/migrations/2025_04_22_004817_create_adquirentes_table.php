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
        Schema::create('adquirentes', function (Blueprint $table) {
            $table->id();
            $table->string('default')->default('royalbanking');
            $table->string('uri')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->decimal('taxa_cash_in', 10,2)->default(5);
            $table->decimal('taxa_cash_out', 10,2)->default(5);
            $table->boolean('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adquirentes');
    }
};
