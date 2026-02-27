<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'alunos'], function () {
    Route::post('/', [AlunoController::class, 'auth'])->name('aluno.auth');
    Route::post('/new-pass', [AlunoController::class, 'newPassword'])->name('aluno.newpass');
    Route::get('/', function () {
        if (auth()->guard('aluno')->check()) {
            return redirect('/alunos/meus-produtos');
        } else {
            return redirect('/login');
        }
    });

    Route::middleware('aluno.auth')->group(function () {

        Route::get('/profile', [AlunoController::class, 'profile'])->name('aluno.profile');
        Route::post('/update-avatar', [AlunoController::class, 'updateAvatar'])->name('aluno.update.avatar');
        Route::post('/alterar-senha', [AlunoController::class, 'alterarSenha'])->name('aluno.senha.alterar');
        Route::post('/alterar-endereco', [AlunoController::class, 'alterarEndereco'])->name('aluno.endereco.alterar');
        Route::get('/meus-produtos', [AlunoController::class, 'meusProdutos'])->name('aluno.produtos.adquiridos');
        //Route::get('/shop', [AlunoController::class, 'shop'])->name('aluno.shop');
        Route::get('/content/{id}', [AlunoController::class, 'produto'])->where('id', '.*')->name('aluno.produto.id');
        Route::get('/produto/{produtoId}/sessao/{sessaoId}', [AlunoController::class, 'getSessaoData'])->name('aluno.sessao.data');
        Route::post('/sessao/concluir', [AlunoController::class, 'concluirSessao'])->name('aluno.sessao.concluir');
        
        // API de progresso
        Route::post('/api/progresso/update', [\App\Http\Controllers\Api\ProgressoController::class, 'updateProgress'])->name('aluno.progresso.update');
        Route::get('/api/progresso/{produtoId}', [\App\Http\Controllers\Api\ProgressoController::class, 'getProgress'])->name('aluno.progresso.get');

        Route::get('/chat/{produtoId}/messages', [ChatController::class, 'alunoMessages'])->name('aluno.chat.messages');
        Route::post('/chat/send', [ChatController::class, 'alunoSend'])->name('aluno.chat.send');

        Route::post('/logout', function () {
            auth()->guard('aluno')->logout();
            return redirect('/login');
        })->name('aluno.logout');
    });
});
