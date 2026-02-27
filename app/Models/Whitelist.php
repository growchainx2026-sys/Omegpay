<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    protected $fillable = ['ip','user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
