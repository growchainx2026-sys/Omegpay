<?php

use App\Http\Controllers\Api\Adquirentes\CashtimeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'cashtime'], function () {
    Route::post('callback/deposit', [CashtimeController::class, 'deposit']);
    Route::post('callback/withdraw', [CashtimeController::class, 'withdraw']);
});
