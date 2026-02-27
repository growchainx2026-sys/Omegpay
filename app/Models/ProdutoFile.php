<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoFile extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'file',
        'file_type',
        'cover',
        'produto_id',
        'categoria_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function categoria()
    {
        return $this->belongsTo(ProdutoFileCategoria::class);
    }
}
