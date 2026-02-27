<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionOut extends Model
{

    public $table = "transactions_cash_out";
    protected $fillable = [
        'user_id',
        'external_id',
        'amount',
        'recebedor_name',
        'recebedor_cpf',
        'pixKeyType',
        'pixKey',
        'status',
        'idTransaction',
        'end2end',
        'taxa_cash_out',
        'taxa_fixa',
        'type',
        'plataforma',
        'cash_out_liquido',
        'request_ip',
        'request_domain',
        'callbackUrl',
        'descricao_transacao',
        'adquirente_ref'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function callback()
    {
        return $this->hasOne(Callback::class, 'transaction_cash_out_id');
    }
}
