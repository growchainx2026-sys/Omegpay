<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Aluno;
use App\Models\Video;
use App\Models\ProgressoAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendNewPasswordAluno;
use App\Models\ProdutoFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AlunoController extends Controller
{
    public function auth(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('tipo_auth', 'aluno')
                    ->withInput();
            }

            $credentials = $request->only('email', 'password');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('tipo_auth', 'aluno')->withErrors([
                    'email' => 'Credenciais inválidas.',
                ])->withInput();
        }

        $credentials = $request->only('email', 'password');
        $aluno = Aluno::where('email', $request->email)->first();
        if (!$aluno) {
            return redirect()->back()
                ->with('tipo_auth', 'aluno')->withErrors([
                    'email' => 'Credenciais inválidas.',
                ])->withInput();
        }
        if (!Hash::check($request->password, $aluno->password)) {
            return redirect()->back()
                ->with('tipo_auth', 'aluno')->withErrors([
                    'email' => 'Credenciais inválidas.',
                ])->withInput();
        }
        if (auth()->guard('aluno')->attempt($credentials)) {
            return redirect()->to('/alunos/meus-produtos');
        }

        return redirect()->back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->withInput();
    }

    public function index(Request $request)
    {
        return view('pages.aluno.index');
    }

    public function profile(Request $request)
    {
        $aluno = auth('aluno')->user();
        $pedidos = $aluno->pedidos()->where('status', 'pago')->with('produto')->orderBy('created_at', 'desc')->get();

        $cursosComDados = [];
        foreach ($pedidos as $pedido) {
            $progresso = $aluno->progressoProduto($pedido->produto->id);
            $cursosComDados[] = [
                'produto' => $pedido->produto,
                'progresso' => $progresso,
                'pedido' => $pedido,
            ];
        }

        return view('pages.aluno.profile', compact('cursosComDados'));
    }

    public function meusProdutos(Request $request)
    {
        $aluno = auth('aluno')->user();
        $pedidos = $aluno->pedidos()->where('status', 'pago')->with('produto')->get();
        
        // Calcula progresso para cada produto
        $produtosComProgresso = [];
        foreach ($pedidos as $pedido) {
            $progresso = $aluno->progressoProduto($pedido->produto->id);
            $produtosComProgresso[] = [
                'produto' => $pedido->produto,
                'progresso' => $progresso,
                'pedido' => $pedido,
            ];
        }
        
        return view('pages.aluno.meus-produtos-novo', compact('produtosComProgresso', 'aluno'));
    }

    public function produto(Request $request, $id)
    {
        $aluno = auth('aluno')->user();
        $produto = Produto::where('id', $id)->first();
        
        if (!$produto) {
            return abort(404);
        }

        // Verifica se o aluno tem acesso ao produto
        $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
        if (!$pedido) {
            return redirect('/alunos/meus-produtos')->with('error', 'Você não tem acesso a este produto.');
        }

        // Carrega módulos novos (estrutura nova)
        $produto->load(['modulosAtivos.sessoesAtivas.videosAtivos']);
        
        // Carrega categorias antigas (estrutura antiga) para compatibilidade
        $produto->load(['categories.files']);
        
        // Calcula progresso geral
        $progressoGeral = $aluno->progressoProduto($produto->id);
        
        // Se há parâmetros de sessão, mostra a visualização do vídeo
        if ($request->has('sessao') && $request->has('modulo')) {
            $sessao = \App\Models\Sessao::with(['videosAtivos' => function($query) {
                $query->orderBy('ordem');
            }, 'modulo'])->findOrFail($request->sessao);
            
            // Verifica se a sessão pertence ao módulo e produto corretos
            if ($sessao->modulo_id != $request->modulo || $sessao->modulo->produto_id != $produto->id) {
                return redirect()->route('aluno.produto.id', ['id' => $id])->with('error', 'Sessão não encontrada.');
            }
            
            // Verifica se módulo está liberado
            if (!$aluno->moduloLiberado($sessao->modulo->id, $produto->id)) {
                return redirect()->route('aluno.produto.id', ['id' => $id])->with('error', 'Complete o módulo anterior para acessar este conteúdo.');
            }
            
            // Verifica se sessão está liberada
            if (!$aluno->sessaoLiberada($sessao->id, $produto->id)) {
                return redirect()->route('aluno.produto.id', ['id' => $id])->with('error', 'Complete as sessões anteriores para acessar este conteúdo.');
            }
            
            // Carrega todas as sessões do módulo para a trilha
            $modulo = $sessao->modulo;
            $todasSessoes = $modulo->sessoesAtivas()->orderBy('ordem')->get();
            
            // Primeiro vídeo da sessão como padrão
            $videoAtual = $sessao->videosAtivos->first();
            
            return view('pages.aluno.produto-video', compact('produto', 'progressoGeral', 'aluno', 'sessao', 'modulo', 'todasSessoes', 'videoAtual'));
        }
        
        return view('pages.aluno.produto-novo', compact('produto', 'progressoGeral', 'aluno'));    
    }

    public function shop(Request $request)
    {
        $pedidos = auth('aluno')->user()->pedidos()->where('status', 'pago')->get();
        $files = [];
        foreach ($pedidos as $pedido) {
            $file = ProdutoFile::where('produto_id', $pedido->produto->id)->first();
            if($file->produto()->where('area_member_shop_show', 1)){
                $files[] = $file;
            }
        }
        $file = $files[0];
        $produtos = Produto::where('status', 1)->where('area_member_shop_show', 1)->get();
        return view('pages.aluno.shop', compact('produtos', 'file'));
    }

    public function newPassword(Request $request)
    {
        /* try { */
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('tipo_auth', 'new-password')
                ->withInput();
        }

        $aluno = Aluno::where('email', $request->email)->first();

        if (!$aluno) {
            return redirect()->back()
                ->withErrors(['email' => "Email não encontrado na base de dados."])
                ->with('tipo_auth', 'new-password')
                ->withInput();
        }

        $senhaProvisoria = uniqid();
        $hash = Hash::make($senhaProvisoria);
        $aluno->update(['password' => $hash]);
        $aluno->save();

        $assunto = "Área de membros - Nova senha de acesso.";
        Mail::to($aluno->email)->queue(new SendNewPasswordAluno($aluno, $senhaProvisoria, $assunto));

        return redirect()->back()
            ->with('tipo_auth', 'aluno')
            ->with('success', "Senha enviada para {$aluno->email}.");

        /* } catch (\Throwable $th) {
            return redirect()->back()
                ->with('tipo_auth', 'new-password')->withErrors([
                    'email' => 'Email inválido.',
                ])->withInput();
        } */
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'image|max:2048',
        ]);

        $user = auth('aluno')->user();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = 'storage/' . $avatarPath;
            $user->save();
        }

        return back()->with('success', 'Avatar atualizado com sucesso.');
    }

    public function alterarSenha(Request $request)
    {
        try {
            $user = auth('aluno')->user();

            $validator = Validator::make($request->all(), [
                'senha_atual' => ['required'],
                'nova_senha' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'senha_atual.required' => 'A senha atual é obrigatória.',
                'nova_senha.required' => 'A nova senha é obrigatória.',
                'nova_senha.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
                'nova_senha.confirmed' => 'A confirmação da nova senha não confere.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // 🔹 Verifica a senha atual
            if (!Hash::check($request->senha_atual, $user->password)) {
                return back()->withErrors([
                    'senha_atual' => 'A senha atual informada não confere.',
                ]);
            }

            $user->update([
                'password' => Hash::make($request->nova_senha),
            ]);

            $assunto = "Você alterou sua senha.";
            Mail::to($user->email)->queue(new SendNewPasswordAluno($user, $request->nova_senha, $assunto));

            return back()->with('success', 'Senha alterada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao alterar senha do usuário ID ' . auth('aluno')->id() . ': ' . $e->getMessage());

            return back()->withErrors(['erro' => 'Ocorreu um erro inesperado. Tente novamente mais tarde.'])->withInput();
        }
    }

    public function alterarEndereco(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        auth('aluno')->user()->update($data);

        return redirect()->back()->with('success', "Endereço alterado com sucesso.");
    }

    public function getSessaoData(Request $request, $produtoId, $sessaoId)
    {
        try {
            $aluno = auth('aluno')->user();
            if (!$aluno) {
                return response()->json(['error' => 'Não autenticado.'], 401);
            }
            
            $produto = Produto::findOrFail($produtoId);
            
            // Verifica se aluno tem acesso ao produto
            $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
            if (!$pedido) {
                return response()->json(['error' => 'Você não tem acesso a este produto.'], 403);
            }
            
            $sessao = \App\Models\Sessao::with(['modulo.produto'])->findOrFail($sessaoId);
            
            // Verifica se sessão pertence ao produto
            if (!$sessao->modulo || $sessao->modulo->produto_id != $produto->id) {
                return response()->json(['error' => 'Sessão não pertence a este produto.'], 403);
            }
            
            // Verifica se está liberada
            if (!$aluno->sessaoLiberada($sessao->id, $produto->id)) {
                return response()->json(['error' => 'Sessão bloqueada. Complete as sessões anteriores.'], 403);
            }
            
            $modulo = $sessao->modulo;
            $todasSessoes = $modulo->sessoesAtivas()->orderBy('ordem')->get();
            
            // Prepara vídeos com status de conclusão
            $videos = Video::where('sessao_id', $sessao->id)
                ->where('status', 1)
                ->orderBy('ordem')
                ->get()
                ->map(function($video) use ($aluno) {
                    $progresso = $aluno->progressoVideo($video->id);
                    return [
                        'id' => $video->id,
                        'titulo' => $video->titulo,
                        'descricao' => $video->descricao,
                        'url_youtube' => $video->url_youtube,
                        'duracao' => $video->duracao,
                        'ordem' => $video->ordem,
                        'concluido' => $progresso && $progresso->concluido
                    ];
                });
            
            return response()->json([
                'success' => true,
                'sessao' => [
                    'id' => $sessao->id,
                    'nome' => $sessao->nome,
                    'descricao' => $sessao->descricao
                ],
                'modulo' => [
                    'id' => $modulo->id,
                    'nome' => $modulo->nome
                ],
                'videos' => $videos->values(),
                'todasSessoes' => $todasSessoes->map(function($s) use ($aluno, $produto) {
                    // Verifica se sessão está concluída usando método do modelo
                    $concluida = $aluno->sessaoConcluida($s->id, $produto->id);
                    
                    // Verifica se está liberada
                    $liberada = $aluno->sessaoLiberada($s->id, $produto->id);
                    
                    return [
                        'id' => $s->id,
                        'nome' => $s->nome,
                        'videos_count' => Video::where('sessao_id', $s->id)->where('status', 1)->count(),
                        'concluida' => $concluida,
                        'liberada' => $liberada
                    ];
                })->values()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados da sessão: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erro ao carregar dados da sessão: ' . $e->getMessage()], 500);
        }
    }

    public function concluirSessao(Request $request)
    {
        try {
            $aluno = auth('aluno')->user();
            if (!$aluno) {
                return response()->json(['error' => 'Não autenticado.'], 401);
            }
            
            $sessaoId = $request->input('sessao_id');
            $produtoId = $request->input('produto_id');
            
            $sessao = \App\Models\Sessao::with(['videosAtivos'])->findOrFail($sessaoId);
            $produto = Produto::findOrFail($produtoId);
            
            // Verifica se aluno tem acesso ao produto
            $pedido = $aluno->pedidos()->where('produto_id', $produto->id)->where('status', 'pago')->first();
            if (!$pedido) {
                return response()->json(['error' => 'Você não tem acesso a este produto.'], 403);
            }
            
            // Verifica se sessão pertence ao produto
            if ($sessao->modulo->produto_id != $produto->id) {
                return response()->json(['error' => 'Sessão não pertence a este produto.'], 403);
            }
            
            // Verifica se todos os vídeos foram concluídos
            $todosVideosConcluidos = true;
            foreach ($sessao->videosAtivos as $video) {
                $progresso = $aluno->progressoVideo($video->id);
                if (!$progresso || !$progresso->concluido) {
                    $todosVideosConcluidos = false;
                    break;
                }
            }
            
            if (!$todosVideosConcluidos) {
                return response()->json(['error' => 'Complete todos os vídeos antes de concluir a sessão.'], 400);
            }
            
            // Marca todos os vídeos como concluídos (garantia)
            foreach ($sessao->videosAtivos as $video) {
                ProgressoAluno::updateOrCreate(
                    [
                        'aluno_id' => $aluno->id,
                        'video_id' => $video->id,
                    ],
                    [
                        'produto_id' => $produto->id,
                        'tempo_assistido' => 100,
                        'tempo_total' => 100,
                        'ultima_posicao' => 0,
                        'concluido' => true,
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Sessão concluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao concluir sessão: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao concluir sessão: ' . $e->getMessage()], 500);
        }
    }
}
