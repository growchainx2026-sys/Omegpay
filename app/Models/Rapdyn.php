<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rapdyn extends Model
{
    protected $table = 'rapdyns';

    protected $fillable = [
        'url_base',
        'api_token',
        'webhook_token_deposit',
        'webhook_token_withdraw',
        'taxa_cash_in',
        'taxa_cash_out',
    ];
}
