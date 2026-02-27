<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Efi extends Model
{
    protected $fillable = [
        'client_id',
        'client_secret',
        'chave_pix',
        'identificador_conta',
        'gateway_id',
        'cert',
        'taxa_pix_cash_in',
        'taxa_pix_cash_out',
        'billet_tx_fixed',
        'billet_tx_percent',
        'card_tx_percent',
        'card_tx_fixed',
        'billet_days_availability',
        'card_days_availability',
    ];
}
