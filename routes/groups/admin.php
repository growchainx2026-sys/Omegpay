<?php

use App\Http\Controllers\Admin\{
    AdquirenteController,
    BannerController, 
    ClienteController,
    DepositoController,
    DevController,
    GameficationController,
    PagesController,
    SaqueController,
    SettingController,
    SystemController
};
use App\Http\Controllers\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth','isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [PagesController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/clientes', [ClienteController::class, 'index'])->name('admin.clientes');
    Route::get('/clientes/editar/{id}', [ClienteController::class, 'indexEdit'])->where('id', '.*')->name('admin.clientes.edit');
    Route::post('/clientes/atualizar', [ClienteController::class, 'update'])->name('admin.clientes.update');
    Route::post('/clientes/excluir', [ClienteController::class, 'excluir'])->name('admin.clientes.excluir');
    Route::post('/clientes/status', [ClienteController::class, 'status'])->name('admin.clientes.status');

    Route::post('depositos/antecipar/{id}', [DepositoController::class, 'antecipar'])->where('id', '.*')->name('admin.depositos.antecipar');

    Route::get('/adquirentes', [AdquirenteController::class, 'index'])->name('admin.adquirentes');
    Route::post('/adquirentes/atualizar', [AdquirenteController::class, 'update'])->name('admin.adquirentes.update');
    Route::post('/adquirentes/status', [AdquirenteController::class, 'status'])->name('admin.adquirentes.status');
    Route::post('/efi/registrar-webhook', [AdquirenteController::class, 'efiRegistrarWebhook'])->name('admin.adquirentes.efi.regitrar');
    
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/settings/atualizar', [SettingController::class, 'update'])->name('admin.settings.update');

    // ✨ NOVAS ROTAS - Alteração de Senha Master Admin
    Route::get('/pass_admin/editar/{id}', [SettingController::class, 'editPassAdmin'])->where('id', '.*')->name('admin.pass_admin.edit');
    Route::put('/pass_admin/atualizar/{id}', [SettingController::class, 'updatePassAdmin'])->where('id', '.*')->name('admin.pass_admin.update');

    Route::get('/depositos', [DepositoController::class, 'index'])->name('admin.depositos');

    Route::get('/saques', [SaqueController::class, 'index'])->name('admin.saques');

    Route::get('/aprovar-saques', [SaqueController::class, 'indexAprovar'])->name('admin.aprovar.saques.index');
    Route::post('/saques/aprovar', [SaqueController::class, 'aprovar'])->name('admin.aprovar.saques.aprovar');
    Route::post('/aprovar-saques/manual', [SaqueController::class, 'aprovarManual'])->name('admin.aprovar.saques.manual');
    Route::post('/saques/rejeitar', [SaqueController::class, 'rejeitar'])->name('admin.aprovar.saques.rejeitar');

    Route::get('/balance', [PagesController::class, 'balance'])->name('admin.balance');
    Route::post('/balance/addentrada', [DepositoController::class, 'addentrada'])->name('admin.balance.addentrada');
    Route::post('/balance/addsaida', [SaqueController::class, 'addsaida'])->name('admin.balance.addsaida');

    Route::get('/extrato', [PagesController::class, 'extrato'])->name('admin.extrato');
        

    Route::group(['prefix'=> 'gamefication'], function(){
        Route::get('/', [GameficationController::class, 'index'])->name('gamefication.index');
        Route::post('/', [GameficationController::class, 'add'])->name('gamefication.add');
        Route::put('/edit/{id}', [GameficationController::class, 'edit'])->where('id','.*')->name('gamefication.edit');
        Route::delete('/delete/{id}', [GameficationController::class, 'excluir'])->where('id','.*')->name('gamefication.delete');
    });

    Route::group(['prefix'=> 'banners'], function(){
        Route::get('/', [BannerController::class, 'index'])->name('banner.index');
        Route::post('/', [BannerController::class, 'create'])->name('banners.add');
        Route::put('/edit/{id}', [BannerController::class, 'edit'])->where('id','.*')->name('banner.edit');
        Route::delete('/delete/{id}', [BannerController::class, 'destroy'])->where('id','.*')->name('banner.delete');
    });

    Route::get('customization',[PagesController::class, 'customization'])->name('customization.index');
    Route::get('taxas',[PagesController::class, 'taxas'])->name('taxas.index');

    Route::get('/produtos', [ProdutoController::class, 'indexAdmin'])->name('admin.produtos');
  
    Route::get('/system/update', [SystemController::class, 'update'])->name('system.update');

    // Área de desenvolvedor (apenas para usuários com permission = dev)
    Route::prefix('dev')->name('admin.dev.')->group(function () {
        Route::get('/', [DevController::class, 'index'])->name('index');
        Route::get('/manual', [DevController::class, 'manual'])->name('manual');
        Route::get('/pix-in', [DevController::class, 'pixIn'])->name('pix-in');
        Route::get('/pix-out', [DevController::class, 'pixOut'])->name('pix-out');
        Route::get('/webhooks', [DevController::class, 'webhooks'])->name('webhooks');
        Route::get('/adquirentes', [DevController::class, 'adquirentes'])->name('adquirentes');
        Route::get('/sandbox', [DevController::class, 'sandbox'])->name('sandbox');
        Route::post('/sandbox/send-webhook', [DevController::class, 'sendWebhookTest'])->name('sandbox.send-webhook');
        Route::post('/sandbox/test-token-secret', [DevController::class, 'testTokenSecret'])->name('sandbox.test-token-secret');
        Route::post('/sandbox/test-api-request', [DevController::class, 'testApiRequest'])->name('sandbox.test-api-request');
    });
  	
});

Route::get('/private/{path}', function ($path) {
    $path = str_replace('..', '', $path); // Segurança contra path traversal
    $path = base64_decode($path, true);
    $fullPath = storage_path("app/private/{$path}");
//dd($fullPath);
    if (!file_exists($fullPath)) {
        abort(404, 'Arquivo não encontrado.');
    }

    return response()->file($fullPath);
})->where('path', '.*');