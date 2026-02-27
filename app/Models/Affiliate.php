<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Affiliate extends Model
{
    protected $table = "affiliates";

    protected $fillable = [
        'percentage',
        'status',
        'produto_id',
        'user_id',
        'ref'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

     protected static function booted()
    {
        static::creating(function ($affiliate) {
            if (empty($affiliate->ref)) {
                $affiliate->ref = Str::uuid()->toString();
            }
        });
    }
}
