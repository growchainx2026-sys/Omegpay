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
        Schema::table('users', function (Blueprint $table) {
            $table->string('cpf_cnpj')->unique();
            $table->string('username')->unique();
            $table->string('data_nascimento')->nullable()->default(NULL);
            $table->string('telefone')->nullable()->default(NULL)->unique();
            $table->decimal('saldo', 10,2)->default(0.00);
            $table->decimal('total_transacoes', 10,2)->default(0.00);
            $table->enum('permission', ['user', 'admin', 'dev', 'affiliate'])->default('user');
            $table->string('avatar')->nullable()->default('avatars/default.png');
            $table->enum('status', ['aguardando', 'analise','aprovado', 'reprovado'])->default('aguardando');
            $table->timestamp('data_cadastro')->default(now());
            $table->string('ip_user')->nullable()->default(NULL);
            $table->integer('transacoes_aproved')->default(0);
            $table->integer('transacoes_recused')->default(0);
            $table->decimal('valor_sacado', 10,2)->default(0.00);
            $table->decimal('valor_saque_pendente', 10,2)->default(0.00);
            $table->decimal('taxa_cash_in', 10,2)->default(5.00);
            $table->decimal('taxa_cash_out', 10,2)->default(5.00);
            $table->boolean('banido')->default(false);
            $table->longText('clientId')->nullable()->default(NULL);
            $table->longText('secret')->nullable()->default(NULL);
            $table->decimal('taxa_percentual', 10,2);
            $table->decimal('volume_transacional', 10,2);
            $table->decimal('valor_pago_taxa', 10,2);
            $table->string('cep')->nullable()->default(NULL);
            $table->string('rua')->nullable()->default(NULL);
            $table->string('estado')->nullable()->default(NULL);
            $table->string('cidade')->nullable()->default(NULL);
            $table->string('bairro')->nullable()->default(NULL);
            $table->string('numero_residencia')->nullable()->default(NULL);
            $table->string('complemento')->nullable()->default(NULL);
            $table->string('foto_rg_frente')->nullable()->default(NULL);
            $table->string('foto_rg_verso')->nullable()->default(NULL);
            $table->string('selfie_rg')->nullable()->default(NULL);
            $table->decimal('media_faturamento', 10,2)->default(0.00);
            $table->string('codigo_referencia')->nullable()->default(NULL);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
};
