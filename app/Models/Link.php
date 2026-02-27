<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        "codigo",
        "valor",
        "meios",
        "status",
        'user_id',
        'descricao',
        'idTransaction'
    ];

    protected $casts = [
        'meios' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}