<?php

use App\Models\Aluno;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Rotas de desenvolvimento - REMOVER EM PRODUÇÃO
 * 
 * Estas rotas permitem criar alunos de teste facilmente.
 * IMPORTANTE: Desabilite ou remova estas rotas em produção!
 */

// Apenas em ambiente local/desenvolvimento
if (app()->environment(['local', 'development']) || config('app.debug')) {
    
    Route::get('/dev/create-aluno', function () {
        return view('dev.create-aluno');
    })->name('dev.create-aluno');

    Route::post('/dev/create-aluno', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:alunos,email',
            'password' => 'required|string|min:6',
            'cpf' => 'nullable|string',
            'produto_id' => 'nullable|exists:produtos,id',
            'with_pedido' => 'nullable|boolean',
        ]);

        $cpf = $request->cpf;
        if (!$cpf) {
            // Gera CPF fake
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
            
            $cpf = sprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', $n1, $n2, $n3, $n4, $n5, $n6, $n7, $n8, $n9, $d1, $d2);
        }

        $aluno = Aluno::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $cpf,
            'celular' => '(11) 99999-9999',
            'status' => 'ativo',
        ]);

        $message = "✅ Aluno criado com sucesso!<br>";
        $message .= "📧 Email: {$request->email}<br>";
        $message .= "🔑 Senha: {$request->password}<br>";

        if ($request->with_pedido) {
            $produtoId = $request->produto_id;
            
            if (!$produtoId) {
                $produto = Produto::where('status', 1)->first();
                if ($produto) {
                    $produtoId = $produto->id;
                }
            } else {
                $produto = Produto::find($produtoId);
            }

            if ($produto) {
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

                $message .= "📦 Pedido criado e marcado como pago!<br>";
                $message .= "💰 Produto: {$produto->name} - R$ " . number_format($produto->price, 2, ',', '.') . "<br>";
            }
        }

        $message .= "<br>🌐 <a href='/alunos'>Acesse a área de membros</a>";

        return redirect()->route('dev.create-aluno')->with('success', $message);
    })->name('dev.create-aluno.post');

    Route::get('/dev/list-alunos', function () {
        $alunos = Aluno::with(['pedidos' => function($q) {
            $q->where('status', 'pago');
        }])->get();
        
        return view('dev.list-alunos', compact('alunos'));
    })->name('dev.list-alunos');
}
