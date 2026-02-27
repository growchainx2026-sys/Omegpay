<?php

use App\Http\Controllers\Api\Adquirentes\EfiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'efi'], function () {
    Route::group(['prefix' => 'callback'], function () {
        Route::post('/', [EfiController::class, 'webhook']);
        Route::post('billet', [EfiController::class, 'webhookBillet']);
        Route::post('card', [EfiController::class, 'webhookCard']);
    });
    Route::post('register-webhook', [EfiController::class, 'registerWebhook']);
});
