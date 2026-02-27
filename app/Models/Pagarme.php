<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagarme extends Model
{
    protected $table = "pagarme";

    protected $fillable = [
        'secret',
        'tx_pix_cash_in',
        'tx_pix_cash_out',
        'tx_billet_fixed',
        'tx_billet_percent',
        'tx_card_fixed',
        'tx_card_percent',
        '1x','2x','3x','4x',
        '5x','6x','7x','8x',
        '9x','10x','11x','12x',
    ];
}
