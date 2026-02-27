<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\TransactionIn;
use App\Models\User;
use App\Models\TransactionOut;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\App;
use App\Models\CheckoutOrders;
use App\Models\Pedido;
use App\Models\Transactions;
use App\Traits\Tracking\UtmfyTrait;

class CallbackController extends Controller
{
    /**
     * Handle the deposit callback from Cashtime.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        \Log::debug("[PIX-IN] Received Callback: " . json_encode($data));
        if ($data['status'] == "paid") {
            $cashin = TransactionIn::where('external_id', $data['orderId'])->first();
            if (!$cashin || $cashin->status != "pendente") {
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);

            $user = User::where('id', $cashin->user_id)->first();
            Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
            Helper::calculaSaldoLiquido($user->id);

            $pedido = Pedido::where('idTransaction', $cashin->idTransaction)->first();
            if (isset($pedido)) {
                $pedido->update(['status' => 'pago', 'updated_at' => $updated_at]);
                $pedido->save();

                if ($user->utmfy) {
                    UtmfyTrait::gerarUTM(
                        'pix',
                        'paid',
                        $cashin,
                        $pedido->produto,
                        $user->utmfy,
                        '0.0.0.0'
                    );
                }
            }

            if ($user->client_indication) {
                $split = TransactionIn::where('external_id', $data['orderId'])->first();
                if ($split) {
                    $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);
                }
            }

            try {
                \Log::debug("[PIX-IN] Send Callback: Para $cashin->callbackUrl -> Enviando...");
                if ($cashin->callbackUrl && $cashin->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashin->callbackUrl, [
                            "status"            => "paid",
                            "idTransaction"     => $cashin->idTransaction,
                            "typeTransaction"   => "PIX"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-IN] Send Callback: ' . $cashin->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                    'deposit_id' => $cashin->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        }
    }

    /**
     * Handle the withdrawal callback from Cashtime.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackWithdraw(Request $request)
    {
        $data = $request->all();

        \Log::debug("[PIX-OUT] Received Callback: " . json_encode($data));

        if ($data['withdrawStatusId'] == "Successfull") {
            $cashout = TransactionOut::where('external_id', $data['id'])->first();
            if (!$cashout || $cashout->status != "pendente") {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'pago', 'updated_at' => $data['updatedAt']]);
            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);

            try {
                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashout->callbackUrl, [
                            "status"            => "paid",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                    'deposit_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        } elseif ($data['withdrawStatusId'] == "Canceled") {
            $cashout = TransactionOut::where('external_id', $data['id'])->first();
            if (!$cashout) {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'cancelado', 'updated_at' => $data['updatedAt']]);
            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);

            try {
                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashout->callbackUrl, [
                            "status"            => "canceled",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas saque foi cancelado', [
                    'saque_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        }
    }

    /**
     * Handle the deposit callback from Cashtime.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackDepositSixxpayments(Request $request)
    {
        $data = $request->all();
        \Log::debug("[PIX-IN] Received Callback: " . json_encode($data));
        if ($data['status'] == "paid") {
            $cashin = TransactionIn::where('external_id', $data['orderId'])->first();
            if (!$cashin || $cashin->status != "pendente") {
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);

            $user = User::where('id', $cashin->user_id)->first();
            Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
            Helper::calculaSaldoLiquido($user->id);

            $pedido = Pedido::where('idTransaction', $cashin->idTransaction)->first();
            if (isset($pedido)) {
                $pedido->update(['status' => 'pago', 'updated_at' => $updated_at]);
                $pedido->save();

                if ($user->utmfy) {
                    UtmfyTrait::gerarUTM(
                        'pix',
                        'paid',
                        $cashin,
                        $pedido->produto,
                        $user->utmfy,
                        '0.0.0.0'
                    );
                }
            }

            /*  if(!is_null($user->webhook_cash_in) && $cashin->callbackUrl != 'web'){
                if ($cashin->callbackUrl && $cashin->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                    ->post($user->webhook_cash_in, [
                        "type"              => "deposito",
                        "nome"              => $cashin->client_name,
                        "nome"              => $cashin->client_name,
                        "status"            => "pago",
                        "idTransaction"     => $cashin->idTransaction,
                        "typeTransaction"   => "PIX"
                    ]);
                }
            } */

