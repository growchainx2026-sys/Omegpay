<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use App\Models\Produto;
use App\Models\ProgressoAluno;

class Aluno extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'cpf',
        'celular',
        'cep',
        'street',
        'uf',
        'city',
        'address',
        'status',
        'produto_id'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function progressos()
    {
        return $this->hasMany(ProgressoAluno::class);
    }

    public function progressoVideo($videoId)
    {
        return $this->progressos()->where('video_id', $videoId)->first();
    }

    /**
     * Calcula o progresso geral do aluno em um produto
     */
    public function progressoProduto($produtoId)
    {
        $produto = Produto::find($produtoId);
        if (!$produto) return 0;

        $totalVideos = 0;
        $videosConcluidos = 0;

        foreach ($produto->modulosAtivos as $modulo) {
            foreach ($modulo->sessoesAtivas as $sessao) {
                foreach ($sessao->videosAtivos as $video) {
                    $totalVideos++;
                    $progresso = $this->progressoVideo($video->id);
                    if ($progresso && $progresso->concluido) {
                        $videosConcluidos++;
                    }
                }
            }
        }

        return $totalVideos > 0 ? round(($videosConcluidos / $totalVideos) * 100) : 0;
    }

    /**
     * Verifica se uma sessão está liberada para o aluno
     * Uma sessão só é liberada se todas as sessões anteriores do mesmo módulo foram concluídas
     */
    public function sessaoLiberada($sessaoId, $produtoId)
    {
        $sessao = \App\Models\Sessao::find($sessaoId);
        if (!$sessao) return false;

        // Verifica se a sessão pertence ao produto
        if ($sessao->modulo->produto_id != $produtoId) return false;

        // Se a sessão tem data de liberação, verifica se já passou
        if ($sessao->liberar_em && now()->lt($sessao->liberar_em)) {
            return false;
        }

        // Se a sessão tem "liberar em X dias", verifica a data de acesso do aluno ao curso
        if ($sessao->liberar_em_dias !== null) {
            $pedido = $this->pedidos()->where('produto_id', $produtoId)->where('status', 'pago')->orderBy('created_at')->first();
            if (!$pedido) return false;
            $releaseAt = $pedido->created_at->copy()->addDays((int) $sessao->liberar_em_dias);
            if (now()->lt($releaseAt)) return false;
        }

        // Primeira sessão do módulo sempre está liberada (após data se houver)
        $primeiraSessao = $sessao->modulo->sessoesAtivas()->orderBy('ordem')->first();
        if ($primeiraSessao && $primeiraSessao->id == $sessaoId) {
            return true;
        }

        // Verifica se todas as sessões anteriores foram concluídas
        $sessoesAnteriores = $sessao->modulo->sessoesAtivas()
            ->where('ordem', '<', $sessao->ordem)
            ->orderBy('ordem')
            ->get();

        foreach ($sessoesAnteriores as $sessaoAnterior) {
            $todosVideosConcluidos = true;
            foreach ($sessaoAnterior->videosAtivos as $video) {
                $progresso = $this->progressoVideo($video->id);
                if (!$progresso || !$progresso->concluido) {
                    $todosVideosConcluidos = false;
                    break;
                }
            }
            if (!$todosVideosConcluidos) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se uma sessão está concluída (todos os vídeos foram assistidos)
     */
    public function sessaoConcluida($sessaoId, $produtoId)
    {
        $sessao = \App\Models\Sessao::find($sessaoId);
        if (!$sessao) return false;

        // Verifica se a sessão pertence ao produto
        if ($sessao->modulo->produto_id != $produtoId) return false;

        // Verifica se todos os vídeos foram concluídos
        $videos = $sessao->videosAtivos;
        if ($videos->isEmpty()) return false;

        foreach ($videos as $video) {
            $progresso = $this->progressoVideo($video->id);
            if (!$progresso || !$progresso->concluido) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifica se um módulo está liberado para o aluno
     * Um módulo só é liberado se o módulo anterior foi concluído
     */
    public function moduloLiberado($moduloId, $produtoId)
    {
        $modulo = \App\Models\Modulo::find($moduloId);
        if (!$modulo) return false;

        // Verifica se o módulo pertence ao produto
        if ($modulo->produto_id != $produtoId) return false;

        // Se o módulo tem data de liberação, verifica se já passou
        if ($modulo->liberar_em && now()->lt($modulo->liberar_em)) {
            return false;
        }

        // Se o módulo tem "liberar em X dias", verifica a data de acesso do aluno ao curso
        if ($modulo->liberar_em_dias !== null) {
            $pedido = $this->pedidos()->where('produto_id', $produtoId)->where('status', 'pago')->orderBy('created_at')->first();
            if (!$pedido) return false;
            $releaseAt = $pedido->created_at->copy()->addDays((int) $modulo->liberar_em_dias);
            if (now()->lt($releaseAt)) return false;
        }

        // Primeiro módulo sempre está liberado (após data se houver)
        $primeiroModulo = \App\Models\Produto::find($produtoId)
            ->modulosAtivos()
            ->orderBy('ordem')
            ->first();
        
        if ($primeiroModulo && $primeiroModulo->id == $moduloId) {
            return true;
        }

        // Verifica se o módulo anterior foi concluído
        $modulosAnteriores = \App\Models\Produto::find($produtoId)
            ->modulosAtivos()
            ->where('ordem', '<', $modulo->ordem)
            ->orderBy('ordem')
            ->get();

        foreach ($modulosAnteriores as $moduloAnterior) {
            // Verifica se todas as sessões do módulo anterior foram concluídas
            foreach ($moduloAnterior->sessoesAtivas as $sessao) {
                foreach ($sessao->videosAtivos as $video) {
                    $progresso = $this->progressoVideo($video->id);
                    if (!$progresso || !$progresso->concluido) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
