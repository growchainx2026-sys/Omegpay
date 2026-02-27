<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'notifications'], function(){
    Route::post('/save-token', [NotificationController::class, 'saveToken'])->name('save-token');
});