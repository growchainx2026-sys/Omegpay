<?php

namespace App\Traits;

use App\DTO\WitetecDTO\CustomerDTO;
use App\DTO\WitetecDTO\DepositDTO;
use App\DTO\WitetecDTO\Enums\DepositMethod;
use App\DTO\WitetecDTO\Enums\PixKeyType;
use App\DTO\WitetecDTO\ItemDTO;
use App\Services\WitetecService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use App\Models\{User, Witetec, Setting, TransactionIn, TransactionOut};
use App\Helpers\Helper;
use App\DTO\ApiDepositDTO;

trait WitetecTrait
{
    protected static string $apiKey;
    protected static string $baseUrl;
    protected static string $txBilletFixed;
    protected static string $txBilletPercent;
    protected static string $txCardFixed;
    protected static string $txCardPercent;

    protected static function generateCredentialWitetec()
    {

        $setting = Witetec::first();
        if (!$setting) {
            return false;
        }

        self::$apiKey = $setting->api_token;
        self::$baseUrl = $setting->url;
        self::$txBilletFixed = $setting->tx_billet_fixed;
        self::$txBilletPercent = $setting->tx_billet_percent;
        self::$txCardFixed = $setting->tx_card_fixed;
        self::$txCardPercent = $setting->tx_card_percent;

        return true;
    }

    public static function requestDepositWitetec($request)
    {

        if (self::generateCredentialWitetec()) {

            /** @var ApiDepositDTO $data */
            $data = $request;

            if (Helper::validarCPF($data->debtor_document_number)) {
                $cpf = $data->debtor_document_number;
            } else {
                $cpf = Helper::generateValidCpf(false);
            }

            $customer = new CustomerDTO(
                $data->debtor_name,
                $data->email,
                $data->phone,
                "CPF",
                $cpf

            );

            $item = new ItemDTO(
                "Produto X",
                $data->amount * 100,
                1,
                false,
                uniqid("PROD_")
            );

            $deposit = new DepositDTO(
                $data->amount * 100,
                DepositMethod::PIX,
                $customer,
                [$item],
                null

            );
            dd($deposit);
            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->deposit($deposit);

            if ($response['status']) {

                $responseData = $response['data'];
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

                $ip = $request->header('X-Forwarded-For') ?
                    $request->header('X-Forwarded-For') : ($request->header('CF-Connecting-IP') ?
                        $request->header('CF-Connecting-IP') :
                        $request->ip());

                $cashin = [
                    "external_id"                   => $responseData['id'],
                    "amount"                        => $data->amount,
                    "client_name"                   => $data->debtor_name,
                    "client_cpf"                    => $data->debtor_document_number,
                    "client_email"                  => $data->email,
                    "status"                        => "pendente",
                    "idTransaction"                 => $responseData['id'],
                    "cash_in_liquido"               => $deposito_liquido,
                    "taxa_reserva"                  => $taxa_reserva,
                    "qrcode_pix"                    => $responseData['pix']['copyPaste'],
                    "paymentcode"                   => $responseData['pix']['copyPaste'],
                    "paymentCodeBase64"             => $responseData['pix']['copyPaste'],
                    "adquirente_ref"                => 'witetec',
                    "taxa_cash_in"                  => $taxa_cash_in,
                    "executor_ordem"                => 'witetec',
                    "request_ip"                    => $ip,
                    "request_domain"                => $request->httpHost(),
                    "type"                          => 'cash',
                    "plataforma"                    => 'api',
                    "user_id"                       => $data->user->id,
                    "descricao_transacao"           => $descricao,
                    "callbackUrl"                   => $data->postback,
                ];

                TransactionIn::create($cashin);

                return [
                    "data" => [
                        "idTransaction" => $responseData['id'],
                        "qrcode" => $responseData['pix']['copyPaste'],
                        "qr_code_image_url" => $responseData['pix']['qrcode']
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

    public static function requestPaymentWitetec($request)
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
            return self::paymentManualWitetec($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
        }

        if (self::generateCredentialWitetec()) {

            /** @var \App\DTO\WitetecDTO\withdrawDTO $payload */
            $payload->amount = $cashout_liquido * 100;
            $payload->pixKey = $request->pixKey;

            switch ($request->pixKeyType) {
                case 'email':
                    $payload->pixKeyType = PixKeyType::EMAIL;
                    break;
                case 'telefone':
                    $payload->pixKeyType = PixKeyType::PHONE;
                    break;
                case 'aleatoria':
                    $payload->pixKeyType = PixKeyType::EVP;
                    break;
                default:
                    $payload->pixKeyType = PixKeyType::CPF;
                    break;
            }

            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->withdraw($payload);


            if ($response['status']) {
                Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
                Helper::decrementAmount($user, $cashout_liquido, 'saldo');

                $name = "Cliente de " . $request->user->name;
                $responseData = $response['data'];

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
                    "adquirente_ref"        => "witetec"
                ];

                TransactionOut::create($pixcashout);

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
        } else {
            return [
                "status" => 200,
                "data" => [
                    "status" => "error"
                ]
            ];
        }
    }

    protected static function paymentManualWitetec($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
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

    public static function liberarSaqueManualWitetec($id)
    {
        if (self::generateCredentialWitetec()) {
            $cashout = TransactionOut::where('id', $id)->first();
            /** @var \App\DTO\WitetecDTO\withdrawDTO $payload */
            $payload->amount = $cashout->cashout_liquido * 100;
            $payload->pixKey = $cashout->pixKey;

            switch (strtolower($cashout->pixKeyType)) {
                case 'email':
                    $payload->pixKeyType = PixKeyType::EMAIL;
                    break;
                case 'telefone':
                    $payload->pixKeyType = PixKeyType::PHONE;
                    break;
                case 'aleatoria':
                    $payload->pixKeyType = PixKeyType::EVP;
                    break;
                default:
                    $payload->pixKeyType = PixKeyType::CPF;
                    break;
            }

            $api = new WitetecService(self::$baseUrl, self::$apiKey);
            $response = $api->withdraw($payload);

            if ($response['status']) {
                $responseData = $response['data'];
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

    public static function cadastrarWebhookWitetec()
    {
        self::generateCredentialWitetec();
        $api = new WitetecService(self::$baseUrl, self::$apiKey);
        $api->webhooks();
        return response()->json(['status' => true]);
    }
}
