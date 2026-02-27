<?php

namespace App\Console\Commands;

use App\Models\Aluno;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AddPedidoToAluno extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aluno:add-pedido 
                            {aluno_id : ID do aluno}
                            {--produto= : ID do produto (se não informado, usa o primeiro produto ativo)}
                            {--valor= : Valor do pedido (se não informado, usa o preço do produto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adiciona um pedido pago a um aluno existente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alunoId = $this->argument('aluno_id');
        $aluno = Aluno::find($alunoId);

        if (!$aluno) {
            $this->error("❌ Aluno com ID {$alunoId} não encontrado!");
            return Command::FAILURE;
        }

        $this->info("🎓 Aluno encontrado: {$aluno->name} ({$aluno->email})");
        $this->newLine();

        // Busca produto
        $produtoId = $this->option('produto');
        
        if (!$produtoId) {
            $produto = Produto::where('status', 1)->first();
            if (!$produto) {
                $this->error('❌ Nenhum produto ativo encontrado!');
                return Command::FAILURE;
            }
            $produtoId = $produto->id;
        } else {
            $produto = Produto::find($produtoId);
            if (!$produto) {
                $this->error("❌ Produto com ID {$produtoId} não encontrado!");
                return Command::FAILURE;
            }
        }

        $valor = $this->option('valor') ?: $produto->price;

        $this->info("📦 Produto: {$produto->name}");
        $this->info("💰 Valor: R$ " . number_format($valor, 2, ',', '.'));
        $this->newLine();

        // Cria o pedido pago
        $pedido = Pedido::create([
            'produto_id' => $produtoId,
            'aluno_id' => $aluno->id,
            'valor' => $valor,
            'valor_liquido' => $valor,
            'metodo' => 'pix',
            'status' => 'pago',
            'idTransaction' => 'TEST-' . Str::random(10),
            'bumps' => [],
            'comprador' => [
                'name' => $aluno->name,
                'email' => $aluno->email,
                'cpf' => $aluno->cpf,
                'phone' => $aluno->celular ?? '(11) 99999-9999',
            ],
            'pagamento' => [
                'metodo' => 'pix',
            ],
        ]);

        $this->info("✅ Pedido criado e marcado como pago!");
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID do Pedido', $pedido->id],
                ['Aluno', $aluno->name],
                ['Produto', $produto->name],
                ['Valor', 'R$ ' . number_format($pedido->valor, 2, ',', '.')],
                ['Status', $pedido->status],
            ]
        );

        return Command::SUCCESS;
    }
}
