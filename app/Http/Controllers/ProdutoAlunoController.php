<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Aluno;
use Illuminate\Http\Request;

class ProdutoAlunoController extends Controller
{
    public function detalhesAluno($produtoId, $alunoId)
    {
        $produto = Produto::findOrFail($produtoId);
        
        // Verifica se o usuário é dono do produto
        if ($produto->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }
        
        $aluno = Aluno::findOrFail($alunoId);
        
        // Verifica se o aluno tem acesso ao produto
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return response()->json(['error' => 'Aluno não tem acesso a este produto'], 404);
        }
        
        $progressoGeral = $aluno->progressoProduto($produto->id);
        
        // Conta vídeos
        $totalVideos = 0;
        $videosConcluidos = 0;
        $modulosData = [];
        
        foreach ($produto->modulosAtivos as $modulo) {
            $moduloVideos = 0;
            $moduloConcluidos = 0;
            
            foreach ($modulo->sessoesAtivas as $sessao) {
                foreach ($sessao->videosAtivos as $video) {
                    $totalVideos++;
                    $moduloVideos++;
                    
                    $progresso = $aluno->progressoVideo($video->id);
                    if ($progresso && $progresso->concluido) {
                        $videosConcluidos++;
                        $moduloConcluidos++;
                    }
                }
            }
            
            $moduloProgresso = $moduloVideos > 0 ? round(($moduloConcluidos / $moduloVideos) * 100) : 0;
            
            $modulosData[] = [
                'nome' => $modulo->nome,
                'progresso' => $moduloProgresso,
                'videos_concluidos' => $moduloConcluidos,
                'total_videos' => $moduloVideos,
            ];
        }
        
        return response()->json([
            'aluno' => [
                'name' => $aluno->name,
                'email' => $aluno->email,
                'cpf' => $aluno->cpf,
                'avatar' => $aluno->avatar,
                'avatar_url' => $aluno->avatar ? asset($aluno->avatar) : null,
            ],
            'progresso_geral' => $progressoGeral,
            'videos_concluidos' => $videosConcluidos,
            'total_videos' => $totalVideos,
            'data_compra' => $pedido->created_at->format('d/m/Y H:i'),
            'modulos' => $modulosData,
        ]);
    }
}
