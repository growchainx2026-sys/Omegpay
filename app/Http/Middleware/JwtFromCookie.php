<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Log;

class JwtFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('token');

        if (!$token) {
            Log::warning('Token JWT ausente no cookie.');
            return $next($request); // Deixa passar se a rota não exigir auth
        }

        // Validação simples de estrutura do token
        if (substr_count($token, '.') !== 2) {
            Log::error('Token JWT com estrutura inválida: ' . var_export($token, true));
            return $next($request);
        }

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if ($user) {
                // Opcional: colocar o usuário no request
                $request->setUserResolver(fn () => $user);
                Log::info("Usuário autenticado via cookie: " . $user->email);
            } else {
                Log::warning('Usuário não encontrado com token válido.');
            }

        } catch (JWTException $e) {
            Log::error('Erro ao autenticar com token JWT: ' . $e->getMessage());
        }

        return $next($request);
    }
}
