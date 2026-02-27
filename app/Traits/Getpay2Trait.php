<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{User, Getpay2, Setting, TransactionIn, TransactionOut};
use App\Helpers\Helper;

trait Getpay2Trait
{
    protected static function getGetpay2Credentials(): ?Getpay2
    {
        $getpay = Getpay2::first();
        if (!$getpay || !$getpay->client_id || !$getpay->client_secret) {
            return null;
        }
        return $getpay;
    }

    public static function requestDepositGetpay2($request)
    {
        $getpay = self::getGetpay2Credentials();
        if (!$getpay) {
            return [
                'data' => ['status' => 'error', 'message' => 'GetPay2 não configurado'],
                'status' => 401,
            ];
        }

        $data = $request;
        $document = preg_replace('/\D/', '', $data->debtor_document_number);
        $externalId = (string) Str::uuid();
        $urlBase = rtrim($getpay->url_base ?? 'https://api.getpay.one/api', '/');

        $payload = [
            'externalId' => $externalId,
            'amount' => (float) $data->amount,
            'document' => $document,
            'name' => $data->debtor_name,
            'expire' => 3600,
            'email' => $data->email ?? null,
            'description' => 'Depósito PIX - ' . $externalId,
        ];

        $url = $urlBase . '/v2/payin';
        $basicAuth = base64_encode($getpay->client_id . ':' . $getpay->client_secret);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $basicAuth,
        ])->post($url, array_filter($payload));

        Log::debug('[GETPAY2][DEPOSIT] Request: ' . $url . ' | Response: ' . $response->status() . ' ' . $response->body());

        if ($response->successful() || $response->status() === 202) {
            $responseData = $response->json();
            $user = $data->user;
            $setting = Setting::first();

            $taxafixa = $user->use_taxas_individual == 1 ? (float) $user->taxa_cash_in_fixa : (float) $setting->taxa_cash_in_fixa;
            $tx_reserva = $user->use_taxas_individual == 1 ? (float) $user->taxa_reserva : (float) $setting->taxa_reserva;

            $tx_cash_in = $user->use_taxas_individual == 1 ? $user->taxa_cash_in : $setting->taxa_cash_in;
            $taxatotal = ((float) $data->amount * (float) $tx_cash_in / 100);
            $deposito_liquido = (float) $data->amount - $taxatotal;
            $taxa_cash_in = $taxatotal;
            $descricao = 'PORCENTAGEM';

            if ($setting->baseline && (float) $taxatotal < (float) $setting->baseline) {
                $deposito_liquido = (float) $data->amount - (float) $setting->baseline;
                $taxa_cash_in = (float) $setting->baseline;
                $descricao = 'FIXA';
            }

            $taxa_reserva = 0;
            if ((float) $tx_reserva > 0) {
                $taxa_reserva += ((float) $data->amount * (float) $tx_reserva / 100);
                $deposito_liquido -= $taxa_reserva;
            }

            $deposito_liquido -= $taxafixa;
            $taxa_cash_in += $taxafixa;

            $idTransaction = $responseData['payment_id'] ?? $responseData['id'] ?? $externalId;
            $qrcode = $responseData['pix']['copyPaste'] ?? $responseData['copyPaste'] ?? $responseData['qrcode'] ?? $responseData['payload'] ?? $idTransaction;
            $qrImage = $responseData['pix']['encodedImage'] ?? $responseData['qr_code_image_url'] ?? $responseData['qrcode_image'] ?? '';

            $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

            $cashin = [
                'external_id' => $idTransaction,
                'amount' => $data->amount,
                'client_name' => $data->debtor_name,
                'client_cpf' => $document,
                'client_email' => $data->email ?? null,
                'status' => 'pendente',
                'idTransaction' => $idTransaction,
                'cash_in_liquido' => $deposito_liquido,
                'taxa_reserva' => $taxa_reserva,
                'qrcode_pix' => $qrcode,
                'paymentcode' => $qrcode,
                'paymentCodeBase64' => $qrcode,
                'adquirente_ref' => 'getpay2',
                'taxa_cash_in' => $taxa_cash_in,
                'executor_ordem' => 'getpay2',
                'request_ip' => $ip,
                'request_domain' => $request->httpHost(),
                'type' => 'cash',
                'plataforma' => 'api',
                'user_id' => $data->user->id,
                'descricao_transacao' => $descricao,
                'callbackUrl' => $data->postback ?? 'checkout',
            ];

            TransactionIn::create($cashin);

            return [
                'data' => [
                    'idTransaction' => $idTransaction,
                    'qrcode' => $qrcode,
                    'qr_code_image_url' => $qrImage ?: null,
                ],
                'status' => 200,
            ];
        }

        return [
            'data' => [
                'status' => 'error',
                'message' => $response->json('message') ?? $response->json('error') ?? 'Erro ao criar depósito GetPay2',
            ],
            'status' => $response->status() ?: 500,
        ];
    }

    public static function requestPaymentGetpay2($request)
    {
        $user = User::where('id', $request->user->id)->first();
        $setting = Setting::first();

        $taxafixa = $user->use_taxas_individual == 1 ? $user->taxa_cash_out_fixa : $setting->taxa_cash_out_fixa;
        $tx_cash_out = $user->use_taxas_individual == 1 ? $user->taxa_cash_out : $setting->taxa_cash_out;

        $taxatotal = ((float) $request->amount * (float) $tx_cash_out / 100);
        $cashout_liquido = (float) $request->amount - $taxatotal;
        $taxa_cash_out = $taxatotal;
        $descricao = 'PORCENTAGEM';

        $cashout_liquido -= $taxafixa;
        $taxa_cash_out += $taxafixa;

        if ($user->saldo < $cashout_liquido) {
            return [
                'data' => ['status' => 'error', 'message' => 'Saldo insuficiente.'],
                'status' => 401,
            ];
        }

        if ($request->baasPostbackUrl === 'web') {
            return self::generateTransactionPaymentManualGetpay2($request, $taxa_cash_out, $cashout_liquido, Carbon::now(), $descricao, $user);
        }

        $getpay = self::getGetpay2Credentials();
        if (!$getpay) {
            return [
                'data' => ['status' => 'error', 'message' => 'GetPay2 não configurado'],
                'status' => 401,
            ];
        }

        $pixKeyType = match (strtolower($request->pixKeyType ?? 'cpf')) {
            'email' => 'email',
            'telefone', 'phone' => 'phone',
            'aleatoria', 'random' => 'evp',
            default => 'cpf',
        };

        $pixKey = $request->pixKey;
        if (in_array($pixKeyType, ['cpf', 'cnpj', 'phone'])) {
            $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
        }

        $payload = [
            'amount' => (float) $cashout_liquido,
            'pixKey' => $pixKey,
            'pixKeyType' => $pixKeyType,
            'name' => $request->user->name ?? 'Cliente',
            'document' => preg_replace('/\D/', '', $request->user->cpf_cnpj ?? ''),
        ];

        $urlBase = rtrim($getpay->url_base ?? 'https://api.getpay.one/api', '/');
        $url = $urlBase . '/v2/payout';
        $basicAuth = base64_encode($getpay->client_id . ':' . $getpay->client_secret);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $basicAuth,
        ])->post($url, $payload);

        Log::debug('[GETPAY2][WITHDRAW] Request: ' . $url . ' | Response: ' . $response->status() . ' ' . $response->body());

        if ($response->successful() || $response->status() === 202) {
            Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            $responseData = $response->json();
            $externalId = $responseData['id'] ?? $responseData['transaction_id'] ?? (string) Str::uuid();
            $internalId = strtoupper(str_replace('-', '', (string) Str::uuid()));

            $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

            TransactionOut::create([
                'user_id' => $request->user->id,
                'external_id' => $externalId,
                'amount' => $request->amount,
                'recebedor_name' => $request->user->name ?? 'Cliente',
                'recebedor_cpf' => $pixKey,
                'pixKey' => $pixKey,
                'pixKeyType' => strtolower($request->pixKeyType ?? 'cpf'),
                'status' => 'pendente',
                'type' => 'cash',
                'idTransaction' => $internalId,
                'taxa_cash_out' => $taxa_cash_out,
                'taxa_fixa' => 0,
                'plataforma' => 'api',
                'cash_out_liquido' => $cashout_liquido,
                'request_ip' => $ip,
                'request_domain' => $request->httpHost(),
                'callbackUrl' => $request->baasPostbackUrl ?? null,
                'descricao_transacao' => $descricao,
                'adquirente_ref' => 'getpay2',
                'end2end' => $externalId,
                'end_to_end' => $externalId,
            ]);

            return [
                'status' => 200,
                'data' => [
                    'id' => $internalId,
                    'amount' => $request->amount,
                    'pixKey' => $request->pixKey,
                    'pixKeyType' => $request->pixKeyType,
                    'withdrawStatusId' => 'PendingProcessing',
                    'createdAt' => Carbon::now(),
                    'updatedAt' => Carbon::now(),
                ],
            ];
        }

        return [
            'status' => $response->status() ?: 500,
            'data' => [
                'status' => 'error',
                'message' => $response->json('message') ?? $response->json('error') ?? 'Erro ao criar saque GetPay2',
            ],
        ];
    }

    protected static function generateTransactionPaymentManualGetpay2($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user): array
    {
        $idTransaction = (string) Str::uuid();
        $internalId = strtoupper(str_replace('-', '', $idTransaction));
        $pixKey = preg_replace('/[^0-9]/', '', $request->pixKey);

        if (in_array(strtolower($request->pixKeyType ?? ''), ['cpf', 'cnpj', 'phone'])) {
            $pixKey = preg_replace('/[^0-9]/', '', $request->pixKey);
        }

        $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

        TransactionOut::create([
            'user_id' => $request->user->id,
            'external_id' => $idTransaction,
            'amount' => $request->amount,
            'recebedor_name' => $request->user->name ?? 'Cliente',
            'recebedor_cpf' => $pixKey,
            'pixKey' => $pixKey,
            'pixKeyType' => strtolower($request->pixKeyType ?? 'cpf'),
            'status' => 'pendente',
            'type' => 'cash',
            'idTransaction' => $internalId,
            'taxa_cash_out' => $taxa_cash_out,
            'taxa_fixa' => 0,
            'plataforma' => 'web',
            'cash_out_liquido' => $cashout_liquido,
            'request_ip' => $ip,
            'request_domain' => $request->httpHost(),
            'callbackUrl' => $request->baasPostbackUrl ?? null,
            'descricao_transacao' => 'WEB',
            'adquirente_ref' => 'getpay2',
            'end2end' => $idTransaction,
            'end_to_end' => $idTransaction,
        ]);

        return [
            'status' => 200,
            'data' => [
                'id' => $internalId,
                'amount' => $request->amount,
                'pixKey' => $request->pixKey,
                'pixKeyType' => $request->pixKeyType,
                'withdrawStatusId' => 'PendingProcessing',
                'createdAt' => $date,
                'updatedAt' => $date,
            ],
        ];
    }

    public static function liberarSaqueManualGetpay2($id)
    {
        $getpay = self::getGetpay2Credentials();
        if (!$getpay) {
            return back()->with('error', 'GetPay2 não configurado.');
        }

        $cashout = TransactionOut::where('id', $id)->first();
        if (!$cashout) {
            return back()->with('error', 'Saque não encontrado.');
        }

        $pixKeyType = match (strtolower($cashout->pixKeyType ?? 'cpf')) {
            'email' => 'email',
            'phone', 'telefone' => 'phone',
            'aleatoria', 'random', 'evp' => 'evp',
            default => 'cpf',
        };

        $payload = [
            'amount' => (float) $cashout->cash_out_liquido,
            'pixKey' => $cashout->pixKey,
            'pixKeyType' => $pixKeyType,
            'name' => $cashout->recebedor_name ?? 'Cliente',
            'document' => preg_replace('/\D/', '', $cashout->recebedor_cpf ?? ''),
        ];

        $urlBase = rtrim($getpay->url_base ?? 'https://api.getpay.one/api', '/');
        $url = $urlBase . '/v2/payout';
        $basicAuth = base64_encode($getpay->client_id . ':' . $getpay->client_secret);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $basicAuth,
        ])->post($url, $payload);

        if ($response->successful() || $response->status() === 202) {
            $responseData = $response->json();
            $externalId = $responseData['id'] ?? $responseData['transaction_id'] ?? $cashout->external_id;

            $cashout->update([
                'external_id' => $externalId,
                'end2end' => $externalId,
                'end_to_end' => $externalId,
                'descricao_transacao' => 'LIBERADOADMIN',
            ]);

            return back()->with('success', 'Pedido de saque enviado com sucesso!');
        }

        return back()->with('error', 'Erro ao liberar saque: ' . ($response->json('message') ?? $response->body()));
    }
}
