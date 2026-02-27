<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Mail\SendCredentialsAluno;
use App\Models\Aluno;
use App\Models\Callback;
use App\Models\Link;
use App\Models\Pedido;
use App\Models\Setting;
use App\Models\TransactionIn;
use App\Models\TransactionOut;
use App\Models\User;
use App\Notifications\GeralNotification;
use App\Traits\Tracking\UtmfyTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessaCallback
{
    public function __construct() {}

    public function deposit(string $idTransaction, string $status, $end2end = null, string $adquirente)
    {
        Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> idTransaction: {$idTransaction}, status: {$status}...");
        $cashin = TransactionIn::where('external_id', $idTransaction)->first();

        if (!$cashin || $cashin->status != "pendente") {
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> idTransaction: {$idTransaction} já foi pago.");
            return response()->json(['status' => false]);
        }

        $andtwoend = $end2end ?? $idTransaction;
        $updated_at = Carbon::now();
        $cashin->update(['status' => $status, 'end2end' => $andtwoend, 'updated_at' => $updated_at]);
        Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> idTransaction: {$idTransaction}, atualizado para o status: {$status}...");
        
        if(TransactionIn::where('external_id', $idTransaction)->count() > 1) {
            TransactionIn::where('external_id', $idTransaction)->update(['status' => $status, 'end2end' => $andtwoend, 'updated_at' => $updated_at]);
        }

        $user = User::find($cashin->user_id);
        Helper::incrementAmount($user, $cashin->deposito_liquido, 'saldo');
        Helper::calculaSaldoLiquido($user->id);
        Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Saldo de {$user->name} atualizado..");

        $pedido = Pedido::where('idTransaction', $idTransaction)->first();
        if (!$pedido) {
            $valorRecebido = number_format($cashin->amount, 2, ',', '.');
            $user->notify(new GeralNotification(
                'PIX Recebido.',
                "Você recebeu um PIX no valor de R$ {$valorRecebido}",
                "/extratos/depositos?periodo=dia"
            ));
        }

        if ($pedido) {
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Pedido encontrado para idTransaction: {$idTransaction}...");
            $pedido->update(['status' => 'pago', 'updated_at' => $updated_at]);
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Pedido atualizado para idTransaction: {$idTransaction}, status: {$status}...");

            $valorRecebido = number_format($cashin->amount, 2, ',', '.');
            $user->notify(new GeralNotification(
                'Venda realizada.',
                "Nova venda venda realizada no valor de R$ {$valorRecebido}.",
                "/pedidos?status=aprovados"
            ));
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Notificação enviada para: {$user->name}, venda no valor de: R$ {$valorRecebido}...");

            if ($user->utmfy) {
                Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> UTMFY integrado, enviando dados...");
                UtmfyTrait::gerarUTM('pix', 'paid', $cashin, $pedido->produto, $user->utmfy, '0.0.0.0');
            }

            // Criação de aluno e envio de credenciais só faz sentido quando há Pedido (produtos),
            // não para depósitos via API / Links sem Pedido associado.
            $senhaProvisoria = uniqid();
            Log::info("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Dados do Aluno ", ["email" => $pedido->comprador['email'], "senha" => $senhaProvisoria]);

            $aluno = Aluno::where('email', $pedido->comprador['email'])
                ->where('cpf', $pedido->comprador['cpf'])->first();
            if (!$aluno) {
                $aluno = Aluno::create([
                    'name' => $pedido->comprador['name'],
                    'email' => $pedido->comprador['email'],
                    'cpf' => $pedido->comprador['cpf'],
                    'password' => Hash::make($senhaProvisoria)
                ]);
            }

            $pedido->update(['aluno_id' => $aluno->id]);
            Log::info("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Aluno cadastrado...");

            $produto = $pedido->produto->name;

            $setting = Setting::first();
            if (!empty($setting->mail_username) && !empty($setting->mail_password)) {
                $assunto = "Compra confirmada - $produto : Segue os dados de acesso a Área de membros.";
                Mail::to($aluno->email)->queue(new SendCredentialsAluno($aluno, $senhaProvisoria, $assunto));
                Log::info("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Email enviado para aluno: {$aluno->email}");
            }
        }

        if ($user->client_indication) {
            $split = TransactionIn::where('external_id', $idTransaction)->first();
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Cliente foi indicado por {$split->user?->name}...");
            if ($split) {
                $cashin->update(['status' => 'pago', 'updated_at' => $updated_at]);
                
                Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Comissão paga para o afiliado {$split->user?->name}...");
            }
        }

        $enviar = TransactionIn::where('external_id', $idTransaction)->get();
        foreach($enviar as $trans){
            $valor = $trans->cash_in_liquido + $trans->taxa_reserva;
            $fcm = new FirebaseService();
            $fcm->sendNotification(
                $trans->user,
                $valor
            );
        }

        if (str_contains($cashin->callbackUrl, 'http')) {
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Webhook encontrado {$cashin->callbackUrl} enviando callback...");

            $call = Callback::create([
                'transaction_cash_in_id' => $cashin->id,
                'status' => 'pendente',
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ])
                ->post($cashin->callbackUrl, [
                    "status"          => "paid",
                    "idTransaction"   => $cashin->idTransaction,
                    "typeTransaction" => "PIX"
                ]);

            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Callback enviado para {$cashin->callbackUrl}...");
            if ($response->successful()) {
                $call->update(['status' => 'enviado', 'message' => "Enviado com sucesso."]);
            }
        }


        // webhooks do produto
        if ($pedido && optional($pedido->produto)->webhooks()->count() > 0) {
            Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Webhooks de produtos encontrados...");
            foreach ($pedido->produto->webhooks as $webhook) {
                if (!isset($webhook->url)) continue;

                $method = strtolower($webhook->method);
                if (!in_array($method, ['post', 'put', 'get'])) continue;

                $payload = [
                    "product" => [
                        'name' => strtolower($pedido->produto->name),
                        'price' => (float) $pedido->produto->price
                    ],
                    "status"          => "paid",
                    "typeTransaction" => "PIX",
                    "idTransaction"   => $cashin->idTransaction,
                    "client" => [
                        "cpf"   => preg_replace('/\D/', '', $pedido->comprador['cpf']),
                        "name"  => strtolower($pedido->comprador['name']),
                        "email" => strtolower($pedido->comprador['email']),
                        "phone" => "55" . preg_replace('/\D/', '', $pedido->comprador['phone'])
                    ],
                ];

                Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Enviando Webhook de produtos para {$webhook->url}, body: " . json_encode($payload));

                if ($method === 'get') {
                    Http::withHeaders(['accept' => 'application/json'])->get($webhook->url, $payload);
                } else {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->$method($webhook->url, $payload);
                }

                Log::debug("[+][CALLBACK][DEPOSIT][{$adquirente}] -> Webhook enviado para {$webhook->url}. ");
            }
        }

        // webhooks do usuário
        if ($cashin->user && $cashin->user->webhooks()->count() > 0) {
            foreach ($cashin->user->webhooks as $webhook) {
                if (!isset($webhook->url) || !str_contains($webhook->url, 'http')) continue;
                if ($webhook->type == 'produto') continue;

                $method = 'post';
                if (!in_array($method, ['post', 'put', 'get'])) continue;

                $payload = [
                    "status"          => "paid",
                    "typeTransaction" => "PIX",
                    "idTransaction"   => $cashin->idTransaction,
                    "client" => [
                        "name" => $cashin->client_name,
                        "cpf"  => $cashin->client_cpf,
                    ],
                ];

                if ($method === 'get') {
                    Http::withHeaders(['accept' => 'application/json'])->get($webhook->url, $payload);
                } else {
                    Http::withHeaders([
                        'Content-Type' => 'application/json',
                        'accept' => 'application/json'
                    ])->$method($webhook->url, $payload);
                }
            }
        }


        return $cashin;
    }


    public function withdraw(string $idTransaction, string $status, $end2end = null, string $adquirente)
    {
        Log::debug("[+][CALLBACK][WITHDRAW][{$adquirente}] -> idTransaction: {$idTransaction}, status: {$status}...");
        if ($status == "pago") {
            $cashout = TransactionOut::where('external_id', $idTransaction)->first();
            if (!$cashout) {
                return response()->json(['status' => false]);
            }

            $andtwoend = $end2end ?? $idTransaction;
            $updated_at = Carbon::now();
            $cashout->update(['status' => 'pago', 'end2End' => $andtwoend, 'updated_at' => $updated_at]);
            Log::debug("[+][CALLBACK][WITHDRAW][{$adquirente}] -> idTransaction: {$idTransaction}, atualizado para o status: {$status}...");

            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $cashout->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);
            Log::debug("[+][CALLBACK][WITHDRAW][{$adquirente}] -> Saldo de {$user->name} atualizado..");

            try {
                if (str_contains('http', $cashout->callbackUrl)) {
                    Log::debug("[+][CALLBACK][WITHDRAW][{$adquirente}] -> Callback encontrado: $cashout->callbackUrl -> Enviando...");
                    $call = Callback::create([
                        'transaction_cash_out_id' => $cashout->id,
                        'status' => 'pendente',
                    ]);

                    $response = Http::withHeaders([
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

                if ($response->successful()) {
                    $call->update(['status' => 'enviado', 'message' => "Enviado com sucesso."]);
                }
            } catch (\Exception $callbackError) {
                Log::error("[+][CALLBACK][WITHDRAW][{$adquirente}] -> Erro ao enviar callback para " . $cashout->callbackUrl . ' falhou para, mas depósito foi aprovado', [
                    'deposit_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);

                $call = Callback::where('transaction_cash_out_id', $cashout->id)->first();
                if ($call) {
                    $call->update(['status' => 'falhou', 'message' => 'Falha ao enviar callback.']);
                }
            }
        } elseif ($status == "cancelado") {
            $cashout = TransactionOut::where('external_id', $idTransaction)->first();
            if (!$cashout) {
                return response()->json(['status' => false]);
            }

            $andtwoend = $end2end ?? $idTransaction;
            $updated_at = Carbon::now();
            $cashout->update(['status' => 'cancelado', 'end2End' => $andtwoend, 'updated_at' => $updated_at]);
            $user = User::where('id', $cashout->user_id)->first();

            Helper::decrementAmount($user, $cashout->amount, 'valor_saque_pendente');
            Helper::calculaSaldoLiquido($user->id);

            try {
                if (str_contains($cashout->callbackUrl, 'http')) {
                    Log::debug("[+][CALLBACK][WITHDRAW][{$adquirente}] -> Callback encontrado $cashout->callbackUrl -> Enviando...");

                    $call = Callback::create([
                        'transaction_cash_out_id' => $cashout->id,
                        'status' => 'pendente',
                    ]);

                    $response = Http::withHeaders([
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

                if ($response->successful()) {
                    $call->update(['status' => 'enviado', 'message' => "Enviado com sucesso."]);
                }
            } catch (\Exception $callbackError) {
                Log::error('[+][CALLBACK][WITHDRAW][{$adquirente}] -> Falha ao enviar callback para ' . $cashout->callbackUrl . ' falhou para, mas saque foi cancelado', [
                    'saque_id' => $cashout->id,
                    'error' => $callbackError->getMessage()
                ]);

                $call = Callback::where('transaction_cash_out_id', $cashout->id)->first();
                if ($call) {
                    $call->update(['status' => 'falhou', 'message' => 'Falha ao enviar callback.']);
                }
            }
        }
    }
}
