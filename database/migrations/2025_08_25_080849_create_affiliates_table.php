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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->decimal('percentage',10,2)->nullable()->default(5.00);
            $table->enum('status',['pending', 'accept', 'recused'])->nullable()->default('pending');
            $table->unsignedBigInteger('produto_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('set null');

                // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
