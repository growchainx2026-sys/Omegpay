<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->string('url')->nullable()->default(NULL);
            $table->enum('status',['pendente', 'pago', 'cancelado'])->nullable()->default(NULL);
            $table->enum('type', ['geral', 'produto'])->nullable()->default(NULL);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('produto_id')->nullable();

            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
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
        Schema::dropIfExists('webhooks');
    }
};
