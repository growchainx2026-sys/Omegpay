<?php

use App\Http\Controllers\{CheckoutBuilderController, ChatController, CheckoutController, ComprovanteController, CuponController, DocumentationController, OrderBumpController, PagesController, PedidoController, ProdutoController, VoucherController};
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\CoproducaoController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MinhataxaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TransactionsController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\WebhookTestController;
use App\Models\Efi;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;
// ❌ REMOVIDO: use App\Http\Controllers\VoucherController; (LINHA DUPLICADA)


Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [PagesController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::get('/login2', function () {
    return redirect()->to('/login');
})->name('login2');

Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('resetPassword');

Route::post('/rec-pass', [AuthController::class, 'recPassword'])->name('auth.recpass');

Route::group(['prefix' => 'auth'], function () {
    Route::get('jwt/register', [AuthController::class, 'indexRegister'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
});

Route::middleware(['auth', 'web', 'calcula.saldo'])->group(function () {
    Route::post('/update-utmfy', [AuthController::class, 'updateUtmfy'])->name('user.update.utmfy');
    Route::post('/update-spedy', [AuthController::class, 'updateSpedy'])->name('user.update.spedy');
    Route::get('/remove-utmfy', [AuthController::class, 'removeUtmfy'])->name('user.remove.utmfy');

    Route::post('/verify-docs', [AuthController::class, 'verifyDocs'])->name('auth.verifydocs');
    Route::post('/update-avatar', [AuthController::class, 'updateAvatar'])->name('user.update.avatar');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('/alterar-senha', [AuthController::class, 'alterarSenha'])->name('senha.alterar');
    Route::get('/enviar-docs', [PagesController::class, 'enviarDocs'])->name('auth.enviar-docs');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/extratos/saques', [TransactionsController::class, 'extratoSaques'])->name('extrato.saques');
    Route::get('/extratos/depositos', [TransactionsController::class, 'extratoDepositos'])->name('extrato.depositos');
    Route::post('/deposito', [TransactionsController::class, 'depositoWeb'])->name('deposito.web');
    Route::get('/depositoPix', [PagesController::class, 'depositoPix'])->name('depositoPix');
    Route::post('/saquePix', [TransactionsController::class, 'saquePix'])->name('saquePix');
    Route::get('/financeiro', [PagesController::class, 'transferencia'])->name('transferencia');
    Route::post('/transferencia', [TransactionsController::class, 'transferirSaldo'])->name('transferencia.saldo');
    Route::get('/extratos/saques', [TransactionsController::class, 'extratoSaques'])->name('extrato.saques');
    Route::get('/saque-copia-cola', [PagesController::class, 'saqueCopiaCola'])->name('saqueCopiaCola');
    //Route::get('/transfer-balance', [PagesController::class, 'transferBalance'])->name('transferBalance');
    //Route::get('/aprove-withdraw', [PagesController::class, 'aproveWithdraw'])->name('aproveWithdraw');
    Route::get('/account-view', [PagesController::class, 'accountView'])->name('accountView');
    Route::get('/wallet', [PagesController::class, 'wallet'])->name('wallet');

    Route::get('/whitelist', [PagesController::class, 'whitelist'])->name('whitelist');
    Route::post('/whitelist', [AuthController::class, 'addWhitelist'])->name('auth.whitelist.add');
    Route::put('/whitelist/{id}', [AuthController::class, 'delWhitelist'])->where('id', '.*')->name('auth.whitelist.remove');

    Route::get('integracoes', [PagesController::class, 'integracoes'])->name('integracoes');
    Route::get('infracoes', [PagesController::class, 'infracoes'])->name('infracoes');


    Route::get('afiliate', [PagesController::class, 'afiliate'])->name('afiliate');

    Route::group(['prefix' => 'produtos'], function () {
        Route::delete('/delete/bump/{id}', [OrderBumpController::class, 'delete'])->where('id', '.*')->name('produtos.bump.delete');
        Route::post('bump', [OrderBumpController::class, 'store'])->name('produtos.bump.store');

        Route::get('/', [ProdutoController::class, 'index'])->name('produtos.index');
        Route::post('/', [ProdutoController::class, 'store'])->name('produtos.store');
        Route::put('/{id}', [ProdutoController::class, 'edit'])->where('id', '.*')->name('produtos.edit');
        Route::delete('/{id}', [ProdutoController::class, 'delete'])->where('id', '.*')->name('produtos.delete');

        Route::group(['prefix' => 'files'], function () {
            Route::post('/add', [ProdutoController::class, 'addFile'])->name('produto.files.add');
            Route::post('/edit', [ProdutoController::class, 'editFile'])->name('produto.files.edit');
            Route::post('/del', [ProdutoController::class, 'delFile'])->name('produto.file.delete');

            Route::group(['prefix' => 'category'], function () {
                Route::post('/del', [ProdutoController::class, 'delCategory'])->name('produto.category.delete');
                Route::post('/add', [ProdutoController::class, 'addCategory'])->name('produto.category.add');
                Route::post('/edit', [ProdutoController::class, 'editCategory'])->name('produto.category.edit');
            });
        });

        Route::group(['prefix' => 'cupons'], function () {
            Route::post('/', [CuponController::class, 'store'])->name('produtos.cupons.store');
            Route::post('/edit/{id}', [CuponController::class, 'edit'])->where('id', '.*')->name('produtos.cupons.edit');
            Route::post('/del/{id}', [CuponController::class, 'del'])->where('id', '.*')->name('produtos.cupons.del');
        });


        Route::get('/{uuid}/edit', [ProdutoController::class, 'indexEdit'])->where('uuid', '.*')->name('produtos.index.edit');
        Route::get('/{uuid}/area-membros', [ProdutoController::class, 'areaMembros'])->where('uuid', '.*')->name('produtos.area-membros');
        Route::get('/{uuid}/preview-area-membros', [ProdutoController::class, 'previewAreaMembros'])->where('uuid', '.*')->name('produtos.preview-area-membros');

        Route::get('/{uuid}/chat/last-messages', [ChatController::class, 'produtorLastMessages'])->where('uuid', '.*')->name('produtos.chat.last-messages');
        Route::get('/{uuid}/chat/{alunoId}/messages', [ChatController::class, 'produtorMessages'])->where('uuid', '.*')->name('produtos.chat.messages');
        Route::post('/chat/send', [ChatController::class, 'produtorSend'])->name('produtos.chat.send');

        // Rota para detalhes do aluno
        Route::get('/{produtoId}/aluno/{alunoId}/detalhes', [\App\Http\Controllers\ProdutoAlunoController::class, 'detalhesAluno'])->name('produtos.aluno.detalhes');
        
        // Rotas para gerenciar módulos, sessões e vídeos (seguindo padrão da área antiga)
        Route::group(['prefix' => 'modulos'], function () {
            Route::post('/', [\App\Http\Controllers\ModuloController::class, 'store'])->name('produtos.modulos.store');
            Route::post('/edit', [\App\Http\Controllers\ModuloController::class, 'update'])->name('produtos.modulos.update');
            Route::post('/del', [\App\Http\Controllers\ModuloController::class, 'destroy'])->name('produtos.modulos.destroy');
            Route::post('/reorder', [\App\Http\Controllers\ModuloController::class, 'reorder'])->name('produtos.modulos.reorder');
        });
        
        Route::group(['prefix' => 'sessoes'], function () {
            Route::post('/', [\App\Http\Controllers\SessaoController::class, 'store'])->name('produtos.sessoes.store');
            Route::post('/edit', [\App\Http\Controllers\SessaoController::class, 'update'])->name('produtos.sessoes.update');
            Route::post('/del', [\App\Http\Controllers\SessaoController::class, 'destroy'])->name('produtos.sessoes.destroy');
            Route::post('/reorder', [\App\Http\Controllers\SessaoController::class, 'reorder'])->name('produtos.sessoes.reorder');
            Route::get('/{sessaoId}/videos', [\App\Http\Controllers\SessaoController::class, 'getVideos'])->name('produtos.sessoes.videos');
        });
        
        Route::group(['prefix' => 'videos'], function () {
            Route::post('/', [\App\Http\Controllers\VideoController::class, 'store'])->name('produtos.videos.store');
            Route::post('/edit', [\App\Http\Controllers\VideoController::class, 'update'])->name('produtos.videos.update');
            Route::post('/del', [\App\Http\Controllers\VideoController::class, 'destroy'])->name('produtos.videos.destroy');
            Route::post('/toggle-status', [\App\Http\Controllers\VideoController::class, 'toggleStatus'])->name('produtos.videos.toggle-status');
            Route::post('/reorder', [\App\Http\Controllers\VideoController::class, 'reorder'])->name('produtos.videos.reorder');
        });
    });



    Route::group(['prefix' => 'pedidos'], function () {
        Route::get('/', [PedidoController::class, 'index'])->name('pedidos.index');
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::post('/', [CheckoutController::class, 'add'])->name('checkout.add');
        Route::put('/editar/{id}', [CheckoutController::class, 'editar'])->where('id', '.*')->name('checkout.edit');
        Route::put('/duplicate/{id}', [CheckoutController::class, 'duplicate'])->where('id', '.*')->name('checkout.duplicate');
        Route::delete('/excluir/{id}', [CheckoutController::class, 'deletar'])->where('id', '.*')->name('checkout.delete');
    });

    Route::group(['prefix' => 'checkout-builder'], function () {
        Route::get('/{uuid}', [CheckoutBuilderController::class, 'index'])->where('uuid', '.*')->name('checkout-build.index');
        Route::post('/{uuid}', [CheckoutBuilderController::class, 'update'])->where('uuid', '.*')->name('checkout-build.update');
    });

    Route::get('/builder/components', function () {
        return view('components.builder-components');
    });

    Route::group(['prefix' => 'coproducoes'], function () {
        Route::get('/', [CoproducaoController::class, 'index'])->name('coproducao.index');
        Route::post('/accept', [CoproducaoController::class, 'accept'])->name('coproducao.accept');
        Route::post('/recuse', [CoproducaoController::class, 'recuse'])->name('coproducao.recuse');
        Route::delete('/revogue/{id}', [CoproducaoController::class, 'revogue'])->where('id', '.*')->name('coproducao.revogue');
        Route::post('/add/{uuid}', [CoproducaoController::class, 'add'])->where('uuid', '.*')->name('coproducao.add');
    });

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', [CoproducaoController::class, 'indexNotify'])->name('coproducao.notify.index');
        Route::post('/read', [CoproducaoController::class, 'readNotify'])->name('coproducao.notify.read');
        Route::post('/delete', [CoproducaoController::class, 'deleteNotify'])->name('notifications.delete');
    });


    Route::group(['prefix' => 'affiliates'], function () {
        Route::get('/vitrine', [AffiliateController::class, 'vitrine'])->name('affiliate.vitrine');
        Route::get('/my-affiliates', [AffiliateController::class, 'myAffiliates'])->name('affiliate.myaffiliates');
        Route::get('/view/{id}', [AffiliateController::class, 'view'])->where('id', '.*')->name('affiliate.view');
        Route::post('/affiliate-me', [AffiliateController::class, 'affiliateMe'])->name('affiliates.affiliate.me');
        Route::post('/desaffiliate-me', [AffiliateController::class, 'desaffiliateMe'])->name('affiliates.desaffiliate.me');
    });

    Route::group(['prefix' => 'webhooks'], function () {
        Route::get('/', [WebhookController::class, 'index'])->name('webhooks');
        Route::post('/', [WebhookController::class, 'store'])->name('webhooks.store');
        Route::put('/update/{id}', [WebhookController::class, 'update'])->where('id', '.*')->name('webhooks.update');
        Route::delete('/destroy/{id}', [WebhookController::class, 'destroy'])->where('id', '.*')->name('webhooks.destroy');
    });

    Route::get('/webhook-tests', [WebhookTestController::class, 'index'])->name('webhook-tests.index');
    Route::post('/webhook-tests/send', [WebhookTestController::class, 'send'])->name('webhook-tests.send');

    Route::group(['prefix' => 'minhas-taxas'], function () {
        Route::get('/', [MinhataxaController::class, 'index'])->name('minhas-taxas.index');
        Route::post('/selecionar', [MinhataxaController::class, 'update'])->name('minhas-taxas.update');
    });

    Route::group(['prefix' => 'links-pagamento'], function () {
        Route::get('/list', [LinkController::class, 'index'])->name('user.links.index');
        Route::post('/create', [LinkController::class, 'store'])->name('user.links.store');
        Route::post('/edit/{id}', [LinkController::class, 'edit'])->where('id', '.*')->name('user.links.edit');
        Route::post('/delete/{id}', [LinkController::class, 'del'])->where('id', '.*')->name('user.links.delete');
    });


    Route::group(['prefix' => 'vouchers'], function () {
        Route::get('/list', [VoucherController::class, 'index'])->name('user.vouchers.index');
        Route::post('/create', [VoucherController::class, 'store'])->name('user.vouchers.store');
        Route::post('/edit/{id}', [VoucherController::class, 'edit'])->where('id', '.*')->name('user.vouchers.edit');
        Route::post('/delete/{id}', [VoucherController::class, 'del'])->where('id', '.*')->name('user.vouchers.delete');
    });

    Route::group(['prefix' => 'customization'], function () {
        Route::get('/', [PagesController::class, 'customIndex'])->name('user.customization.index');
        Route::post('/', [PagesController::class, 'customUpdate'])->name('user.customization.update');
    });

    include_once __DIR__ . '/groups/notification.php';

});

Route::group(['prefix' => 'docs/api-pix'], function () {
    Route::get('/', [DocumentationController::class, 'index'])->name('docs.index');
    Route::get('/send', [DocumentationController::class, 'send'])->name('docs.send');
    Route::get('/receive', [DocumentationController::class, 'receive'])->name('docs.receive');
    Route::get('/webhooks', [DocumentationController::class, 'webhooks'])->name('docs.webhooks');
});

Route::get('/produto-image-default/{w?}/{h?}', function (?string $w = null, ?string $h = null) {
    $setting = Setting::first();
    $svg = file_get_contents(public_path('storage/produtos/box_default.svg'));

    // Substitui todos os currentColor pela cor definida
    $svg = preg_replace('/currentColor/', $setting->software_color, $svg);

    // Substitui width se informado
    if (!empty($w)) {
        $svg = preg_replace('/width=[\'"][0-9]+[\'"]/', "width='{$w}'", $svg);
    }

    // Substitui height se informado
    if (!empty($h)) {
        $svg = preg_replace('/height=[\'"][0-9]+[\'"]/', "height='{$h}'", $svg);
    }

    return response($svg, 200)
        ->header('Content-Type', 'image/svg+xml');
});

Route::get('produto/{uuid}', [ProdutoController::class, 'indexClient'])->where('uuid', '.*')->name('produtos.index.client');
Route::get('produto-variavel/{uuid}', [ProdutoController::class, 'indexClientSimple'])->where('uuid', '.*')->name('produtos.index.client.simple');
Route::get('comprovante/pix/{uuid}', [ComprovanteController::class, 'comprovantePix'])->where('uuid', '.*')->name('comprovante.pix');
Route::get('payment/link/{id}', [LinkController::class, 'indexPayment'])->name('links.payment');
Route::post('payment/order', [LinkController::class, 'order'])->name('links.order');

// Rotas de Links
Route::get('/links', [LinkController::class, 'index'])->name('links.index');
Route::post('/links/store', [LinkController::class, 'store'])->name('links.store');

// ✨ NOVA ROTA DE VOUCHERS
Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');

Route::post('/vouchers/{id}/validar', [VoucherController::class, 'validar'])
    ->name('vouchers.validar');

// ===================================
// ⚠️ IMPORTANTE: ROTA DA API DEVE ESTAR FORA DO MIDDLEWARE WEB
// ===================================
// Esta rota PRECISA estar FORA do grupo com middleware ['auth', 'web']
// para evitar verificação de CSRF token
// ===================================
Route::post('/api/voucher/create', [LinkController::class, 'createVoucher'])
    ->middleware('api.voucher.auth')
    ->name('api.voucher.create');


include_once __DIR__ . '/groups/admin.php';
include_once __DIR__ . '/groups/checkout.php';
include_once __DIR__ . '/groups/aluno.php';

// Rotas de desenvolvimento - REMOVER EM PRODUÇÃO
if (app()->environment(['local', 'development']) || config('app.debug')) {
    include_once __DIR__ . '/dev.php';
}
include_once __DIR__ . '/groups/upsell.php';