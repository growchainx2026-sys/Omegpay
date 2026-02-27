<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupon extends Model
{
    protected $table = "cupons";

    protected $fillable = [
        'codigo',
        'type',
        'desconto',
        'data_inicio',
        'data_termino',
        'aplicar_orderbumps',
        'produto_id',
        'usage'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_termino' => 'datetime',
    ];
}
