<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicacoes extends Model
{
    protected $fillable = ['amount', 'percent', 'transaction_id'];
}
