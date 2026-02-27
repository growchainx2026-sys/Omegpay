<?php

use App\Http\Controllers\Api\Adquirentes\RapdynController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'rapdyn'], function () {
    Route::post('callback/deposit', [RapdynController::class, 'deposit']);
    Route::post('callback/withdraw', [RapdynController::class, 'withdraw']);
});
