<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoFileCategoria extends Model
{
    protected $fillable = ['name', 'description', 'produto_id'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function files()
    {
        return $this->hasMany(ProdutoFile::class, 'categoria_id');
    }
}
