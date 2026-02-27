<?php

namespace App\Http\Middleware;

use App\Models\Pedido;
use App\Models\Setting;
use App\Models\TransactionIn;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Helper;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->permission !== 'admin') {
            
            // If the user is not authenticated or not an admin, redirect to the login page
            return abort(403);
        }
        Helper::calcularSaldoLiquidoUsuarios();
        $transactionsIn = TransactionIn::where('status', 'revisao')->get();
        foreach ($transactionsIn as $transaction) {
            $setting = Setting::first();

            $tempo = $setting->card_days_to_release;
            if ($transaction->method == 'billet') {
                $tempo = $setting->billet_days_to_release;
            }

            $pagoem = $transaction->created_at;
            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
           //dd($diasrestantes);
            if($diasrestantes === 0){
                $transaction->update(['status' => 'pago']);
                $pedido = Pedido::where('idTransaction', $transaction->idTransaction)->first();
                if($pedido){
                    $pedido->update(['status' => 'pago']);
                }
            }
            # code...
        }
        return $next($request);
    }
}
