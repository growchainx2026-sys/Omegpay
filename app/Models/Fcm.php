<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fcm extends Model
{
    protected $table = 'fcm';

    protected $fillable = [
        "apiKey",
        "authDomain",
        "projectId",
        "storageBucket",
        "messagingSenderId",
        "appId",
        "measurementId",
        'firebase_config',
        'title',
        'body',
    ];
}
