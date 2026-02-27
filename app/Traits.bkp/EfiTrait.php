<?php

namespace App\Traits;

use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{User, Efi, Setting, TransactionIn, TransactionOut};
use Faker\Factory as FakerFactory;
use App\Helpers\Helper;
use App\Services\ProcessaCallback;

trait EfiTrait
{
    protected static string $baseUrl;
    protected static string $access_token;
    protected static string $chave_pix;
    protected static string $client_id;
    protected static string $client_secret;
    protected static string $cert;
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $taxaCashIn;
    protected static string $taxaCashOut;
    protected static string $billetTxFixed;
    protected static string $billetTxPercent;
    protected static string $env;

    protected static function generateCredentialEfi($type = 'pix')
    {

        $setting = Efi::first();
        if (!$setting) {
            return false;
        }


        if ($type == 'pix' && env('EFI_ENV') == "sandbox") {
            self::$baseUrl = "https://pix-h.api.efipay.com.br";
        } elseif ($type == 'pix' && env('EFI_ENV') == "production") {
            self::$baseUrl = "https://pix.api.efipay.com.br";
        } elseif ($type == 'card' && env('EFI_CARD_ENV') == "sandbox") {
            self::$baseUrl = "https://cobrancas-h.api.efipay.com.br";
        } elseif ($type == 'card' && env('EFI_CARD_ENV') == "production") {
            self::$baseUrl = "https://cobrancas.api.efipay.com.br";
        } elseif ($type == 'billet' && env('EFI_BILLET_ENV') == "sandbox") {
            self::$baseUrl = "https://cobrancas-h.api.efipay.com.br";
        } elseif ($type == 'billet' && env('EFI_BILLET_ENV') == "production") {
            self::$baseUrl = "https://cobrancas.api.efipay.com.br";
        }

        self::$chave_pix = $setting->chave_pix ?? '';
        self::$client_id = $setting->client_id;
        self::$client_secret = $setting->client_secret;
        self::$cert = storage_path('app/private/certificados/producao.pem');
        self::$taxaCashIn = $setting->taxa_pix_cash_in;
        self::$taxaCashOut = $setting->taxa_pix_cash_out;

        if ($type == 'pix') {
            $endpoint = self::$baseUrl . '/oauth/token';
        } else {
            $endpoint = self::$baseUrl . '/v1/authorize';
        }

        $certPath = storage_path('app/private/certificados/producao.pem');

        // Verificação do certificado
        if (!file_exists($certPath)) {
            throw new \RuntimeException("Certificado não encontrado em: $certPath");
        }

        $payload = [
            "grant_type" => "client_credentials"
        ];
        $autorizacao = base64_encode(self::$client_id . ":" . self::$client_secret);
        // Fazer a requisição
        $response = Http::withOptions([
            'cert' => [$certPath, ''],
            'verify' => false // ← Adicionado aqui
        ])
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $autorizacao,
            ])
            ->post($endpoint, $payload);

        // Retornar a resposta formatada
        $res = $response->json();
        self::$access_token = $res['access_token'];
        return true;
    }

    public static function requestDepositEfi($data)
    {
        if (self::generateCredentialEfi()) {
            $client_ip = $data->ip();

            $productid = uniqid();
            $document = Helper::generateValidCpf();

            $cpfLimpo = preg_replace('/[^0-9]/', '', $document);

            $payload = [
                "calendario" => ["expiracao" => 3600],
                "devedor" => [
                    "cpf" => $cpfLimpo,
                    "nome" => $data->debtor_name
                ],
                "valor" => ["original" => number_format($data->amount, 2, '.', '')],
                "chave" => self::$chave_pix,
                "solicitacaoPagador" => "Cobrança dos serviços prestados."
            ];

            $response = Http::withOptions([
                'cert' => [self::$cert, ''],
                'verify' => false // ← Adicionado aqui
            ])
                ->withHeaders([
                    'authorization' => 'Bearer ' . self::$access_token,
                    'Content-Type' => 'application/json'
                ])
                ->post(self::$baseUrl . '/v2/cob', $payload);

            if ($response->successful()) {

                $responseData = $response->json();
                $user = $data->user;
                $setting = Setting::first();
                $taxafixa = (float) $user->taxa_cash_in_fixa;

                $tx_cash_in = (float) $user->taxa_cash_in;
                $taxatotal = (float) ((float) $data->amount * (float) $tx_cash_in / 100);
                $deposito_liquido = (float) $data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float) $taxatotal < (float) $setting->baseline) {
                    $deposito_liquido = (float) $data->amount - (float) $setting->baseline;
                    $taxa_cash_in = (float) $setting->baseline;
                    $descricao = "FIXA";
                }

                $taxa_reserva = 0;


                $deposito_liquido -= $taxafixa;
                $taxa_cash_in += $taxafixa;

                $ip = $data->header('X-Forwarded-For') ?
                    $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                        $data->header('CF-Connecting-IP') :
                        $data->ip());

                $cashin = [
                    "external_id" => $responseData['txid'],
                    "amount" => $data->amount,
                    "client_name" => $data->debtor_name,
                    "client_cpf" => $document,
                    "client_email" => $data->email,
                    "status" => "pendente",
                    "idTransaction" => $responseData['txid'],
                    "cash_in_liquido" => $deposito_liquido,
                    "taxa_reserva" => $taxa_reserva,
                    "qrcode_pix" => $responseData['pixCopiaECola'],
                    "paymentcode" => $responseData['pixCopiaECola'],
                    "paymentCodeBase64" => $responseData['pixCopiaECola'],
                    "adquirente_ref" => 'Efí',
                    "taxa_cash_in" => $taxa_cash_in,
                    "executor_ordem" => 'Efí',
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
                        "idTransaction" => $responseData['txid'],
                        "qrcode" => $responseData['pixCopiaECola'],
                        "qr_code_image_url" => 'https://quickchart.io/qr?text=' . $responseData['pixCopiaECola']
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

    public static function requestPaymentEfi($request)
    {

        $user = User::where('id', $request->user->id)->first();

        $setting = Setting::first();
        $taxafixa = $user->taxa_cash_out_fixa;
        $tx_cash_out = $user->taxa_cash_out;

        $taxatotal = (float) ($request->amount * $tx_cash_out / 100);
        $cashout_liquido = (float) $request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = "PORCENTAGEM";

        $cashout_liquido = $cashout_liquido - $taxafixa;
        $taxa_cash_out = $taxa_cash_out + $taxafixa;

        if ($user->saldo < $cashout_liquido) {
            return response()->json([
                'status' => 'error',
                'message' => "Saldo insuficiente.",
            ], 401);
        }

        $date = Carbon::now();

        if ($request->baasPostbackUrl === 'web') {
            return self::generateTransactionPaymentManualEfi($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialEfi()) {
            $callback = url("api/efi/callback/withdraw");
            $client_ip = $request->ip();

            $payload = [
                "valor" => number_format($cashout_liquido, '2', '.', ','),
                "pagador" => [
                    "chave" => self::$chave_pix,
                    "infoPagador" => "Segue o pagamento da conta"
                ],
                "favorecido" => [
                    "chave" => $request->pixKey
                ]
            ];

            $internal_id = str_replace('-', '', Str::uuid()->toString());

            $response = Http::withOptions([
                'cert' => [self::$cert, ''],
                'verify' => true // ← Adicionado aqui
            ])
                ->withHeaders([
                    'authorization' => 'Bearer ' . self::$access_token,
                    'Content-Type' => 'application/json'
                ])
                ->put(self::$baseUrl . '/v3/gn/pix/' . $internal_id, $payload);
            if ($response->successful()) {
                Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . $request->user->name;
                $responseData = $response->json();

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

                $internal_id = str_replace('-', '', (string) Str::uuid());
                $internal_id = strtoupper($internal_id);

                $pixcashout = [
                    "user_id" => $request->user->id,
                    "external_id" => $responseData['idEnvio'],
                    "amount" => $request->amount,
                    "recebedor_name" => $name,
                    "recebedor_cpf" => $pixKey,
                    "pixKey" => $pixKey,
                    "pixKeyType" => strtolower($request->pixKeyType),
                    "status" => "pendente",
                    "type" => "cash",
                    "idTransaction" => $internal_id,
                    "end2end" => $responseData['idEnvio'],
                    "taxa_cash_out" => $taxa_cash_out,
                    "taxa_fixa" => 0,
                    "plataforma" => 'api',
                    "cash_out_liquido" => $cashout_liquido,
                    "request_ip" => $ip,
                    "request_domain" => $request->httpHost(),
                    "end_to_end" => $responseData['idEnvio'],
                    "callbackUrl" => $request->baasPostbackUrl,
                    "descricao_transacao" => $descricao,
                    "adquirente_ref" => "Efí"
                ];

                $cashout = TransactionOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id" => $internal_id,
                        "amount" => $request->amount,
                        "pixKey" => $request->pixKey,
                        "pixKeyType" => $request->pixKeyType,
                        "withdrawStatusId" => $responseData["PendingProcessing"] ?? "PendingProcessing",
                        "createdAt" => $responseData['createdAt'] ?? $date,
                        "updatedAt" => $responseData['updatedAt'] ?? $date
                    ]
                ];
            }
        } else {
            return [
                "status" => 200,
                "data" => [
                    "status" => "error"
                ]
            ];
        }
    }

    protected static function generateTransactionPaymentManualEfi($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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
            "pixKeyType" => strtolower($request->pixKeyType),
            "status" => "pendente",
            "type" => "cash",
            "idTransaction" => $internal_id,
            "end2end" => $idTransaction,
            "taxa_cash_out" => $taxa_cash_out,
            "taxa_fixa" => 0,
            "plataforma" => 'web',
            "cash_out_liquido" => $cashout_liquido,
            "request_ip" => $ip,
            "request_domain" => $request->httpHost(),
            "end_to_end" => $idTransaction,
            "callbackUrl" => $request->baasPostbackUrl,
            "descricao_transacao" => "WEB",
            "adquirente_ref" => "efi"
        ];


        $cashout = TransactionOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id" => $internal_id,
                "amount" => $request->amount,
                "pixKey" => $request->pixKey,
                "pixKeyType" => $request->pixKeyType,
                "withdrawStatusId" => "PendingProcessing",
                "createdAt" => $date,
                "updatedAt" => $date
            ]
        ];
    }

    public static function liberarSaqueManualEfi($id)
    {
        if (self::generateCredentialEfi()) {
            $cashout = TransactionOut::where('id', $id)->first();
            $callback = url("api/efi/callback/withdraw");

            $payload = [
                "valor" => $cashout->cash_out_liquido,
                "pagador" => [
                    "chave" => self::$chave_pix,
                    "infoPagador" => "Segue o pagamento da conta"
                ],
                "favorecido" => [
                    "chave" => $cashout->pixKey
                ]
            ];
            
            //dd($payload);
            $internal_id = str_replace('-', '', Str::uuid()->toString());

            // Fazer a requisição
            $response = Http::withOptions([
                'cert' => [self::$cert, ''],
                'verify' => true // ← Adicionado aqui
            ])
                ->withHeaders([
                    'authorization' => 'Bearer ' . self::$access_token,
                    'Content-Type' => 'application/json'
                ])
                ->put(self::$baseUrl . '/v3/gn/pix/' . $internal_id, $payload);
            Log::debug('RESPOSTA DO SAQUE DA EFI: ' . json_encode($response->json()));
            //dd($response->json());
            if ($response->successful()) {
                $responseData = $response->json();
                $pixcashout = [
                    "external_id" => $responseData['idEnvio'],
                    "end2end" => $responseData['e2eId'],
                    "descricao_transacao" => "LIBERADOADMIN"
                ];

                $cashout = TransactionOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }
        } else {
            return back()->with('error', 'Houve um erro ao liberar saque.');
        }
    }

    public static function cadastrarWebhook()
    {
        if (self::generateCredentialEfi()) {

            $access_token = self::$access_token;
            $url = env('APP_URL') . '/api/efi/callback?ignorar=';
            $chave = self::$chave_pix;

            $certPath = storage_path('app/private/certificados/producao.pem');

            $payload = [
                "webhookUrl" => $url,
            ];

            // Fazer a requisição
            $response = Http::withOptions([
                'cert' => [$certPath, ''],
                'verify' => false // ← Adicionado aqui
            ])
                ->withHeaders([
                    'authorization' => 'Bearer ' . $access_token,
                    'x-skip-mtls-checking' => "true",
                    'Content-Type' => 'application/json'
                ])
                ->put(self::$baseUrl . '/v2/webhook/' . $chave, $payload);

            // Retornar a resposta formatada
            $res = $response->json();
            //dd($res);
            return $res;
        }
    }

    public static function requestPaymentCard($request, $data, $user)
    {
        //dd($request->user);
        if (self::generateCredentialEfi('card')) {
            //dd($data);

            $idTransaction = $data['pedido_uuid'];
            unset($data['pedido_uuid']);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$access_token,
                'Content-Type' => 'application/json',
                'cert' => [self::$cert, ''],
                'verify' => false // ← Adicionado aqui
            ])
                ->post(self::$baseUrl . '/v1/charge/one-step', $data);


            if ($response->successful()) {

                $responseData = $response->json()['data'];
                /* if(isset($responseData['refusal']['retry']) && $responseData['refusal']['retry'] == true){

                } */
                $efi = Efi::first();

                $taxa_reserva = 0;
                $amount = $data['items'][0]['value'] / 100;
                $taxa_percent = $efi->card_tx_percent;
                $taxa_fixed = $efi->card_tx_fixed;
                $taxa_cash_in = $taxa_fixed + (float) $amount * $taxa_percent / 100;
                $deposito_liquido = $amount - $taxa_fixed;
                $deposito_liquido -= (float) $amount * $taxa_percent / 100;


                $setting = Setting::first();
                $dias_recebimento = $setting->card_days_to_release;

                switch ($user->plan_card) {
                    case 'opt1':
                        $dias_recebimento = $setting->card_days_to_anticipation_opt1;
                        $deposito_liquido -= $amount * $setting->card_tx_to_anticipation_opt1 / 100;
                        $taxa_cash_in += $amount * $setting->card_tx_to_anticipation_opt1 / 100;
                        break;
                    case 'opt2':
                        $dias_recebimento = $setting->card_days_to_anticipation_opt2;
                        $deposito_liquido -= $amount * $setting->card_tx_to_anticipation_opt2 / 100;
                        $taxa_cash_in += $amount * $setting->card_tx_to_anticipation_opt2 / 100;
                        break;
                    default:
                        break;
                }
                $descricao = $responseData['status'] == 'approved' ? "" : $responseData['refusal']['reason'];

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());


                $cashin = [
                    "method" => "card",
                    "external_id" => $responseData['charge_id'],
                    "amount" => $amount,
                    "client_name" => $data['payment']['credit_card']['customer']['name'],
                    "client_cpf" => $data['payment']['credit_card']['customer']['cpf'],
                    "client_email" => $data['payment']['credit_card']['customer']['email'],
                    "status" => $responseData['status'] == 'approved' ? "pendente" : 'cancelado',
                    "idTransaction" => $data['metadata']['custom_id'],
                    "cash_in_liquido" => $deposito_liquido,
                    "taxa_reserva" => $taxa_reserva,
                    "qrcode_pix" => "N/A",
                    "paymentcode" => "N/A",
                    "paymentCodeBase64" => "N/A",
                    "adquirente_ref" => 'Efí',
                    "taxa_cash_in" => $taxa_cash_in,
                    "executor_ordem" => 'Efí',
                    "request_ip" => $ip,
                    "request_domain" => $request->httpHost(),
                    "type" => 'cash',
                    "plataforma" => 'web',
                    "user_id" => $request->user->id,
                    "dias_recebimento" => $dias_recebimento,
                    "descricao_transacao" => $descricao,
                    "callbackUrl" => 'checkout',
                ];

                TransactionIn::create($cashin);

                if ($responseData['status'] == 'approved') {
                    return response()->json(['status' => true, 'uuid' => $data['metadata']['custom_id']]);
                } else {
                    return response()->json(['status' => false, 'uuid' => $data['metadata']['custom_id'], 'message' => 'Pagamento recusado pela operadora de cartão. Tente um outro cartão.']);
                }
            } else {
                $responseData = $response->json();

                $message = 'Houve um erro. Tente novamente mais tarde.';
                if (isset($responseData['error_description']) && str_contains($responseData['error_description'], 'idênticas')) {
                    $message = 'Varias tentativas foram realizada com este cartão. Utilize um novo cartão para continuar...';
                }
                return response()->json(['status' => false, 'message' => $message]);
            }
        }
    }

    public static function requestPaymentBillet($request, $data)
    {

        if (self::generateCredentialEfi('card')) {
            //dd($data);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$access_token,
                'Content-Type' => 'application/json',
                'cert' => [self::$cert, ''],
                'verify' => false // ← Adicionado aqui
            ])
                ->post(self::$baseUrl . '/v1/charge/one-step', $data);


            if ($response->successful()) {

                $responseData = $response->json()['data'];
                //dd($responseData);
                /* if(isset($responseData['refusal']['retry']) && $responseData['refusal']['retry'] == true){

                } */
                $efi = Efi::first();

                $taxa_reserva = 0;
                $amount = $responseData['total'] / 100;
                $taxa_percent = $efi->billet_tx_percent;
                $taxa_fixed = $efi->billet_tx_fixed;
                $taxa_cash_in = $taxa_fixed + (float) $amount * $taxa_percent / 100;
                $deposito_liquido = $amount - $taxa_fixed;
                $deposito_liquido -= (float) $amount * $taxa_percent / 100;
                $descricao = "";

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());


                $cashin = [
                    "method" => "billet",
                    "external_id" => $responseData['charge_id'],
                    "amount" => $amount,
                    "client_name" => $data['payment']['banking_billet']['customer']['name'],
                    "client_cpf" => $data['payment']['banking_billet']['customer']['cpf'],
                    "client_email" => $data['payment']['banking_billet']['customer']['email'],
                    "status" => "pendente",
                    "idTransaction" => $data['metadata']['custom_id'],
                    "cash_in_liquido" => $deposito_liquido,
                    "taxa_reserva" => $taxa_reserva,
                    "qrcode_pix" => "N/A",
                    "paymentcode" => "N/A",
                    "paymentCodeBase64" => "N/A",
                    "adquirente_ref" => 'Efí',
                    "taxa_cash_in" => $taxa_cash_in,
                    "executor_ordem" => 'Efí',
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
                    'barcode' => $responseData['barcode'],
                    'download' => $responseData['pdf']['charge'],
                    'uuid' => $data['metadata']['custom_id']
                ]);
            } else {
                $responseData = $response->json();

                $message = 'Houve um erro. Tente novamente mais tarde.';
                if (isset($responseData['error_description']) && str_contains($responseData['error_description'], 'idênticas')) {
                    $message = 'Varias tentativas foram realizada com este cartão. Utilize um novo cartão para continuar...';
                }
                return response()->json(['status' => false, 'message' => $message]);
            }
        }
    }

    public static function consultaTransaction($token, $metodo)
    {
        if (self::generateCredentialEfi('card')) {
            //dd($data);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . self::$access_token,
                'Content-Type' => 'application/json',
                'cert' => [self::$cert, ''],
                'verify' => false // ← Adicionado aqui
            ])
                ->get(self::$baseUrl . "/v1/notification/{$token}");

            //dd()
            if ($response->successful()) {

                $chargeNotification = $response->json();
                $i = count($chargeNotification["data"]);
                // Pega o último Object chargeStatus
                $ultimoStatus = $chargeNotification["data"][$i - 1];
                Log::debug("[+][EFITRAIT][CONSULTTRANSACTION] -> dados recebidos: " . json_encode($ultimoStatus));
                // Acessando o array Status
                $status = $ultimoStatus["status"];
                // Obtendo o ID da transação    
                $charge_id = $ultimoStatus["identifiers"]['charge_id'];

                $transaction = TransactionIn::where('external_id', $charge_id)->first();

                // Obtendo a String do status atual
                $statusAtual = $status["current"]; //unpaid | paid | canceled
                switch ($statusAtual) {
                    case 'paid':
                        $processa = new ProcessaCallback();
                        $processa->deposit($charge_id, 'revisao', $transaction->idTransaction, 'EFI');
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
