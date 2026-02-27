<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\SendMessage;
use App\Mail\SendNewPasswordProdutor;
use App\Models\Setting;
use App\Models\User;
use App\Models\Whitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
                return redirect()->route('auth.enviar-docs');
            }
            return redirect()->route('dashboard');
        }
        return view('pages.login');
    }
    public function enviarDocs(Request $request)
    {

        return view('pages.enviar-docs');
    }
    public function indexRegister(Request $request)
    {
        if (auth()->check()) {
            if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
                return redirect()->route('auth.enviar-docs');
            }
            return redirect()->route('dashboard');
        }
        return view('pages.register');
    }
    public function resetPassword(Request $request)
    {
        return view('pages.reset-password');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('tipo_auth', 'produtor')
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return redirect()->back()
                ->with('tipo_auth', 'produtor')->withErrors('Credenciais inválidas.');
        }


        $cookie = cookie('token', $token, 60);

        return redirect('/dashboard')->withCookie($cookie);
    }


    public function logout(Request $request)
    {
        try {
            $token = $request->cookie('token');

            if ($token) {
                JWTAuth::setToken($token)->invalidate();
            }


            $cookie = cookie()->forget('token');

            return redirect('/login')->withCookie($cookie);
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao fazer logout.');
        }
    }

    public function register(Request $request)
    {
        $data = $request->except(['_method', '_token']);
        //dd($data);
        $data['cpf'] = isset($data['cpf']) ? preg_replace('/\D/', '', $data['cpf']) : null;
        $data['cnpj'] = isset($data['cnpj']) ? preg_replace('/\D/', '', $data['cnpj']) : null;
        $data['telefone'] = preg_replace('/\D/', '', $data['phone']);
        $data['username'] = isset($data['cpf']) ? $data['cpf'] : $data['cnpj'];
        $data['cpf_cnpj'] = isset($data['cpf']) ? $data['cpf'] : $data['cnpj'];
        $data['media_faturamento'] = isset($data['media_faturamento']) ? (int) $data['media_faturamento'] : 0;
        if ($data['tipo'] == 'pessoa_juridica') {
            $data['name'] = $data['razao_social'];
        }

        unset($data['phone']);
        unset($data['cpf']);
        unset($data['cnpj']);
        $validator = Validator::make($data, [
            'tipo' => 'required|in:pessoa_fisica,pessoa_juridica',
            'media_faturamento' => 'nullable|numeric|min:0',
            'telefone' => 'required|string|unique:users,telefone',
            'cpf_cnpj' => 'required|string|unique:users,cpf_cnpj',
            'email' => 'required|string|email|unique:users,email',
            'name' => 'required|string|min:2|max:100|regex:/^[\pL\s\'\-]+$/u',
            'password' => 'required|string|min:6|max:24|same:password_confirm',
            'password_confirm' => 'required|string',

            // Regra condicional
            'razao_social' => 'required_if:tipo,pessoa_juridica|string|min:2|max:100',
        ], [
            'required' => 'Este campo é obrigatório',
            'string' => 'Este campo deve ser uma string',
            'unique' => 'Este :attribute já está em uso',
            'same' => 'As senhas não coincidem',
            'email' => 'O campo :attribute deve ser um endereço de e-mail válido',
            'in' => 'Valor inválido para o campo :attribute',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres',
            'required_if' => 'O campo :attribute é obrigatório quando o tipo de conta é pessoa jurídica.',
            'password' => "O campo :attribute deve conter no minimo 8 caractéres, misto de Letras maíusculas, minúsculas e ao menos um caracter especial: @#_.=+-!"
        ]);
        //dd($validator->errors());
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $payload = collect($data)
            ->merge([
                'clientId' => 'ci_' . Str::slug(Str::before($request->name, ' ')) . '_' . Str::uuid()->toString(),
                'secret' => 'cs_' . Str::slug(Str::before($request->name, ' ')) . '_' . Str::uuid()->toString(),
                'codigo_referencia' => uniqid(),
                'password' => Hash::make($request->password),
                'ip_user' => $request->ip(),
                'client_id' => Str::uuid()->toString(),
                'media_faturamento' => $request->input('media_faturamento', 0),
            ])
            ->merge(optional(Setting::first())->only([
                'taxa_cash_in',
                'taxa_cash_out',
                'taxa_cash_in_fixa',
                'taxa_cash_out_fixa',
                'taxa_reserva',
                'deposito_minimo',
                'deposito_maximo',
                'saque_minimo',
                'saque_maximo',
                'saques_dia',
            ]))
            ->when($request->has('ref'), function ($c) use ($request) {
                return $c->put('client_indication', $request->ref);
            })
            ->toArray();

        $user = User::create($payload);

        $setting = Setting::first();

        if (
            !empty($setting->mail_host) &&
            !empty($setting->mail_port) &&
            !empty($setting->mail_username) &&
            !empty($setting->mail_password)
        ) {
            $assunto = "Cadastro realizado com sucesso.";
            $mail = Mail::to($data['email'])->queue(new SendMessage(
                $user->email,
                $assunto,
                "Seja bem vindo a " . $setting->software_name . ". Complete seu cadastro para começar a utilizar nossos serviços.",
                $user->name,
            ));
        }

        try {
            if (!$token = JWTAuth::fromUser($user)) {
                return redirect()->back()->with('error', 'Falha na autenticação JWT.');
            }
        } catch (JWTException $e) {
            return redirect()->back()->with('error', 'Não foi possível criar o token.');
        }


        $cookie = cookie('token', $token, 60);

        return redirect('/dashboard')->withCookie($cookie);
    }

    public function verifyDocs(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'foto_rg_frente' => 'image|max:10240',
            'foto_rg_verso' => 'image|max:10240',
            'selfie_rg' => 'image|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $items = $request->only([
            "cpf_cnpj",
            "nome_mae",
            "nome_pai",
            "cep",
            "rua",
            "numero_residencia",
            "complemento",
            "bairro",
            "cidade",
            "estado",
        ]);

        $imageFields = ['foto_rg_frente', 'foto_rg_verso', 'selfie_rg'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = auth()->user()->id . $field . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('documents/clientes', $imageName, 'local');

                $items[$field] = '/' . $imagePath;
            } else {
                // Se for null, remover do array $data
                unset($items[$field]);
            }
        }

        $items['status'] = 'analise';


        $user = auth()->user();
        $user->update($items);
        auth()->user()->status = 'analise';

        $setting = Setting::first();
        if (
            !empty($setting->mail_host) &&
            !empty($setting->mail_port) &&
            !empty($setting->mail_username) &&
            !empty($setting->mail_password)
        ) {
            $assunto = "Recebemos sua atualização cadastral.";
            $mail = Mail::to($user->email)->queue(new SendMessage(
                $user->email,
                $assunto,
                "Recebemos seus documentos. A análise será realizada e em breve você receberá um novo email com a efetivação da sua conta.",
                $user->name,
            ));
        }

        return back()->with('success', 'Dados enviados com sucesso! Em breve sua conta estará ativa.');
    }

    public function addWhitelist(Request $request)
    {
        $data = $request->only('ip');
        $data['user_id'] = auth()->user()->id;
        Whitelist::create($data);

        return back()->with('success', 'IP adcionado com sucesso!');
    }

    public function delWhitelist(Request $request)
    {
        $id = $request->id;
        Whitelist::where('id', $id)->delete();

        return back()->with('success', 'IP removido com sucesso!');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'image|max:2048',
        ]);

        $user = auth()->user();

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
            $user = auth()->user();

            $validator = Validator::make($request->all(), [
                'senha_atual' => ['required', 'current_password'],
                'nova_senha' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'senha_atual.required' => 'A senha atual é obrigatória.',
                'senha_atual.current_password' => 'A senha atual está incorreta.',
                'nova_senha.required' => 'A nova senha é obrigatória.',
                'nova_senha.min' => 'A nova senha deve ter no mínimo 8 caracteres.',
                'nova_senha.confirmed' => 'A confirmação da nova senha não confere.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $user->update([
                'password' => Hash::make($request->nova_senha),
            ]);

            return back()->with('success', 'Senha alterada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao alterar senha do usuário ID ' . auth()->id() . ': ' . $e->getMessage());

            return back()->withErrors(['erro' => 'Ocorreu um erro inesperado. Tente novamente mais tarde.'])->withInput();
        }
    }

    public function updateUtmfy(Request $request)
    {
        auth()->user()->update([
            'utmfy' => $request->utmfy,
        ]);

        auth()->user()->fresh();

        return back()->with('success', 'Token UTMFY atualizado com sucesso!');
    }

    public function updateSpedy(Request $request)
    {
        auth()->user()->update([
            'spedy' => $request->spedy,
        ]);

        auth()->user()->fresh();

        return back()->with('success', 'API Key Spedy atualizado com sucesso!');
    }

    public function removeUtmfy(Request $request)
    {
        auth()->user()->update([
            'utmfy' => null,
        ]);

        auth()->user()->fresh();

        return back()->with('success', 'Token UTMFY removido com sucesso!');
    }


    public function recPassword(Request $request)
    {
        $setting = Setting::first();
        if (
            !empty($setting->mail_host) &&
            !empty($setting->mail_port) &&
            !empty($setting->mail_username) &&
            !empty($setting->mail_password)
        ) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('tipo_auth', 'new-password')
                    ->withInput();
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()
                    ->withErrors(['email' => "Email não encontrado na base de dados."])
                    ->with('tipo_auth', 'new-password')
                    ->withInput();
            }

            $senhaProvisoria = uniqid();
            $hash = Hash::make($senhaProvisoria);
            $user->update(['password' => $hash]);
            $user->save();

            $assunto = "Produtor - Sua nova senha de acesso.";
            Mail::to($user->email)->queue(new SendNewPasswordProdutor($user, $senhaProvisoria, $assunto));

            return redirect()->back()
                ->with('tipo_auth', 'aluno')
                ->with('success', "Senha enviada para {$user->email}.");
        }
        return redirect()->back()
            ->with("error", "Sistema de recuperação de acesso temporáriamente indisponível.");
    }
}
