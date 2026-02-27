<?php

namespace App\Traits;

use App\Services\PagarmeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{User, Pagarme, Setting, TransactionIn, TransactionOut};
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;
use App\Services\ProcessaCallback;

trait PagarmeTrait
{
    protected static string $secret;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;
    protected static string $cardTxFixed;
    protected static string $cardTxPercent;
    protected static string $billetTxFixed;
    protected static string $billetTxPercent;

    protected static function generateCredentialPagarme()
    {

        $setting = Pagarme::first();
        if (!$setting) {
            return false;
        }

        self::$secret = $setting->secret;
        self::$taxaCashIn = $setting->tx_pix_cash_in;
        self::$taxaCashOut = $setting->tx_pix_cash_out;
        self::$cardTxFixed = $setting->tx_card_fixed;
        self::$cardTxPercent = $setting->tx_card_percent;
        self::$billetTxFixed = $setting->tx_billet_fixed;
        self::$billetTxPercent = $setting->tx_billet_percent;

        return true;
    }

    public static function requestDepositPagarme($data)
    {
        if (self::generateCredentialPagarme()) {

            $pagarme = new PagarmeService(self::$secret);
            $response = $pagarme->paymentPix($data, $data->amount);
            //dd($response);

            if (isset($response['id'])) {

                $responseData = $response;
                $external_id = $responseData['id'];
                $idTransaction = Str::uuid()->toString();

                $document = Helper::generateValidCpf();

                $user = $data->user;
                $setting = Setting::first();
                $taxafixa = $user->use_taxas_individual == 1 ? (float) $user->taxa_cash_in_fixa : (float) $setting->taxa_cash_in_fixa;
                $tx_reserva = $user->use_taxas_individual == 1 ? (float) $user->taxa_reserva : (float) $setting->taxa_reserva;

                $tx_cash_in = $user->use_taxas_individual == 1 ? $user->taxa_cash_in : $setting->taxa_cash_in;
                $taxatotal = ((float) $data->amount * (float) $tx_cash_in / 100);
                $deposito_liquido = (float) $data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float) $taxatotal < (float) $setting->baseline) {
                    $deposito_liquido = (float) $data->amount - (float) $setting->baseline;
                    $taxa_cash_in = (float) $setting->baseline;
                    $descricao = "FIXA";
                }

                $taxa_reserva = 0;
                if ((float) $tx_reserva > 0) {
                    $taxa_reserva += ((float) $data->amount * (float) $tx_reserva / 100);
                    $deposito_liquido -= $taxa_reserva;
                    $taxa_cash_in += $deposito_liquido;
                }

                $deposito_liquido -= $taxafixa;
                $taxa_cash_in += $taxafixa;

                $ip = $data->header('X-Forwarded-For') ?
                    $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                        $data->header('CF-Connecting-IP') :
                        $data->ip());

                $cashin = [
                    "external_id" => $external_id,
                    "amount" => $data->amount,
                    "client_name" => $data->debtor_name,
                    "client_cpf" => $document,
                    "client_email" => $data->email,
                    "client_telefone" => $data->phone, // ← ADICIONADO
                    "status" => "pendente",
                    "idTransaction" => $idTransaction,
                    "cash_in_liquido" => $deposito_liquido,
                    "taxa_reserva" => $taxa_reserva,
                    "qrcode_pix" => $responseData['charges'][0]['last_transaction']['qr_code'],
                    "paymentcode" => $responseData['charges'][0]['last_transaction']['qr_code'],
                    "paymentCodeBase64" => $responseData['charges'][0]['last_transaction']['qr_code'],
                    "adquirente_ref" => 'pagar.me',
                    "taxa_cash_in" => $taxa_cash_in,
                    "executor_ordem" => 'pagar.me',
                    "request_ip" => $ip,
                    "request_domain" => $data->httpHost(),
                    "type" => 'cash',
                    "plataforma" => 'api',
                    "user_id" => $data->user->id,
                    "descricao_transacao" => $descricao,
                    "callbackUrl" => $data->postback,
                ];

