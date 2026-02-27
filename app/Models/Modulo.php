<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    
    protected $fillable = [
        'produto_id',
        'nome',
        'descricao',
        'icone',
        'ordem',
        'status',
        'liberar_em',
        'liberar_em_dias',
    ];

    protected $casts = [
        'liberar_em' => 'datetime',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function sessoes()
    {
        return $this->hasMany(Sessao::class)->orderBy('ordem');
    }

    public function sessoesAtivas()
    {
        return $this->hasMany(Sessao::class)->where('status', 1)->orderBy('ordem');
    }
}
