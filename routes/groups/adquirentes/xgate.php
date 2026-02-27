<?php

use App\Http\Controllers\Api\Adquirentes\XGateController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'xgate'], function () {
    Route::post('callback/webhook', [XGateController::class, 'webhook']);
});