                TransactionIn::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $idTransaction,
                        "qrcode" => $responseData['charges'][0]['last_transaction']['qr_code'],
                        "qr_code_image_url" => $responseData['charges'][0]['last_transaction']['qr_code_url']
                    ],
                    "status" => 200
                ];
            }
        } else {
            return [
                "data" => [
                    'status' => 'error'
                ],
                "status" => 401
            ];
        }
    }

    public static function requestPaymentPagarme($request)
    {
        $user = User::where('id', $request->user->id)->first();

        $setting = Setting::first();
        $taxafixa = $user->use_taxas_individual == 1 ? $user->taxa_cash_out_fixa : $setting->taxa_cash_out_fixa;
        $tx_cash_out = $user->use_taxas_individual == 1 ? $user->taxa_cash_out : $setting->taxa_cash_out;

        $taxatotal = ((float) $request->amount * (float) $tx_cash_out / 100);
        $cashout_liquido = (float) $request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        $cashout_liquido = $cashout_liquido - $taxafixa;
        $taxa_cash_out = $taxa_cash_out + $taxafixa;

        if ($user->saldo < $cashout_liquido) {
            return [
                'data' => [
                    'status' => 'error',
                    'message' => "Saldo insuficiente."
                ],
                'status' => 401
            ];
        }

        $date = Carbon::now();

        return self::generateTransactionPaymentManualPagarme($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
    }

    protected static function generateTransactionPaymentManualPagarme($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();

        $internal_id = str_replace('-', '', (string) Str::uuid());
        $internal_id = strtoupper($internal_id);

        $name = $request->user->name;
        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $ip = $request->header('X-Forwarded-For') ?
            $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                $request->header('CF-Connecting-IP') :
                $request->ip());

        $pixcashout = [
            "user_id" => $request->user->id,
            "external_id" => $idTransaction,
            "amount" => $request->amount,
            "recebedor_name" => $name,
            "recebedor_cpf" => $pixKey,
            "pixKey" => $pixKey,
            "pixKeyType" => $request->pixKeyType,
            "status" => "pendente",
            "idTransaction" => $idTransaction,
            "cash_out_liquido" => $cashout_liquido,
            "adquirente_ref" => 'pagar.me',
            "taxa_cash_out" => $taxa_cash_out,
            "end2end" => $internal_id, // ID único para rastreamento
            "request_ip" => $ip,
            "request_domain" => $request->httpHost(),
            "type" => 'cash',
            "plataforma" => 'web',
            "descricao_transacao" => $descricao,
        ];

        TransactionOut::create($pixcashout);

        $user->saldo -= $cashout_liquido;
        $user->save();

        return [
            'data' => [
                'status' => 'success',
                'message' => 'Pagamento criado com sucesso.',
                'idTransaction' => $idTransaction
            ],
            'status' => 200
        ];
    }

    public static function requestPaymentCardPagarme($request, $data)
    {
        if (self::generateCredentialPagarme()) {
            
            $pagarme = new PagarmeService(self::$secret);
            $response = $pagarme->paymentCard(
                $data['items'][0]['value'],
                $data['description'],
                $data['cartao'],
                $data['installments'],
                $data['customer']
            );

            // ===== VALIDAÇÃO DA RESPOSTA =====
            if (!isset($response['charges']) || !is_array($response['charges']) || count($response['charges']) === 0) {
                Log::error('[PAGARME][CARD] Resposta inválida da API', [
                    'response' => $response,
                    'data' => $data
                ]);
                
                return response()->json([
                    'status' => false, 
                    'message' => 'Erro ao processar pagamento. Tente novamente.'
                ]);
            }
            // =================================
            
            $status = $response['charges'][0]['status'];

            $setting = Setting::first();
            $pm = Pagarme::first();
            $vezes = $data['installments'] . 'x';

            if ($data['installments'] == 1) {
                $taxa_reserva = 0;
                $amount = $data['items'][0]['value'] / 100;
                $taxa_percent = $setting->card_taxa_percent;
                $taxa_fixed = $setting->card_taxa_fixed;
                $taxa_cash_in = $taxa_fixed + (float) $amount * $taxa_percent / 100;
                $deposito_liquido = $amount - $taxa_fixed;
                $deposito_liquido -= (float) $amount * $taxa_percent / 100;
                $descricao = $status == 'paid' ? "" : ($response['charges'][0]['last_transaction']['acquirer_message'] ?? 'Recusado');
            } else {
                $taxa_reserva = 0;
                $amount = $data['items'][0]['value'] / 100;
                $taxa_percent = $pm->$vezes;
                $taxa = (float) $amount * $taxa_percent / 100;
                $deposito_liquido = $amount - $taxa;
                $taxa_cash_in = $taxa;
                $descricao = $status == 'paid' ? "" : ($response['charges'][0]['last_transaction']['acquirer_message'] ?? 'Recusado');
            }

            $ip = $request->header('X-Forwarded-For') ?
                $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                    $request->header('CF-Connecting-IP') :
                    $request->ip());

            $idTransaction = $data['pedido_uuid'];
            $cashin = [
                "method" => "card",
                "external_id" => $response['id'],
                "amount" => $amount,
                "client_name" => $data['payment']['credit_card']['customer']['name'],
                "client_cpf" => $data['payment']['credit_card']['customer']['cpf'],
                "client_email" => $data['payment']['credit_card']['customer']['email'],
                "client_telefone" => $data['payment']['credit_card']['customer']['phone_number'], // ← ADICIONADO
                "status" => $status == 'paid' ? "revisao" : 'cancelado',
                "idTransaction" => $idTransaction,
                "cash_in_liquido" => $deposito_liquido,
                "taxa_reserva" => $taxa_reserva,
                "qrcode_pix" => "N/A",
                "paymentcode" => "N/A",
                "paymentCodeBase64" => "N/A",
                "adquirente_ref" => 'pagar.me',
                "taxa_cash_in" => $taxa_cash_in,
                "executor_ordem" => 'pagar.me',
                "request_ip" => $ip,
                "request_domain" => $request->httpHost(),
                "type" => 'cash',
                "plataforma" => 'web',
                "user_id" => $request->user->id,
                "descricao_transacao" => $descricao,
                "callbackUrl" => 'checkout',
            ];

            TransactionIn::create($cashin);

            if ($status == 'paid') {
                return response()->json(['status' => true, 'uuid' => $idTransaction]);
            } else {
                return response()->json([
                    'status' => false, 
                    'uuid' => $idTransaction, 
                    'message' => $descricao ?: 'Pagamento recusado pela operadora de cartão. Tente um outro cartão.'
                ]);
            }
        }
    }

    public static function requestPaymentBilletPagarme($request, $data)
    {
        if (self::generateCredentialPagarme()) {
            
            $pagarme = new PagarmeService(self::$secret);
            $response = $pagarme->paymentBillet(
                $data['items'][0]['value'],
                $data['description'],
                $data['customer']
            );
            
            $status = $response['status'] ?? 'error';
            $amount = $data['items'][0]['value'] / 100;
            
            if ($status == 'pending') {
                $setting = Setting::first();

                $taxa_reserva = 0;
                $taxa_percent = $setting->billet_taxa_percent;
                $taxa_fixed = $setting->billet_taxa_fixed;
                $taxa_cash_in = $taxa_fixed + (float) $amount * $taxa_percent / 100;
                $deposito_liquido = $amount - $taxa_fixed;
                $deposito_liquido -= (float) $amount * $taxa_percent / 100;
                $descricao = "";

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());

                $idTransaction = Str::uuid()->toString();
                $cashin = [
                    "method" => "billet",
                    "external_id" => $response['id'],
                    "amount" => $amount,
                    "client_name" => $data['customer']['name'],
                    "client_cpf" => $data['customer']['cpf'],
                    "client_email" => $data['customer']['email'],
                    "client_telefone" => $data['payment']['banking_billet']['customer']['phone_number'], // ← ADICIONADO
                    "status" => "pendente",
                    "idTransaction" => $idTransaction,
                    "cash_in_liquido" => $deposito_liquido,
                    "taxa_reserva" => $taxa_reserva,
                    "qrcode_pix" => "N/A",
                    "paymentcode" => "N/A",
                    "paymentCodeBase64" => "N/A",
                    "adquirente_ref" => 'pagar.me',
                    "taxa_cash_in" => $taxa_cash_in,
                    "executor_ordem" => 'pagar.me',
                    "request_ip" => $ip,
                    "request_domain" => $request->httpHost(),
                    "type" => 'cash',
                    "plataforma" => 'web',
                    "user_id" => $request->user->id,
                    "descricao_transacao" => $descricao,
                    "callbackUrl" => 'checkout',
                ];

                TransactionIn::create($cashin);

                return response()->json([
                    'status' => true,
                    'barcode' => $response['charges'][0]['last_transaction']['line'],
                    'download' => $response['charges'][0]['last_transaction']['pdf'],
                    'uuid' => $idTransaction
                ]);
            } else {
                $message = 'Houve um erro. Tente novamente mais tarde.';
                return response()->json(['status' => false, 'message' => $message]);
            }
        }
    }

    public static function consultaTransactionPagarme($token, $metodo)
    {
        if (self::generateCredentialPagarme()) {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$access_token,
                'Content-Type' => 'application/json',
                'cert' => [self::$cert, ''],
                'verify' => false
            ])->get(self::$baseUrl . "/v1/notification/{$token}");

            if ($response->successful()) {

                $chargeNotification = $response->json();
                $i = count($chargeNotification["data"]);
                $ultimoStatus = $chargeNotification["data"][$i - 1];
                Log::debug("[+][EFITRAIT][CONSULTTRANSACTION] -> dados recebidos: " . json_encode($ultimoStatus));
                
                $status = $ultimoStatus["status"];
                $charge_id = $ultimoStatus["identifiers"]['charge_id'];

                $transaction = TransactionIn::where('external_id', $charge_id)->first();

                $statusAtual = $status["current"];
                switch ($statusAtual) {
                    case 'paid':
                        $processa = new ProcessaCallback();
                        $processa->deposit($charge_id, 'pago', $transaction->idTransaction, 'EFI');
                        break;
                    case 'upaid':
                    case 'canceled':
                        $processa = new ProcessaCallback();
                        $processa->deposit($charge_id, 'cancelado', $transaction->idTransaction, 'EFI');
                        break;
                    default:
                        break;
                }
            }
        }
    }
}