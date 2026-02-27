<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressoAluno extends Model
{
    protected $table = 'progresso_alunos';
    
    protected $fillable = [
        'aluno_id',
        'video_id',
        'produto_id',
        'tempo_assistido',
        'tempo_total',
        'concluido',
        'ultima_posicao',
    ];

    protected $casts = [
        'concluido' => 'boolean',
        'tempo_assistido' => 'integer',
        'tempo_total' => 'integer',
        'ultima_posicao' => 'integer',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    /**
     * Calcula a porcentagem de progresso
     */
    public function getPorcentagemAttribute()
    {
        if ($this->tempo_total > 0) {
            return min(100, round(($this->tempo_assistido / $this->tempo_total) * 100));
        }
        return 0;
    }
}
