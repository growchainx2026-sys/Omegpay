<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['produto_id', 'aluno_id', 'sender_type', 'sender_id', 'body'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
