<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{User, Setting, TransactionIn, TransactionOut};
use App\Helpers\Helper;
use App\Services\AppmaxService;

trait AppmaxTrait
{
    protected static string $uri;
    protected static string $accessToken;
    protected static string $customerId;

    protected static function generateCredentialsAppmax()
    {
        /* $setting = Appmax::first();
        if (!$setting) {
            return false;
        } */

        self::$uri = "https://homolog.sandboxappmax.com.br";
        self::$accessToken = "7B3DE46C-01D9617B-FF33779A-07028EEE";
        self::$customerId = 183454;
        return true;
    }

    protected static function createCustomer($data, $ip)
    {
        $fullname = explode(' ', $data->debtor_name);
        $firstname = $fullname[0];
        $lastname = $fullname[1] ?? "Silva";
        $email = $data->email;

        $payload = [
            "access-token" => self::$accessToken,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "telephone" => Helper::formatarTelefoneBR($data->phone),
            "ip" => $ip,
        ];
        //dd($payload);
        $endpoint = self::$uri . "/api/v3/customer";
        $response = Http::post($endpoint, $payload);

        dd($response->json());
        if ($response->successful()) {
            self::$customerId = $response->json()['customer_id'];
        } 
    }

    public static function requestDepositAppmax($data)
    {
        if (self::generateCredentialsAppmax()) {
            $document = Helper::generateValidCpf();


            $ip = $data->header('X-Forwarded-For') ?
                $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                    $data->header('CF-Connecting-IP') :
                    $data->ip());

            $appmax = new AppmaxService(self::$accessToken, $ip);
            $response = $appmax->criarPedido($data);

            dd($response);

            if ($response->successful()) {

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
                    "external_id"                   => $responseData['orderId'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_cpf"                    => $document,
                    "client_email"                  => $data->email,
                    "status"                        => "pendente",
                    "idTransaction"                 => $responseData['orderId'],
                    "cash_in_liquido"               => $deposito_liquido,
                    "taxa_reserva"                  => $taxa_reserva,
                    "qrcode_pix"                    => $responseData['pix']['payload'],
                    "paymentcode"                   => $responseData['pix']['payload'],
                    "paymentCodeBase64"             => $responseData['pix']['payload'],
                    "adquirente_ref"                => 'cashtime',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "executor_ordem"                => 'cashtime',
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
                        "idTransaction" => $responseData['orderId'],
                        "qrcode" => $responseData['pix']['payload'],
                        "qr_code_image_url" => $responseData['pix']['encodedImage']
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

    public static function requestPaymentAppmax($request)
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

        if ($request->baasPostbackUrl === 'web') {
            return self::generateTransactionPaymentManualAppmax($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialsAppmax()) {
            $callback = url("api/cashtime/callback/withdraw");
            $client_ip = $request->ip();

            $payload = [
                "amount"            => floatval($cashout_liquido * 100),
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "baasPostbackUrl"   => $callback
            ];


            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashOut, $payload);


            if ($response->successful()) {
                Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . explode(' ', $request->user->name)[0] . ' ' . explode(' ', $request->user->name)[1];
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
                    "user_id"               => $request->user->id,
                    "external_id"           => $responseData['id'],
                    "amount"                => $request->amount,
                    "recebedor_name"        => $name,
                    "recebedor_cpf"         => $pixKey,
                    "pixKey"                => $pixKey,
                    "pixKeyType"            => strtolower($request->pixKeyType),
                    "status"                => "pendente",
                    "type"                  => "cash",
                    "idTransaction"         => $internal_id,
                    "end2end"               => $responseData['id'],
                    "taxa_cash_out"         => $taxa_cash_out,
                    "taxa_fixa"             => 0,
                    "plataforma"            => 'api',
                    "cash_out_liquido"      => $cashout_liquido,
                    "request_ip"            => $ip,
                    "request_domain"        => $request->httpHost(),
                    "end_to_end"            => $responseData['id'],
                    "callbackUrl"           => $request->baasPostbackUrl,
                    "descricao_transacao"   => $descricao,
                    "adquirente_ref"        => "cashtime"
                ];

                $cashout = TransactionOut::create($pixcashout);

                return [
                    "status" => 200,
                    "data" => [
                        "id"                => $internal_id,
                        "amount"            => $request->amount,
                        "pixKey"            => $request->pixKey,
                        "pixKeyType"        => $request->pixKeyType,
                        "withdrawStatusId"  => $responseData["PendingProcessing"] ?? "PendingProcessing",
                        "createdAt"         => $responseData['createdAt'] ?? $date,
                        "updatedAt"         => $responseData['updatedAt'] ?? $date
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

    protected static function generateTransactionPaymentManualAppmax($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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
            "plataforma"            => 'api',
            "cash_out_liquido"      => $cashout_liquido,
            "request_ip"            => $ip,
            "request_domain"        => $request->httpHost(),
            "end_to_end"            => $idTransaction,
            "callbackUrl"           => $request->baasPostbackUrl,
            "descricao_transacao"   => "WEB",
            "adquirente_ref"        => "cashtime"
        ];


        $cashout = TransactionOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id"                => $internal_id,
                "amount"            => $request->amount,
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "withdrawStatusId"  => "PendingProcessing",
                "createdAt"         => $date,
                "updatedAt"         => $date
            ]
        ];
    }

    public static function liberarSaqueManualAppmaxAppmax($id)
    {
        if (self::generateCredentialsAppmax()) {
            $cashout = TransactionOut::where('id', $id)->first();
            $callback = url("api/cashtime/callback/withdraw");

            $payload = [
                "amount"            => intval($cashout->cash_out_liquido * 100),
                "pixKey"            => $cashout->pixKey,
                "pixKeyType"        => $cashout->pixKeyType == 'aleatoria' ? 'random' : $cashout->pixKeyType,
                "baasPostbackUrl"   => $callback
            ];
            //dd(self::$urlCashOut, $payload);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-authorization-key' => self::$secret,
            ])->post(self::$urlCashOut, $payload);
            //dd($response->json());

            if ($response->successful()) {
                $responseData = $response->json();
                $pixcashout = [
                    "external_id"           => $responseData['id'],
                    "end2end"               => $responseData['id'],
                    "descricao_transacao"   => "LIBERADOADMIN"
                ];

                $cashout = TransactionOut::where('id', $id)->update($pixcashout);
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            } else {
                return back()->with('error', 'Houve um erro ao liberar saque.');
            }
        }
    }
}
