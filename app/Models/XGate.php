<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XGate extends Model
{
    protected $table = 'xgate';

    protected $fillable = [
        'email',
        'password',
        'taxa_cash_in',
        'taxa_cash_out',
    ];
}
