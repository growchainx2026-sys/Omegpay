<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Getpay2 extends Model
{
    protected $table = 'getpays2';

    protected $fillable = [
        'url_base',
        'client_id',
        'client_secret',
        'webhook_token_deposit',
        'webhook_token_withdraw',
        'taxa_cash_in',
        'taxa_cash_out',
    ];
}
