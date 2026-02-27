<?php

use App\Http\Controllers\Api\Adquirentes\Getpay2Controller;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'getpay2'], function () {
    Route::post('callback/deposit', [Getpay2Controller::class, 'deposit']);
    Route::post('callback/withdraw', [Getpay2Controller::class, 'withdraw']);
});
