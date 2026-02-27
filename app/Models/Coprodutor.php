<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coprodutor extends Model
{
    protected $table = "coprodutores";

    protected $fillable = [
        'percentage',
        'periodo',
        'produto_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
