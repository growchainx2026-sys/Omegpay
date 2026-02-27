<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\TransactionOut;
use App\Models\User;
use App\Models\Transfeera;
use App\Helpers\Helper;
use App\Models\Setting;
use App\Models\TransactionIn;
use Illuminate\Http\Request;

trait TransfeeraTrait
{
    protected static string $urlCashIn;
    protected static string $urlCashOut;
    protected static string $userAgent;
    protected static string $accessToken;
    protected static string $tenantKeyPix;

    protected static function generateCredentialsTransfeera()
    {
       
        $setting = Transfeera::first();
        if(!$setting){
            return false;
        }

        self::$urlCashIn = $setting->url_cash_in;
        self::$urlCashOut = $setting->url;
        self::$userAgent = $setting->tenant_name." (".$setting->tenant_email.")";
        self::$tenantKeyPix = $setting->tenant_keypix;

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
        ->post('https://login-api.transfeera.com/authorization',[
            "grant_type" => "client_credentials",
            "client_id"  =>  $setting->token,
            "client_secret" =>  $setting->secret
        ]);
        
        if($response->successful()){
            $responseData = $response->json();
            self::$accessToken = $responseData['access_token'];
            return true;
        } else {
            Log::debug("[Tranfeera] Erro de Autenticação: ".json_encode($response->json())); 
            //dd($response->json());
            return false;
        }


    }

