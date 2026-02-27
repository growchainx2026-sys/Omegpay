<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopularDadosTesteFiltros extends Command
{
    protected $signature = 'teste:popular-filtros 
                            {--email= : E-mail do cliente a popular}
                            {--entradas=15 : Quantidade de entradas (depósitos) a criar}
                            {--saidas=10 : Quantidade de saídas a criar}';

    protected $description = 'Popula a conta de um cliente com entradas e saídas em várias datas para testar filtros e ordenação (Hoje, Semana, Mês, Personalizado, Todos).';

    public function handle(): int
    {
        $email = $this->option('email');
        $numEntradas = (int) $this->option('entradas');
        $numSaidas = (int) $this->option('saidas');

        $user = $email
            ? User::where('email', $email)->where('permission', '!=', 'admin')->first()
            : User::where('permission', '!=', 'admin')->first();

        if (!$user) {
            $this->error('Nenhum cliente encontrado. Use --email= ou crie um usuário não-admin.');
            return self::FAILURE;
        }

        $this->info("Cliente: {$user->name} (ID: {$user->id}, Email: {$user->email})");
        $this->newLine();

        $base = Carbon::now();
        $entradasInseridas = 0;
        $saidasInseridas = 0;

        // Datas distribuídas: hoje, esta semana, este mês, e até ~3 meses atrás
        $datasEntrada = $this->gerarDatas($numEntradas, $base);
        $datasSaida = $this->gerarDatas($numSaidas, $base);

        DB::beginTransaction();
        try {
            foreach ($datasEntrada as $i => $data) {
                $this->inserirEntrada($user->id, $data, $i);
                $entradasInseridas++;
            }
            foreach ($datasSaida as $i => $data) {
                $this->inserirSaida($user->id, $data, $i);
                $saidasInseridas++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Erro ao inserir: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info("Entradas criadas: {$entradasInseridas}");
        $this->info("Saídas criadas: {$saidasInseridas}");
        $this->newLine();
        $this->info('Faça login como este cliente e teste em Entradas e Saídas os filtros (Hoje, Semana, Mês, Personalizado, Todos) e a ordenação pela coluna Data.');
        return self::SUCCESS;
    }

    private function gerarDatas(int $quantidade, Carbon $base): array
    {
        $datas = [];
        $metodos = [
            fn () => $base->copy()->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
            fn () => $base->copy()->subDays(rand(1, 6))->subHours(rand(0, 23)),
            fn () => $base->copy()->subDays(rand(7, 28))->subHours(rand(0, 23)),
            fn () => $base->copy()->subDays(rand(29, 90))->subHours(rand(0, 23)),
        ];
        for ($i = 0; $i < $quantidade; $i++) {
            $datas[] = $metodos[$i % 4]();
        }
        shuffle($datas);
        return $datas;
    }

    private function inserirEntrada(int $userId, Carbon $createdAt, int $index): void
    {
        $valor = round(rand(5000, 150000) / 100, 2);
        $taxa = round($valor * 0.02, 2);
        $liquido = $valor - $taxa;
        $statuses = ['pago', 'pago', 'pago', 'pendente', 'revisao'];
        $methods = ['pix', 'pix', 'card', 'billet'];
        $status = $statuses[$index % count($statuses)];
        $method = $methods[$index % count($methods)];

        DB::table('transactions_cash_in')->insert([
            'user_id' => $userId,
            'external_id' => null,
            'amount' => $valor,
            'client_name' => 'Cliente Teste ' . ($index + 1),
            'client_cpf' => '000.000.000-' . str_pad((string) ($index % 100), 2, '0', STR_PAD_LEFT),
            'end2end' => null,
            'status' => $status,
            'idTransaction' => 'test_filtro_in_' . $createdAt->format('YmdHis') . '_' . $index,
            'cash_in_liquido' => $liquido,
            'qrcode_pix' => '',
            'paymentcode' => '',
            'paymentCodeBase64' => '',
            'adquirente_ref' => 'test',
            'taxa_cash_in' => $taxa,
            'taxa_fixa' => 0,
            'executor_ordem' => 'test',
            'descricao_transacao' => 'Depósito teste filtros #' . ($index + 1),
            'request_ip' => '127.0.0.1',
            'request_domain' => 'gtpag.com',
            'type' => 'cash',
            'plataforma' => 'web',
            'callbackUrl' => null,
            'method' => $method,
            'dias_recebimento' => 0,
            'taxa_reserva' => 0,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }

    private function inserirSaida(int $userId, Carbon $createdAt, int $index): void
    {
        $valor = round(rand(2000, 80000) / 100, 2);
        $taxa = round($valor * 0.01, 2);
        $liquido = $valor - $taxa;
        $statuses = ['pago', 'pago', 'pendente', 'cancelado'];
        $status = $statuses[$index % count($statuses)];

        $user = User::find($userId);
        $userIdCol = (string) $userId;

        DB::table('transactions_cash_out')->insert([
            'user_id' => $userIdCol,
            'external_id' => null,
            'amount' => $valor,
            'recebedor_name' => $user ? $user->name : 'Recebedor Teste',
            'recebedor_cpf' => $user && $user->cpf_cnpj ? $user->cpf_cnpj : '000.000.000-00',
            'pixKeyType' => 'cpf',
            'pixKey' => '000.000.000-00',
            'status' => $status,
            'idTransaction' => 'test_filtro_out_' . $createdAt->format('YmdHis') . '_' . $index,
            'end2end' => '',
            'taxa_cash_out' => $taxa,
            'taxa_fixa' => 0,
            'type' => 'cash',
            'plataforma' => 'web',
            'cash_out_liquido' => $liquido,
            'request_ip' => '127.0.0.1',
            'request_domain' => 'gtpag.com',
            'callbackUrl' => null,
            'adquirente_ref' => 'test',
            'descricao_transacao' => 'Saque teste filtros #' . ($index + 1),
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);
    }
}
