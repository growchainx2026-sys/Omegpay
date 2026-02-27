<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProdutoIdNullableOnCheckoutsTable extends Migration
{
    public function up()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            // Remove foreign key existente
            $table->dropForeign(['produto_id']);

            // Torna a coluna nullable
            $table->unsignedBigInteger('produto_id')->nullable()->change();

            // Recria a foreign key com ON DELETE SET NULL
            $table->foreign('produto_id')
                  ->references('id')
                  ->on('produtos')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropForeign(['produto_id']);
            $table->unsignedBigInteger('produto_id')->nullable(false)->change();
            $table->foreign('produto_id')
                  ->references('id')
                  ->on('produtos')
                  ->onDelete('cascade');
        });
    }
}
