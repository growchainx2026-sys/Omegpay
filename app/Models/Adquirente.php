<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adquirente extends Model
{
    protected $fillable = [
        "default",
        "uri",
        "client_id",
        "client_secret",
        "taxa_cash_in",
        "taxa_cash_out",
        "active",
    ];
}
