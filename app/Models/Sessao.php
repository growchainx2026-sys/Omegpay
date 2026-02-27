<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sessao extends Model
{
    protected $table = 'sessoes';
    
    protected $fillable = [
        'modulo_id',
        'nome',
        'descricao',
        'capa',
        'ordem',
        'status',
        'liberar_em',
        'liberar_em_dias',
    ];

    protected $casts = [
        'liberar_em' => 'datetime',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('ordem');
    }

    public function videosAtivos()
    {
        return $this->hasMany(Video::class)->where('status', 1)->orderBy('ordem');
    }
}
