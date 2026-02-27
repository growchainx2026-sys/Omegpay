<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateHistory extends Model
{
    protected $fillable = [
        'amount',
        'status',
        'user_id',
        'pedido_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
