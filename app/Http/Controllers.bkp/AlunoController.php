<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendNewPasswordAluno;
use App\Models\ProdutoFile;
use Illuminate\Support\Facades\Mail;

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
        return view('pages.aluno.profile');
    }

    public function meusProdutos(Request $request)
    {
        $pedidos = auth('aluno')->user()->pedidos()->where('status', 'pago')->get();
        
        $files = [];
        foreach ($pedidos as $pedido) {
            $file = ProdutoFile::where('produto_id', $pedido->produto->id)->first();
           // dd($file);
            $files[] = $file;
            # code...
        }

        if(count($files) == 0){
            $files = null;
        }
       // dd($files);
        return view('pages.aluno.meus-produtos', compact('files'));
    }

    public function produto(Request $request, $id)
    {
        $produto = Produto::where('id', $id)->first();
        return view('pages.aluno.produto', compact('produto'));    
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
}
