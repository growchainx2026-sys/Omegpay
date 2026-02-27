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
        Schema::table('produtos', function (Blueprint $table) {
            $table->enum('type', ['unique', 'subscription'])->after('id')->default('unique');
            $table->string('description')->nullable()->after('name');
            $table->string('category')->nullable()->after('description');
            $table->string('image')->nullable()->default('produtos/box_default.svg')->after('category');
            $table->integer('garantia')->nullable()->after('image');
            $table->string('email_support')->nullable()->after('image');
            $table->string('name_exibition')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('description');
            $table->dropColumn('category');
            $table->dropColumn('image');
            $table->dropColumn('garantia');
            $table->dropColumn('email_support');
            $table->dropColumn('name_exibition');
        });
    }
};
