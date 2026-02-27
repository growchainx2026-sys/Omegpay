<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sixxpayments extends Model
{
    protected $table = 'sixxpayments';

    protected $fillable = [
        'secret',
        'url_cash_in',
        'url_cash_out',
        'taxa_cash_in',
        'taxa_cash_out',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
