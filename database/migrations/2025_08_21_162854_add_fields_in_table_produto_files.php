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
        Schema::table('produto_files', function (Blueprint $table) {
            $table->enum('file_type', ['audio', 'video', 'zip', 'pdf', 'txt'])->nullable()->default(NULL);
            $table->string('cover')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto_files', function (Blueprint $table) {
            $table->dropColumn('file_type');
            $table->dropColumn('cover');
        });
    }
};
