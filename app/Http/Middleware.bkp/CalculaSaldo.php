<?php

namespace App\Http\Middleware;

use App\Models\Pedido;
use App\Models\Setting;
use App\Models\TransactionIn;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Helper;

class CalculaSaldo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Helper::calculaSaldoLiquido(auth()->id());
        $transactionsIn = auth()->user()->transactions_in()->where('status', 'revisao')->get();
        foreach ($transactionsIn as $transaction) {
            $setting = Setting::first();

            $tempo = $setting->card_days_to_release;
            if ($transaction->method == 'billet') {
                $tempo = $setting->billet_days_to_release;
            }

            if ($transaction->method == 'card' && $transaction->dias_recebimento > 0) {
                $tempo = $transaction->dias_recebimento;
            }

            $pagoem = $transaction->created_at;
            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
            //dd($diasrestantes);
            if ($diasrestantes === 0) {
                $transaction->update(['status' => 'pago']);
                $pedido = Pedido::where('idTransaction', $transaction->idTransaction)->first();
                if ($pedido) {
                    $pedido->update(['status' => 'pago']);
                }
            }
        }

        $transReserva = auth()->user()->transactions_in()
            ->where('taxa_reserva', '>', 0)
            ->where('taxa_reserva_resgatada', 0)
            ->get();

        foreach ($transReserva as $reserva) {
            $tempo = $reserva->dias_liberar_reserva;

            $pagoem = $reserva->created_at;
            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
            if ($diasrestantes === 0) {
                $reserva->update(['taxa_reserva_resgatada' => true]);
            }
        }
        return $next($request);
    }
}
