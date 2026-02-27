<?php

namespace App\Console\Commands;

use App\Models\Aluno;
use Illuminate\Console\Command;

class ListAlunos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aluno:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista todos os alunos cadastrados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alunos = Aluno::with('pedidos')->get();

        if ($alunos->isEmpty()) {
            $this->warn('⚠️  Nenhum aluno cadastrado ainda.');
            $this->info('💡 Crie um aluno de teste usando: php artisan aluno:create-test --with-pedido');
            return Command::SUCCESS;
        }

        $this->info("📋 Total de alunos: {$alunos->count()}");
        $this->newLine();

        $data = [];
        foreach ($alunos as $aluno) {
            $pedidosPagos = $aluno->pedidos()->where('status', 'pago')->count();
            $data[] = [
                'ID' => $aluno->id,
                'Nome' => $aluno->name,
                'Email' => $aluno->email,
                'CPF' => $aluno->cpf ?? '-',
                'Pedidos Pagos' => $pedidosPagos,
            ];
        }

        $this->table(
            ['ID', 'Nome', 'Email', 'CPF', 'Pedidos Pagos'],
            $data
        );

        return Command::SUCCESS;
    }
}
