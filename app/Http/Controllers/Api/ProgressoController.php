<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgressoAluno;
use App\Models\Video;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgressoController extends Controller
{
    public function updateProgress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_id' => 'required|exists:videos,id',
            'tempo_assistido' => 'required|integer|min:0',
            'tempo_total' => 'required|integer|min:0',
            'ultima_posicao' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $aluno = auth('aluno')->user();
        if (!$aluno) {
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        $video = Video::findOrFail($request->video_id);
        $produto = $video->sessao->modulo->produto;

        // Verifica se o aluno tem acesso ao produto
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        // Calcula se o vídeo foi concluído (90% ou mais assistido)
        $porcentagem = $request->tempo_total > 0 
            ? ($request->tempo_assistido / $request->tempo_total) * 100 
            : 0;
        
        $concluido = $porcentagem >= 90;

        $progresso = ProgressoAluno::updateOrCreate(
            [
                'aluno_id' => $aluno->id,
                'video_id' => $video->id,
            ],
            [
                'produto_id' => $produto->id,
                'tempo_assistido' => $request->tempo_assistido,
                'tempo_total' => $request->tempo_total,
                'ultima_posicao' => $request->ultima_posicao ?? 0,
                'concluido' => $concluido,
            ]
        );

        return response()->json([
            'success' => true,
            'progresso' => $progresso,
            'porcentagem' => round($porcentagem, 2),
            'concluido' => $concluido,
        ]);
    }

    public function getProgress(Request $request, $produtoId)
    {
        $aluno = auth('aluno')->user();
        if (!$aluno) {
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        $produto = Produto::findOrFail($produtoId);

        // Verifica se o aluno tem acesso ao produto
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $progressoGeral = $aluno->progressoProduto($produtoId);
        
        $progressos = [];
        foreach ($produto->modulosAtivos as $modulo) {
            foreach ($modulo->sessoesAtivas as $sessao) {
                foreach ($sessao->videosAtivos as $video) {
                    $progresso = $aluno->progressoVideo($video->id);
                    $progressos[$video->id] = [
                        'video_id' => $video->id,
                        'tempo_assistido' => $progresso ? $progresso->tempo_assistido : 0,
                        'tempo_total' => $progresso ? $progresso->tempo_total : 0,
                        'concluido' => $progresso ? $progresso->concluido : false,
                        'ultima_posicao' => $progresso ? $progresso->ultima_posicao : 0,
                        'porcentagem' => $progresso ? $progresso->porcentagem : 0,
                    ];
                }
            }
        }

        return response()->json([
            'progresso_geral' => $progressoGeral,
            'progressos' => $progressos,
        ]);
    }
}
