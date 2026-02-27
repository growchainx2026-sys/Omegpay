<?php

use App\Http\Controllers\Api\Adquirentes\PagarmeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'pagarme'], function () {
    Route::group(['prefix' => 'callback'], function () {
        Route::post('/', [PagarmeController::class, 'webhook']);
        //Route::post('billet', [PagarmeController::class, 'webhookBillet']);
        //Route::post('card', [PagarmeController::class, 'webhookCard']);
    });

    Route::get('parcels/{amount}', [PagarmeController::class,'parcels'])->where('amount', '.*')->name('pagarme.parcels');
});
