<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Altera a coluna description de VARCHAR(255) para TEXT para suportar descrições longas.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE produtos MODIFY description TEXT NULL');
        } else {
            DB::statement('ALTER TABLE produtos ALTER COLUMN description TYPE TEXT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE produtos MODIFY description VARCHAR(255) NULL');
        } else {
            DB::statement('ALTER TABLE produtos ALTER COLUMN description TYPE VARCHAR(255)');
        }
    }
};
