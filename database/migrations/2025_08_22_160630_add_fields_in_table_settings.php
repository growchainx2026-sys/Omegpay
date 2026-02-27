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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('mail_host')->nullable()->default(NULL)->after('rev');
            $table->integer('mail_port')->nullable()->default(465)->after('mail_host');
            $table->string('mail_username')->nullable()->default(NULL)->after('mail_port');
            $table->string('mail_password')->nullable()->default(NULL)->after('mail_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('mail_host');
            $table->dropColumn('mail_port');
            $table->dropColumn('mail_username');
            $table->dropColumn('mail_password');
        });
    }
};
