<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=> 'checkout'], function(){
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
});