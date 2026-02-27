<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\WithdrawController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/link-storage', function () {
    // Recomendado: só permitir em ambiente local ou com autenticação
    //if (app()->environment('local')) {
    Artisan::call('storage:unlink');
    Artisan::call('storage:link');
    return redirect('/');
    //}

    // abort(403, 'Acesso não autorizado.');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/token/api', [AuthController::class, 'tokenApi'])->name('api.token.api');
Route::post('status', [DepositController::class, 'status']);
Route::post('withdraw/status', [WithdrawController::class, 'status']);

Route::middleware('api.token')->group(function () {
    Route::post('wallet/deposit/payment', [DepositController::class, 'deposit'])->name('api.deposit');
    Route::middleware('throttle:custom-ip-limit')->post('pixout', [WithdrawController::class, 'withdraw'])->name('api.withdraw');

});

Route::post('pedido/order', [PedidoController::class, 'order'])->name('pedido.order');
Route::post('pedido/order/stripe', [PedidoController::class, 'orderStripe'])->name('pedido.order.striep');
Route::post('pedido/upsell', [PedidoController::class, 'upsell'])->name('pedido.upsell');
Route::post('pedido/ad/default', [PedidoController::class, 'adDefault'])->name('pedido.ad.default');
Route::post('pedido/cupom/verificar', [PedidoController::class, 'verificarCupom']);
Route::post('/checkout/visit/{uuid}', [CheckoutController::class, 'registerVisit'])->where('uuid', '.*')->name('checkout.visits');
route::get('/consult/status/link/{id}', [LinkController::class, 'consultStatus'])->where('id', '.*')->name('consult.status.pix');

include_once __DIR__ . '/groups/adquirentes/cashtime.php';
include_once __DIR__ . '/groups/adquirentes/sixxpayments.php';
require __DIR__ . '/groups/adquirentes/efi.php';
require __DIR__ . '/groups/adquirentes/witetec.php';
require __DIR__ . '/groups/adquirentes/pagarme.php';
require __DIR__ . '/groups/adquirentes/stripe.php';
include_once __DIR__ . '/groups/adquirentes/getpay.php';
include_once __DIR__ . '/groups/adquirentes/getpay2.php';
include_once __DIR__ . '/groups/adquirentes/xgate.php';
include_once __DIR__ . '/groups/adquirentes/rapdyn.php';
Route::any('/send-notification', [NotificationController::class, 'sendNotification'])->name('send.notification');
Route::get('/firebase-messaging-sw', [NotificationController::class, 'firebaseMessagingSw']);
