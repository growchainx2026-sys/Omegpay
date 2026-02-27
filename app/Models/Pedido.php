<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pedido extends Model
{
    protected $fillable = [
        'produto_id',
        'valor',
        'valor_liquido',
        'taxa',
        'bumps',
        'metodo',
        'comprador',
        'pagamento',
        'coprodutor',
        'afiliado',
        'aluno_id',
        'status',
        'idTransaction',
        'cupon_code',
        'cupon_desconto',
        'uuid'
    ];

    protected $casts = [
        'comprador' => 'array',
        'pagamento' => 'array',
        'coprodutor' => 'array',
        'afiliado' => 'array',
        'bumps' => 'array',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id', 'id');
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'aluno_id', 'id');
    }

    public function historico_afiliado()
    {
        return $this->hasOne(AffiliateHistory::class, 'pedido_id', 'id');
    }

    // Adiciona UUID automaticamente
    protected static function booted()
    {
        static::creating(function ($pedido) {
            if (empty($pedido->uuid)) {
                $pedido->uuid = (string) Str::uuid();
            }
        });
    }
}
