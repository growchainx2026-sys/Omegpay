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
        Schema::create('produto_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->string('description')->nullable()->default(NULL);
            $table->string('file')->nullable()->default(NULL);
            $table->unsignedBigInteger('produto_id')->nullable();

            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_files');
    }
};
