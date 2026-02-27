<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stripe extends Model
{
    protected $table = 'stripe';

    protected $fillable = [
        'public_key',
        'secret_key',
        'tx_card_fixed',
        'tx_card_percent',
    ];
}
