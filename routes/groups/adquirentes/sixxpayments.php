<?php

use App\Http\Controllers\Api\Adquirentes\SixxpaymentsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'sixxpayments'], function () {
    Route::post('callback/deposit', [SixxpaymentsController::class, 'deposit']);
    Route::post('callback/withdraw', [SixxpaymentsController::class, 'withdraw']);
});
