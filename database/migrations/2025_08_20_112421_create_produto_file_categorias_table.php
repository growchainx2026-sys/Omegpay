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
        Schema::create('produto_file_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(NULL);
            $table->string('description')->nullable()->default(NULL);

            $table->unsignedBigInteger('produto_id')->nullable();

            $table->foreign('produto_id')
                ->references('id')
                ->on('produtos')
                ->onDelete('set null');
            $table->timestamps();
        });


        Schema::table('produto_files', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->after('produto_id');

            $table->foreign('categoria_id')
                ->references('id')
                ->on('produto_file_categorias')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_file_categorias');
        Schema::table('produto_files', function (Blueprint $table) {
            $table->dropColumn('categoria_id');
            $table->dropConstrainedForeignId('categoria_id');
        });
    }
};
