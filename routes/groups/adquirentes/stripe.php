<?php 

use App\Http\Controllers\Api\Adquirentes\StripeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'stripe'], function(){
    Route::post('/payment-intent', [StripeController::class, 'paymentIntent']);
});