    public static function requestDepositTransfeera($data)
    {
        if(self::generateCredentialsTransfeera()){

            $txid = uniqid().uniqid();
            $info = uniqid();

            $document = Helper::generateValidCpf();

            $payload = [
                "txid" => $txid,
                "integration_id" => $info,
                "pix_key" => self::$tenantKeyPix,
                "original_value" => floatval($data->amount),
                "payer_question" => "Pagamento ".env('APP_NAME'),
                "payer" => [
                  "name" => $data->debtor_name,
                  "document" => $document
                ],
                "reject_unknown_payer" => false,
            ];

           // dd($payload);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post(self::$urlCashIn, $payload);
            //dd($response->json());
            if($response->successful()){
                
                $responseData = $response->json();

                $user = $data->user;
                $setting = Setting::first();
                $taxafixa = $user->use_taxas_individual == 1 ? (float) $user->taxa_cash_in_fixa : (float) $setting->taxa_cash_in_fixa;
                $tx_reserva = $user->use_taxas_individual == 1 ? (float) $user->taxa_reserva : (float) $setting->taxa_reserva;;

                $tx_cash_in = $user->use_taxas_individual == 1 ? $user->taxa_cash_in : $setting->taxa_cash_in;
                $taxatotal = ((float)$data->amount * (float) $tx_cash_in / 100);
                $deposito_liquido = (float)$data->amount - $taxatotal;
                $taxa_cash_in = $taxatotal;
                $descricao = "PORCENTAGEM";

                if ((float)$taxatotal < (float)$setting->baseline) {
                    $deposito_liquido = (float)$data->amount - (float)$setting->baseline;
                    $taxa_cash_in = (float)$setting->baseline;
                    $descricao = "FIXA";
                }

                $taxa_reserva = 0;
                if ((float) $tx_reserva > 0) {
                    $taxa_reserva += ((float)$data->amount * (float) $tx_reserva / 100);
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
                    "external_id"                   => $txid,
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_cpf"                    => $document,
                    "client_email"                  => $data->email,
                    "status"                        => "pendente",
                    "idTransaction"                 => $txid,
                    "cash_in_liquido"               => $deposito_liquido,
                    "taxa_reserva"                  => $taxa_reserva,
                    "qrcode_pix"                    => $responseData['emv_payload'],
                    "paymentcode"                   => $responseData['emv_payload'],
                    "paymentCodeBase64"             => $responseData['emv_payload'],
                    "adquirente_ref"                => 'transfeera',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "executor_ordem"                => 'transfeera',
                    "request_ip"                    => $ip,
                    "request_domain"                => $data->httpHost(),
                    "type"                          => 'cash',
                    "plataforma"                    => 'api',
                    "user_id"                       => $data->user->id,
                    "descricao_transacao"           => $descricao,
                    "callbackUrl"                   => $data->postback,
                ];

                TransactionIn::create($cashin);
                               
                return [
                    "data" => [
                        "idTransaction"=> $txid,
                        "qrcode"=> $responseData['emv_payload'],
                        "qr_code_image_url"=> "data:image/png;base64,".$responseData['image_base64']
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

    public static function requestPaymentTransfeera($request)
    {
        $user = User::where('id', $request->user->id)->first();

        $setting = Setting::first();
        $taxafixa = $user->use_taxas_individual == 1 ? $user->taxa_cash_out_fixa : $setting->taxa_cash_out_fixa;
        $tx_cash_out = $user->use_taxas_individual == 1 ? $user->taxa_cash_out : $setting->taxa_cash_out;

        $taxatotal = ((float)$request->amount * (float)$tx_cash_out / 100);
        $cashout_liquido = (float)$request->amount - $taxatotal;
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

        if($request->type === 'WEB'){
           return self::generateTransactionPaymentManualTransfeera($request, $taxa_cash_out, $cashout_liquido, $real_data, $descricao, $user);
        }

        if(self::generateCredentialsTransfeera()){

            $keytype = $request->pixKey;
            if(isset($request->pixKey)){
                $keytype = $request->pixKeyType;
            } else {
                $keytype = Helper::verifyPixType($request->pixKey);
            }
            
            function pixKeyType($type)
            {
                switch ($type) {
                  	case'cpf':
                        return "CPF";
                    case'cnpj':
                        return "CNPJ";
                    case'email':
                        return "EMAIL";
                    case'telefone':
                        return "TELEFONE";
                    case'aleatoria':
                        return "CHAVE_ALEATORIA";
                }
            }
           
            $txid = uniqid().uniqid();
            $appName = env("APP_NAME");
            $payloadLote = [
                "type"          => "TRANSFERENCIA",
                "auto_close"    => false,
                "name"          => "",
                "transfers"     => [
                    [
                        "value"             => floatval($request->amount),
                        "integration_id"    => $txid,
                        "idempotency_key"   => "id-".$txid,
                        "pix_description"   => "Recebimento $appName",
                        "destination_bank_account" => [
                        "pix_key_type"    => pixKeyType($keytype),
                        "pix_key"         => $request->pixKey
                        ],
                            "pix_key_validation" => [
                            "cpf_cnpj" => $request->cpf
                        ]
                    ]
                ]
            ];
            
            $responselote = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post('https://api.transfeera.com/batch', $payloadLote);

            $id = $responselote->json()['id'];
            $url = self::$urlCashOut."/batch/$id/close";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post($url, []);
           
            Log::debug("[Tranfeera] Resposta solicitacao saque Body: ".json_encode($response->json())); 
            if($response->successful()){
                Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                Helper::decrementAmount($user, $cashout_liquido, 'saldo');

              	$pixKey = $request->keypix;

                switch ($keytype) {
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
                $name = "Cliente de " . explode(' ', $request->user->name)[0] . ' ' . explode(' ', $request->user->name)[1];
                
                $pixcashout = [
                    "user_id"               => $request->user->id,
                    "external_id"           => "id-".$txid,
                    "amount"                => $request->amount,
                    "recebedor_name"        => $name,
                    "recebedor_cpf"         => $pixKey,
                    "pixKey"                => $pixKey,
                    "pixKeyType"            => strtolower($request->pixKeyType),
                    "status"                => "pendente",
                    "type"                  => "cash",
                    "idTransaction"         => "id-".$txid,
                    "end2end"               => "id-".$txid,
                    "taxa_cash_out"         => $taxa_cash_out,
                    "taxa_fixa"             => 0,
                    "plataforma"            => 'api',
                    "cash_out_liquido"      => $cashout_liquido,
                    "request_ip"            => $ip,
                    "request_domain"        => $request->httpHost(),
                    "end_to_end"            => "id-".$txid,
                    "callbackUrl"           => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao,
                    "adquirente_ref"        => "transfeera"
                ];
                

                $cashout = TransactionOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id"     => "id-".$txid,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => $responseData["PendingProcessing"] ?? "PendingProcessing",
                        "createdAt"         => $responseData['createdAt'] ?? $date,
                        "updatedAt"         => $responseData['updatedAt'] ?? $date
                    ]
                ];
            } else {
                return [
                    "status" => 400,
                    "data" => [
                        "status" => "error",
                        "message" => "Erro ao processar o saque... Tente novamente mais tarde."
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

    protected static function generateTransactionPaymentManualTransfeera($request, $taxa_cash_out, $cashout_liquido, $real_data, $descricao, $user)
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
            "user_id"               => $request->user->id,
            "external_id"           => $idTransaction,
            "amount"                => $request->amount,
            "recebedor_name"        => $name,
            "recebedor_cpf"         => $pixKey,
            "pixKey"                => $pixKey,
            "pixKeyType"            => strtolower($request->pixKeyType),
            "status"                => "pendente",
            "type"                  => "cash",
            "idTransaction"         => $internal_id,
            "end2end"               => $idTransaction,
            "taxa_cash_out"         => $taxa_cash_out,
            "taxa_fixa"             => 0,
            "plataforma"            => 'web',
            "cash_out_liquido"      => $cashout_liquido,
            "request_ip"            => $ip,
            "request_domain"        => $request->httpHost(),
            "end_to_end"            => $idTransaction,
            "callbackUrl"           => $request->baasPostbackUrl,
            "descricao_transacao"   => "WEB",
            "adquirente_ref"        => "cashtime"
        ];


        $cashout = TransactionOut::create($pixcashout);
        Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
        Helper::decrementAmount($user, $cashout_liquido, 'saldo');

        return [
            "status" => 200,
            "data" => [
                "idTransaction"     => $idTransaction,
                "status"            => "processing"
            ]
        ];
    }

    public static function liberarSaqueManualTransfeera($id)
    {
        if(self::generateCredentialsTransfeera()){
            $cashout = TransactionOut::where('id', $id)->first();
            
            $payloadLote = [
                "type"          => "TRANSFERENCIA",
                "auto_close"    => false,
                "name"          => "",
                "transfers"     => [
                    [
                        "value"             => floatval($cashout->amount),
                        "integration_id"    => $cashout->idTransaction,
                        "idempotency_key"   => $cashout->idTransaction,
                        "pix_description"   => "Recebimento ".env('APP_NAME'),
                        "destination_bank_account" => [
                        "pix_key_type"    => $cashout->pixKeyType,
                        "pix_key"         => $cashout->pixKey
                        ],
                            "pix_key_validation" => [
                            "cpf_cnpj" => $cashout->cpf
                        ]
                    ]
                ]
            ];
            
            $responselote = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post('https://api.transfeera.com/batch', $payloadLote);

            $id = $responselote->json()['id'];
            $url = self::$urlCashOut."/batch/$id/close";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post($url, []);
           
            Log::debug("[Tranfeera] Resposta solicitacao saque Body: ".json_encode($response->json()));
              
            if($response->successful()){
                $responseData = $response->json();                
                $pixcashout = [
                    "external_id"           => $responseData['id'],
                    "end2end"               => $responseData['pix_end2end_id'],
                    "descricao_transacao"   => "LIBERADOADMIN" 
                ];

                $cashout = TransactionOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }

        }
    }

    public static function registerWebhookTransfeera(Request $request)
    {
        if(self::generateCredentialsTransfeera()){
            $urlwebhook = env('APP_URL')."/transfeera/webhook";
            $payload = [
                "url"           => $urlwebhook,
                "object_types"  => [
                    "Transfer",
                    "TransferRefund",
                    "CashIn",
                    "ChargeReceivable"
                ]
            ];
            //dd($urlwebhook);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => "Bearer ".self::$accessToken,
                'User-Agent' => self::$userAgent
            ])->post("https://api.transfeera.com/webhook", $payload);
            //dd($response->json());
            if($response->successful()){
                return back()->with('success', "Webhooks atualizado com sucesso.");
            } else {
                if($response->json()['statusCode'] == 400){
                    return back()->with('error', "Webhooks já cadastrados.");
                }
                return back()->with('error', "Não foi possivel atualizar Webhooks");
            }
         } else {
            return back()->with('error', "Erro de autenticação");
        }
    }

}