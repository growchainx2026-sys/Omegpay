<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBump extends Model
{
    protected $fillable = [
        'valor_de',
        'valor_por',
        'call_to_action',
        'product_name',
        'product_description',
        'produto_id',
        'show_image'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
