<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfeera extends Model
{
    protected $table = "transfeeras";
    
    protected $fillable = [
        "token",
        "secret",
        "url",
        "url_cash_in",
        "url_cash_out",
        "tenant_name",
        "tenant_email",
        "tenant_keypix"
    ];
}
