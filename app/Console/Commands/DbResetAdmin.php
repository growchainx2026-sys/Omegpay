<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DbResetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-admin
                            {--email= : Email do admin}
                            {--password= : Senha do admin}
                            {--fresh : Rodar migrate:fresh antes (zera tudo)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zera o banco (opcional), recria tabelas e cria conta admin para login';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Omegpay: Reset DB + Admin ===');

        $email = $this->option('email') ?: $this->ask('Email do admin', 'admin@omegpay.com');
        $password = $this->option('password') ?: $this->ask('Senha do admin (mín. 8 caracteres)', 'admin123');

        if (strlen($password) < 8) {
            $this->error('A senha deve ter no mínimo 8 caracteres.');
            return 1;
        }

        if ($this->option('fresh')) {
            $this->warn('Rodando migrate:fresh (todas as tabelas serão dropadas)...');
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->info('Migrate:fresh concluído.');
        }

        DB::beginTransaction();
        try {
            // Garantir que existe um Setting (usado em todo o app)
            if (! Setting::first()) {
                Setting::create([
                    'software_name' => 'Omegpay',
                    'software_description' => 'Plataforma de pagamentos',
                    'adquirente_default' => 'efi',
                    'taxa_cash_in' => 5,
                    'taxa_cash_out' => 5,
                    'deposito_minimo' => 10,
                    'deposito_maximo' => 50000,
                    'saque_minimo' => 10,
                    'saque_maximo' => 50000,
                    'saques_dia' => 10,
                ]);
                $this->info('Setting criado.');
            }

            // Criar ou atualizar admin
            $admin = User::where('email', $email)->first();

            if ($admin) {
                $admin->update([
                    'password' => Hash::make($password),
                    'permission' => 'admin',
                    'status' => 'aprovado',
                ]);
                $this->info("Admin atualizado: {$email}");
            } else {
                User::create([
                    'name' => 'Administrador',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'username' => 'admin_' . Str::random(6),
                    'cpf_cnpj' => '00000000000',
                    'permission' => 'admin',
                    'status' => 'aprovado',
                    'saldo' => 0,
                    'codigo_referencia' => Str::random(8),
                    'taxa_percentual' => 0,
                    'volume_transacional' => 0,
                    'valor_pago_taxa' => 0,
                ]);
                $this->info("Admin criado: {$email}");
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Erro: ' . $e->getMessage());
            return 1;
        }

        $this->newLine();
        $this->info('Pronto! Use as credenciais abaixo para logar:');
        $this->table(['Email', 'Senha'], [[$email, $password]]);
        $this->newLine();

        return 0;
    }
}
