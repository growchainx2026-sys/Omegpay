<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Domínios permitidos
        $allowedOrigins = [
            'https://www.nxcreative.com.br',
            'https://nxcreative.com.br',
            'http://localhost:3000', // Para testes locais
        ];

        $origin = $request->header('Origin');

        // Verificar se a origem está na lista de permitidos
        if (in_array($origin, $allowedOrigins)) {
            $headers = [
                'Access-Control-Allow-Origin' => $origin,
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, X-API-Token, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true',
            ];

            // Se for requisição OPTIONS (preflight)
            if ($request->isMethod('OPTIONS')) {
                return response()->json(['status' => 'OK'], 200, $headers);
            }

            $response = $next($request);

            // Adicionar headers na resposta
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }

            return $response;
        }

        return $next($request);
    }
}