            try {
                \Log::debug("[PIX-IN] Send Callback: Para $cashin->callbackUrl -> Enviando...");
                if ($cashin->callbackUrl && $cashin->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashin->callbackUrl, [
                            "status"            => "paid",
                            "idTransaction"     => $cashin->idTransaction,
                            "typeTransaction"   => "PIX"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-IN] Send Callback: ' . $cashin->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                    'deposit_id' => $cashin->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        }
    }

    /**
     * Handle the withdrawal callback from Cashtime.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbackWithdrawSixxpayments(Request $request)
    {
        $data = $request->all();

        \Log::debug("[PIX-OUT] Received Callback: " . json_encode($data));

        if ($data['withdrawStatusId'] == "Successfull") {
            $cashout = TransactionOut::where('external_id', $data['id'])->first();
            if (!$cashout || $cashout->status != "pendente") {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'pago', 'updated_at' => $data['updatedAt']]);
            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);

            try {
                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashout->callbackUrl, [
                            "status"            => "paid",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                    'deposit_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        } elseif ($data['withdrawStatusId'] == "Canceled") {
            $cashout = TransactionOut::where('external_id', $data['id'])->first();
            if (!$cashout) {
                return response()->json(['status' => false]);
            }

            $cashout->update(['status' => 'cancelado', 'updated_at' => $data['updatedAt']]);
            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);

            try {
                \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])
                        ->timeout(10)
                        ->retry(3, 500)
                        ->post($cashout->callbackUrl, [
                            "status"            => "canceled",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas saque foi cancelado', [
                    'saque_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);
            }
        }
    }


    public function callbackEfi(Request $request)
    {
        $data = $request->all();
        \Log::debug('[+] Callback Efí: ' . json_encode($data));
        if (isset($data['evento']) && $data['evento'] == 'teste_webhook') {
            \Log::debug('[+] Callback Efí retornou status = success');
            return response()->json([], 200);
        }

        $dados = $data['pix'][0];
        $tipo = isset($dados['tipo']) && $dados['tipo'] == 'SOLICITACAO' ? 'saque' : 'deposito';
        \Log::debug('[+] Tipo de callback Efí: ' . $tipo);

        switch ($tipo) {
            case 'deposito':
                $idTransaction = $dados['txid'];
                $existEndToEndId = isset($dados['endToEndId']) && isset($dados['gnExtras']['pagador']);
                if ($existEndToEndId) {
                    $cashin = TransactionIn::where('external_id', $idTransaction)->first();
                    if (!$cashin || $cashin->status != "pendente") {
                        return response()->json(['status' => false]);
                    }

                    $updated_at = Carbon::now();
                    $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);

                    $user = User::where('id', $cashin->user_id)->first();
                    Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
                    Helper::calculaSaldoLiquido($user->id);

                    $pedido = Pedido::where('idTransaction', $cashin->idTransaction)->first();
                    if (isset($pedido)) {
                        $pedido->update(['status' => 'pago', 'updated_at' => $updated_at]);
                        $pedido->save();

                        if ($user->utmfy) {
                            UtmfyTrait::gerarUTM(
                                'pix',
                                'paid',
                                $cashin,
                                $pedido->produto,
                                $user->utmfy,
                                '0.0.0.0'
                            );
                        }
                    }

                    if ($user->client_indication) {
                        $split = TransactionIn::where('external_id', $idTransaction)->first();
                        if ($split) {
                            $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);
                        }
                    }

                    try {
                        \Log::debug("[PIX-IN] Send Callback: Para $cashin->callbackUrl -> Enviando...");
                        if ($cashin->callbackUrl && $cashin->callbackUrl != 'web') {
                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])
                                ->timeout(10)
                                ->retry(3, 500)
                                ->post($cashin->callbackUrl, [
                                    "status"            => "paid",
                                    "idTransaction"     => $cashin->idTransaction,
                                    "typeTransaction"   => "PIX"
                                ]);
                        }
                    } catch (\Exception $callbackError) {
                        Log::error('[PIX-IN] Send Callback: ' . $cashin->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                            'deposit_id' => $cashin->id,
                            'error' => $callbackError->getMessage()
                        ]);
                    }
                }
                break;
            case 'saque':
                $idTransaction = $dados['gnExtras']['idEnvio'];
                if ($dados['status'] == "REALIZADO") {
                    $cashout = TransactionOut::where('external_id', $idTransaction)->first();
                    if (!$cashout || $cashout->status != "pendente") {
                        return response()->json(['status' => false]);
                    }

                    $updated_at = Carbon::now();
                    $cashout->update(['status' => 'pago', 'updated_at' => $updated_at]);
                    $user = User::where('id', $cashout->user_id)->first();

                    Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
                    Helper::calculaSaldoLiquido($user->id);

                    try {
                        \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                        if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])
                                ->timeout(10)
                                ->retry(3, 500)
                                ->post($cashout->callbackUrl, [
                                    "status"            => "paid",
                                    "idTransaction"     => $cashout->idTransaction,
                                    "typeTransaction"   => "PAYMENT"
                                ]);
                        }
                    } catch (\Exception $callbackError) {
                        Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                            'deposit_id' => $cashout->id,
                            'error' => $callbackError->getMessage()
                        ]);
                    }
                } elseif ($dados['status'] == "NAO_REALIZADO") {
                    $cashout = TransactionOut::where('external_id', $idTransaction)->first();
                    if (!$cashout) {
                        return response()->json(['status' => false]);
                    }

                    $updated_at = Carbon::now();
                    $cashout->update(['status' => 'cancelado', 'updated_at' => $updated_at]);
                    $user = User::where('id', $cashout->user_id)->first();

                    Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');
                    Helper::calculaSaldoLiquido($user->id);

                    try {
                        \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callbackUrl -> Enviando...");
                        if ($cashout->callbackUrl && $cashout->callbackUrl != 'web') {
                            Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'accept' => 'application/json'
                            ])
                                ->timeout(10)
                                ->retry(3, 500)
                                ->post($cashout->callbackUrl, [
                                    "status"            => "canceled",
                                    "idTransaction"     => $cashout->idTransaction,
                                    "typeTransaction"   => "PAYMENT"
                                ]);
                        }
                    } catch (\Exception $callbackError) {
                        Log::error('[PIX-OUT] Send Callback: ' . $cashout->callbackUrl . ' falhou para, mas saque foi cancelado', [
                            'saque_id' => $cashout->id,
                            'error' => $callbackError->getMessage()
                        ]);
                    }
                    break;
                }
        }
    }


    public function webhookTransfeera(Request $request)
    {
        $data = $request->all();
        Log::debug("[TRANSFEERA][PIX-IN][WEBHOOK][DEPOSIT] BODY: ".json_encode($data));
        
        if($data['object'] == "CashIn" && isset($data['data']['payer']['name'])){
            $transaction_id = $data['data']['txid'];
            $cashin = TransactionIn::where('idTransaction', $transaction_id)->first();
            Log::debug("[TRANSFEERA][PIX-IN][WEBHOOK][DEPOSIT] SOLICITAÇÃO: ".json_encode($cashin));

            if(isset($cashin) && $cashin->status != "pendente"){
                return response()->json(['status' => false]);
            }

            $updated_at = Carbon::now();
            $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);
            Log::debug("[TRANSFEERA][PIX-IN][WEBHOOK][DEPOSIT] UPDATE: REALIZADO...");
            $user = User::where('user_id', $cashin->user_id)->first();
            Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
            Helper::calculaSaldoLiquido($user->user_id);
            Log::debug("[TRANSFEERA][PIX-IN][WEBHOOK][DEPOSIT] SALDOS: RECALCULADO...");
            if($cashin->callback){
                $payload = [
                    "status"            => "paid",
                    "idTransaction"     => $cashin->idTransaction,
                    "typeTransaction"   => "PIX"
                ];
                Log::debug("[TRANSFEERA][PIX-IN][WEBHOOK][DEPOSIT] PAYLOAD CALLBACK USUÁRIO: ".json_encode($payload));
                Http::withHeaders([
                    'Content-Type' => 'application/json', 
                    'accept' => 'application/json'
                ])->post($cashin->callback, $payload);
                
                if ($cashin->callback && $cashin->callback != 'web') {
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashin->idTransaction,
                        "typeTransaction"   => "PIX"
                    ];
                    
                    Http::withHeaders([
                        'Content-Type' => 'application/json', 
                        'accept' => 'application/json'
                    ])->post($cashin->callback, $payload);
                                  
                    return response()->json(['status' => true]);
                }
            }
        }

        if($data['object'] == 'Transfer' && $data['data']['status'] === "FINALIZADO"){
            $transaction_id = $data['data']['idempotency_key'];
            $end2end = $data['data']['authorization_code'] ?? $transaction_id;
            $cashout = TransactionOut::where('idTransaction', $transaction_id)->first();
            Log::debug("[TRANSFEERA][PIX-OUT] CASHOUT:".json_encode($cashout));

            $user = User::where('user_id', $cashout->user_id)->first();
            Log::debug("[TRANSFEERA][PIX-OUT] USER:".json_encode($user));

            if(!$cashout || $cashout->status != "pendente"){
                return response()->json(['status' => false]);
            }
            Log::debug("[TRANSFEERA][PIX-OUT] Status : PENDENTE");
                $cashout->update(['status' => 'pago', 'end2end' => $end2end]);

                Log::debug("[TRANSFEERA][PIX-OUT] Update : Realizado...");

                Helper::decrementAmount($user, $request->amount, 'valor_saque_pendente');

                Log::debug("[TRANSFEERA][PIX-OUT] Saldo : Atualizado...");

                if($cashout->callback){
                    $payload = [
                        "status"            => "paid",
                        "idTransaction"     => $cashout->idTransaction,
                        "typeTransaction"   => "PAYMENT"
                    ];

                    Log::debug("[TRANSFEERA][PIX-OUT] PAYLOAD CALLBACK:".json_encode($payload));

                    $sendcallback = Http::withHeaders([
                        'Content-Type' => 'application/json', 
                        'accept' => 'application/json'
                    ])->post($cashout->callback, $payload);
                    
                    \Log::debug("[PIX-OUT] Send Callback: Para $cashout->callback -> Enviando...");
                    if ($cashout->callback && $cashout->callback != 'web') {
                        $payload = [
                            "status"            => "paid",
                            "idTransaction"     => $cashout->idTransaction,
                            "typeTransaction"   => "PAYMENT"
                        ];
                    
                        Http::withHeaders([
                            'Content-Type' => 'application/json', 
                            'accept' => 'application/json'
                        ])->post($cashout->callback, $payload);
                                        
                        return response()->json(['status' => true]);
                    }
                }
        }
    }
}
