<?php

namespace App\Console\Commands;

use App\Models\Aluno;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTestAluno extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aluno:create-test 
                            {--name=Aluno Teste : Nome do aluno}
                            {--email=aluno@teste.com : Email do aluno}
                            {--password=12345678 : Senha do aluno}
                            {--cpf= : CPF do aluno (formato: 000.000.000-00)}
                            {--produto= : ID do produto para associar (opcional)}
                            {--with-pedido : Cria um pedido pago associado}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um aluno de teste para desenvolvimento da área de membros';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎓 Criando aluno de teste...');
        $this->newLine();

        // Dados do aluno
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $cpf = $this->option('cpf') ?: $this->generateFakeCPF();

        // Verifica se o aluno já existe
        $aluno = Aluno::where('email', $email)->first();
        $alunoFoiCriado = false;
        
        if ($aluno) {
            $this->warn("⚠️  Aluno com email {$email} já existe!");
            if (!$this->confirm('Deseja usar o aluno existente? (yes) ou cancelar? (no)', true)) {
                return Command::FAILURE;
            }
            $this->info("✅ Usando aluno existente (ID: {$aluno->id})");
        } else {
            // Verifica se o CPF já existe
            if (Aluno::where('cpf', $cpf)->exists()) {
                $this->warn("⚠️  Aluno com CPF {$cpf} já existe!");
                if (!$this->confirm('Deseja continuar mesmo assim?', false)) {
                    return Command::FAILURE;
                }
            }

            // Cria o aluno
            $aluno = Aluno::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'cpf' => $cpf,
                'celular' => '(11) 99999-9999',
                'status' => 'ativo',
            ]);
            $alunoFoiCriado = true;
        }

        if ($alunoFoiCriado) {
            $this->info("✅ Aluno criado com sucesso!");
        } else {
            $this->info("✅ Usando aluno existente!");
        }
        
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $aluno->id],
                ['Nome', $aluno->name],
                ['Email', $aluno->email],
                ['CPF', $aluno->cpf],
                ['Senha', $password],
            ]
        );

        // Cria pedido se solicitado
        if ($this->option('with-pedido')) {
            $this->newLine();
            $this->info('📦 Verificando pedido pago...');

            $produtoId = $this->option('produto');
            
            // Se não especificou produto, busca o primeiro disponível
            if (!$produtoId) {
                $produto = Produto::where('status', 1)->first();
                if (!$produto) {
                    $this->error('❌ Nenhum produto ativo encontrado! Crie um produto primeiro.');
                    $this->info("💡 Você pode criar um pedido depois usando: php artisan aluno:add-pedido {$aluno->id}");
                    return Command::SUCCESS;
                }
                $produtoId = $produto->id;
            } else {
                $produto = Produto::find($produtoId);
                if (!$produto) {
                    $this->error("❌ Produto com ID {$produtoId} não encontrado!");
                    return Command::FAILURE;
                }
            }

            // Verifica se já existe um pedido pago para este aluno e produto
            $pedidoExistente = Pedido::where('aluno_id', $aluno->id)
                ->where('produto_id', $produtoId)
                ->where('status', 'pago')
                ->first();

            if ($pedidoExistente) {
                $this->warn("⚠️  Já existe um pedido pago para este aluno e produto!");
                $this->info("📦 Usando pedido existente (ID: {$pedidoExistente->id})");
                $pedido = $pedidoExistente;
            } else {
                // Cria o pedido pago
                $pedido = Pedido::create([
                    'produto_id' => $produtoId,
                    'aluno_id' => $aluno->id,
                    'valor' => $produto->price,
                    'valor_liquido' => $produto->price,
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
            }

            $this->table(
                ['Campo', 'Valor'],
                [
                    ['ID do Pedido', $pedido->id],
                    ['Produto', $produto->name],
                    ['Valor', 'R$ ' . number_format($pedido->valor, 2, ',', '.')],
                    ['Status', $pedido->status],
                ]
            );
        }

        $this->newLine();
        $this->info('🎉 Aluno de teste criado com sucesso!');
        $this->info("📧 Email: {$email}");
        $this->info("🔑 Senha: {$password}");
        $this->info("🌐 Acesse: /alunos (faça login com as credenciais acima)");
        
        return Command::SUCCESS;
    }

    /**
     * Gera um CPF fake válido para testes
     */
    private function generateFakeCPF(): string
    {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($d1 % 11);
        if ($d1 >= 10) $d1 = 0;
        
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($d2 % 11);
        if ($d2 >= 10) $d2 = 0;
        
        return sprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $d1, $d2);
    }
}
