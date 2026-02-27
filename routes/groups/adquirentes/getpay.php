<?php

use App\Http\Controllers\Api\Adquirentes\GetpayController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'getpay'], function () {
    Route::post('callback/deposit', [GetpayController::class, 'deposit']);
    Route::post('callback/withdraw', [GetpayController::class, 'withdraw']);
});
