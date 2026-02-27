<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    protected $fillable = [
        'status',
        'message',
        'transaction_cash_in_id',
        'transaction_cash_out_id'
    ];

    public function transactionIn()
    {
        return $this->belongsTo(TransactionIn::class, 'transaction_cash_in_id');
    }

    public function transactionOut()
    {
        return $this->belongsTo(TransactionOut::class, 'transaction_cash_out_id');
    }
}
