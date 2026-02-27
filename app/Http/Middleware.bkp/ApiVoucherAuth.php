<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiVoucherAuth
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
        // Token esperado (definido no .env)
        $expectedToken = env('API_VOUCHER_TOKEN', 'seu-token-secreto-aqui');
        
        // Token enviado no header
        $requestToken = $request->header('X-API-Token');
        
        // Validar token
        if (empty($requestToken) || $requestToken !== $expectedToken) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token de autenticação inválido ou ausente.'
            ], 401);
        }
        
        return $next($request);
    }
}
