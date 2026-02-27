<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersKey;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class CheckTokenAndSecret
{
    public function handle(Request $request, Closure $next)
    {
        // Tenta ler token e secret de múltiplas fontes (JSON bruto, input processado, query string)
        $token = null;
        $secret = null;
        
        // 1. Tenta ler do JSON bruto primeiro (mais confiável para requisições JSON)
        $contentType = $request->header('Content-Type', '');
        $isJsonRequest = str_contains(strtolower($contentType), 'application/json') || $request->isJson();
        
        if ($isJsonRequest) {
            $rawContent = $request->getContent();
            if (!empty($rawContent)) {
                $jsonData = json_decode($rawContent, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                    $token = $jsonData['token'] ?? null;
                    $secret = $jsonData['secret'] ?? null;
                }
            }
        }
        
        // 2. Se não encontrou no JSON bruto, tenta métodos do Laravel
        if (empty($token) || empty($secret)) {
            $token = $token ?? $request->input('token') ?? $request->json('token') ?? $request->query('token') ?? null;
            $secret = $secret ?? $request->input('secret') ?? $request->json('secret') ?? $request->query('secret') ?? null;
        }

        // Log para debug (sempre logar para diagnóstico)
        Log::debug('[API][TOKEN_SECRET] -> Verificando autenticação', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'content_type' => $contentType,
            'is_json' => $isJsonRequest,
            'has_token' => !empty($token),
            'has_secret' => !empty($secret),
            'token_preview' => $token ? substr($token, 0, 30) . '...' : null,
            'secret_preview' => $secret ? substr($secret, 0, 30) . '...' : null,
            'raw_content_preview' => $isJsonRequest ? substr($request->getContent(), 0, 200) : null,
            'all_input' => $request->all(),
        ]);

        // Verifique se ambos os parâmetros token e secret foram enviados
        if (empty($token) || empty($secret)) {
            Log::warning('[API][TOKEN_SECRET] -> Token ou Secret ausentes', [
                'url' => $request->fullUrl(),
                'has_token' => !empty($token),
                'has_secret' => !empty($secret),
            ]);
            
            return Response::json([
                'error' => 'Token ou Secret ausentes',
                'message' => 'Você precisa fornecer tanto o token quanto o secret.'
            ], 400); // Retorna um erro 400 se os parâmetros não forem fornecidos
        }

        // Verifique se existe um usuário com esse token e secret
        $user = User::where('clientId', $token)->where('secret', $secret)->first();
        
        // Se o usuário não for encontrado, retorna um erro
        if (!$user) {
            Log::warning('[API][TOKEN_SECRET] -> Token ou Secret inválidos', [
                'url' => $request->fullUrl(),
                'token' => $token,
                'token_exists' => User::where('clientId', $token)->exists(),
                'secret_exists' => User::where('secret', $secret)->exists(),
            ]);
            
            return Response::json([
                'status' => "error",
                'message' => 'Token ou Secret inválidos'
            ], 401); // Retorna um erro 401 se o token ou secret não forem válidos
        }

        // Verifica se o usuário está banido ou não aprovado
        if($user->banido == 1 || $user->status != 'aprovado'){
            Log::warning('[API][TOKEN_SECRET] -> Usuário sem permissões', [
                'user_id' => $user->id,
                'banido' => $user->banido,
                'status' => $user->status,
            ]);
            
            return Response::json([
                'status' => "error",
                'message' => 'Usuário sem permissões. Fale com seu gerente.'
            ], 401); // Retorna um erro 401 se o token ou secret não forem válidos
        }
        
        // Se o usuário for encontrado, adicione o usuário à requisição
        $request->merge(['user' => $user]);

        // Log de sucesso (apenas em debug)
        if (config('app.debug')) {
            Log::debug('[API][TOKEN_SECRET] -> Autenticação bem-sucedida', [
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);
        }

        // Prossiga com a requisição
        return $next($request);
    }
}
