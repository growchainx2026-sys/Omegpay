<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'transaction_id',
        'codigo_voucher',
        'client_name',
        'client_cpf',
        'client_email',
        'client_telefone',
        'valor',
        'status',
        'payment_method',
        'data_pagamento',
        'descricao',
        'ativacao',
    ];

    protected $casts = [
        'data_pagamento' => 'datetime',
        'valor' => 'decimal:2',
    ];

    /**
     * ✅ RELACIONAMENTO COM LINK (OBRIGATÓRIO!)
     */
    public function link()
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Relacionamento com transação (opcional)
     */
    public function transaction()
    {
        return $this->belongsTo(TransactionIn::class, 'transaction_id');
    }

    /**
     * Relacionamento com usuário via link
     */
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Link::class,
            'id',      // Foreign key em links
            'id',      // Foreign key em users
            'link_id', // Local key em vouchers
            'user_id'  // Local key em links
        );
    }
}