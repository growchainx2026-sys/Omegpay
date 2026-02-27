<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Witetec extends Model
{
    protected $table = "witetec";
    protected $fillable = [
        'url',
        'api_token',
        'taxa_cash_in',
        'taxa_cash_out',
        'tx_billet_fixed',
        'tx_billet_percent',
        'tx_card_fixed',
        'tx_card_percent',
    ];
}
