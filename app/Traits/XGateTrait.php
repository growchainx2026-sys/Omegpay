<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\{User, XGate, Setting, TransactionIn, TransactionOut};
use App\Helpers\Helper;

trait XGateTrait
{
    protected static function getXGateBaseUrl(): string
    {
        return 'https://api.xgateglobal.com';
    }

    protected static function getXGateCredentials(): ?XGate
    {
        $xgate = XGate::first();
        if (!$xgate || !$xgate->email || !$xgate->password) {
            return null;
        }
        return $xgate;
    }

    protected static function getXGateToken(XGate $xgate): ?string
    {
        $response = Http::post(self::getXGateBaseUrl() . '/auth/token', [
            'email' => $xgate->email,
            'password' => $xgate->password,
        ]);

        if (!$response->successful()) {
            Log::error('[XGATE][AUTH] Falha ao obter token: ' . $response->body());
            return null;
        }

        return $response->json('token');
    }

    protected static function getXGateBrlCurrency(string $token): ?array
    {
        $response = Http::withToken($token)
            ->get(self::getXGateBaseUrl() . '/deposit/company/currencies');

        if (!$response->successful()) {
            $response2 = Http::withToken($token)
                ->get(self::getXGateBaseUrl() . '/withdraw/company/currencies');
            if ($response2->successful()) {
                $currencies = $response2->json();
                if (is_array($currencies)) {
                    foreach ($currencies as $c) {
                        if (($c['name'] ?? '') === 'BRL' || ($c['symbol'] ?? '') === 'R$') {
                            return $c;
                        }
                    }
                }
            }
            Log::error('[XGATE][CURRENCIES] Falha ao obter moedas: ' . $response->body());
            return null;
        }

        $currencies = $response->json();
        if (!is_array($currencies)) {
            return null;
        }
        foreach ($currencies as $c) {
            if (($c['name'] ?? '') === 'BRL' || ($c['symbol'] ?? '') === 'R$') {
                return $c;
            }
        }
        return $currencies[0] ?? null;
    }

    public static function requestDepositXgate($request)
    {
        $xgate = self::getXGateCredentials();
        if (!$xgate) {
            return [
                'data' => ['status' => 'error', 'message' => 'XGate não configurado'],
                'status' => 401,
            ];
        }

        $token = self::getXGateToken($xgate);
        if (!$token) {
            return [
                'data' => ['status' => 'error', 'message' => 'Falha na autenticação XGate'],
                'status' => 401,
            ];
        }

        $data = $request;
        $document = preg_replace('/\D/', '', $data->debtor_document_number ?? '');

        $customerPayload = [
            'name' => $data->debtor_name ?? 'Cliente',
            'document' => $document ?: '00000000000',
            'email' => $data->email ?? null,
            'phone' => $data->phone ?? null,
            'notValidationDuplicated' => true,
        ];

        $createCustomer = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/customer', array_filter($customerPayload));

        if (!$createCustomer->successful()) {
            Log::error('[XGATE][DEPOSIT] Falha ao criar cliente: ' . $createCustomer->body());
            return [
                'data' => [
                    'status' => 'error',
                    'message' => $createCustomer->json('message') ?? 'Erro ao criar cliente no XGate',
                ],
                'status' => $createCustomer->status(),
            ];
        }

        $customerId = $createCustomer->json('customer._id');
        if (!$customerId) {
            return [
                'data' => ['status' => 'error', 'message' => 'Resposta XGate inválida'],
                'status' => 500,
            ];
        }

        $currency = self::getXGateBrlCurrency($token);
        if (!$currency) {
            return [
                'data' => ['status' => 'error', 'message' => 'Moeda BRL não encontrada no XGate'],
                'status' => 500,
            ];
        }

        $depositPayload = [
            'amount' => (float) $data->amount,
            'customerId' => $customerId,
            'currency' => $currency,
        ];

        $depositResponse = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/deposit', $depositPayload);

        Log::debug('[XGATE][DEPOSIT] Request deposit | Response: ' . $depositResponse->status() . ' ' . $depositResponse->body());

        if ($depositResponse->successful()) {
            $responseData = $depositResponse->json();
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

            $idTransaction = $responseData['data']['id'] ?? $responseData['id'] ?? Str::uuid()->toString();
            $dataResp = $responseData['data'] ?? $responseData;
            $qrcode = $dataResp['code'] ?? $dataResp['pix']['copyPaste'] ?? $dataResp['pix']['payload'] ?? $dataResp['brcode'] ?? $dataResp['emv'] ?? $dataResp['payload'] ?? $responseData['code'] ?? $idTransaction;
            $qrImage = $dataResp['qrCode'] ?? $dataResp['qr_code_base64'] ?? $dataResp['qrcode'] ?? $dataResp['pix']['qrCode'] ?? $dataResp['pix']['encodedImage'] ?? $dataResp['pix']['qrcode'] ?? $responseData['qrCode'] ?? '';

            if (empty($qrImage) && !empty($qrcode)) {
                $qrImage = 'https://quickchart.io/qr?text=' . urlencode($qrcode) . '&size=300';
            } elseif (!empty($qrImage) && !str_starts_with((string) $qrImage, 'data:') && !str_starts_with((string) $qrImage, 'http')) {
                $qrImage = 'data:image/png;base64,' . $qrImage;
            }

            $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

            $cashin = [
                'external_id' => $idTransaction,
                'amount' => $data->amount,
                'client_name' => $data->debtor_name ?? 'Cliente',
                'client_cpf' => $document ?: null,
                'client_email' => $data->email ?? null,
                'status' => 'pendente',
                'idTransaction' => $idTransaction,
                'cash_in_liquido' => $deposito_liquido,
                'taxa_reserva' => $taxa_reserva,
                'qrcode_pix' => $qrcode,
                'paymentcode' => $qrcode,
                'paymentCodeBase64' => $qrcode,
                'adquirente_ref' => 'xgate',
                'taxa_cash_in' => $taxa_cash_in,
                'executor_ordem' => 'xgate',
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
                'message' => $depositResponse->json('message') ?? 'Erro ao criar depósito XGate',
            ],
            'status' => $depositResponse->status() ?: 500,
        ];
    }

    public static function requestPaymentXgate($request)
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
            return self::generateTransactionPaymentManualXgate($request, $taxa_cash_out, $cashout_liquido, Carbon::now(), $descricao, $user);
        }

        $xgate = self::getXGateCredentials();
        if (!$xgate) {
            return [
                'data' => ['status' => 'error', 'message' => 'XGate não configurado'],
                'status' => 401,
            ];
        }

        $token = self::getXGateToken($xgate);
        if (!$token) {
            return [
                'data' => ['status' => 'error', 'message' => 'Falha na autenticação XGate'],
                'status' => 401,
            ];
        }

        $pixKeyType = match (strtolower($request->pixKeyType ?? 'cpf')) {
            'email' => 'EMAIL',
            'telefone', 'phone' => 'PHONE',
            'aleatoria', 'random', 'evp' => 'EVP',
            default => 'CPF',
        };

        $pixKey = $request->pixKey;
        if (in_array(strtolower($pixKeyType), ['cpf', 'cnpj', 'phone'])) {
            $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
        }

        $document = preg_replace('/\D/', '', $user->cpf_cnpj ?? '');

        $customerPayload = [
            'name' => $user->name ?? 'Cliente',
            'document' => $document ?: '00000000000',
            'email' => $user->email ?? null,
            'phone' => $user->telefone ?? null,
            'notValidationDuplicated' => true,
        ];

        $createCustomer = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/customer', array_filter($customerPayload));

        if (!$createCustomer->successful()) {
            Log::error('[XGATE][WITHDRAW] Falha ao criar cliente: ' . $createCustomer->body());
            return [
                'data' => [
                    'status' => 'error',
                    'message' => $createCustomer->json('message') ?? 'Erro ao criar cliente no XGate',
                ],
                'status' => $createCustomer->status(),
            ];
        }

        $customerId = $createCustomer->json('customer._id');
        if (!$customerId) {
            return [
                'data' => ['status' => 'error', 'message' => 'Resposta XGate inválida'],
                'status' => 500,
            ];
        }

        $pixKeyPayload = ['key' => $pixKey, 'type' => $pixKeyType];
        $createPixKey = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/pix/customer/' . $customerId . '/key', $pixKeyPayload);

        if (!$createPixKey->successful()) {
            $keysResponse = Http::withToken($token)
                ->get(self::getXGateBaseUrl() . '/pix/customer/' . $customerId . '/key');
            $pixKeyObj = null;
            if ($keysResponse->successful()) {
                $keys = $keysResponse->json();
                $dataKeys = is_array($keys) ? ($keys['data'] ?? $keys) : [];
                $pixKeyObj = is_array($dataKeys) ? ($dataKeys[0] ?? null) : null;
            }
            if (!$pixKeyObj) {
                Log::error('[XGATE][WITHDRAW] Falha ao registrar chave PIX: ' . $createPixKey->body());
                return [
                    'data' => [
                        'status' => 'error',
                        'message' => $createPixKey->json('message') ?? 'Erro ao registrar chave PIX no XGate',
                    ],
                    'status' => $createPixKey->status(),
                ];
            }
        } else {
            $pixKeyObj = $createPixKey->json();
            if (isset($pixKeyObj['data'])) {
                $pixKeyObj = $pixKeyObj['data'];
            }
        }

        $pixKeyForWithdraw = [
            'key' => $pixKeyObj['key'] ?? $pixKey,
            'type' => $pixKeyObj['type'] ?? $pixKeyType,
            '_id' => $pixKeyObj['_id'] ?? null,
        ];
        $pixKeyForWithdraw = array_filter($pixKeyForWithdraw);

        $currency = self::getXGateBrlCurrency($token);
        if (!$currency) {
            return [
                'data' => ['status' => 'error', 'message' => 'Moeda BRL não encontrada no XGate'],
                'status' => 500,
            ];
        }

        $withdrawPayload = [
            'amount' => (float) $cashout_liquido,
            'customerId' => $customerId,
            'currency' => $currency,
            'pixKey' => $pixKeyForWithdraw,
        ];

        $withdrawResponse = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/withdraw', $withdrawPayload);

        Log::debug('[XGATE][WITHDRAW] Request withdraw | Response: ' . $withdrawResponse->status() . ' ' . $withdrawResponse->body());

        if ($withdrawResponse->successful() || $withdrawResponse->status() === 202) {
            Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
            Helper::decrementAmount($user, $cashout_liquido, 'saldo');

            $responseData = $withdrawResponse->json();
            $externalId = $responseData['id'] ?? $responseData['data']['id'] ?? $responseData['_id'] ?? Str::uuid()->toString();
            $internalId = strtoupper(str_replace('-', '', (string) Str::uuid()));

            $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

            TransactionOut::create([
                'user_id' => $request->user->id,
                'external_id' => $externalId,
                'amount' => $request->amount,
                'recebedor_name' => $user->name ?? 'Cliente',
                'recebedor_cpf' => $document ?: $pixKey,
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
                'adquirente_ref' => 'xgate',
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
            'status' => $withdrawResponse->status() ?: 500,
            'data' => [
                'status' => 'error',
                'message' => $withdrawResponse->json('message') ?? 'Erro ao criar saque XGate',
            ],
        ];
    }

    protected static function generateTransactionPaymentManualXgate($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user): array
    {
        $idTransaction = (string) Str::uuid();
        $internalId = strtoupper(str_replace('-', '', $idTransaction));
        $pixKey = preg_replace('/[^0-9]/', '', $request->pixKey);

        if (in_array(strtolower($request->pixKeyType ?? ''), ['cpf', 'cnpj', 'phone'])) {
            $pixKey = preg_replace('/[^0-9]/', '', $request->pixKey);
        } else {
            $pixKey = $request->pixKey;
        }

        $ip = $request->header('X-Forwarded-For') ?: ($request->header('CF-Connecting-IP') ?: $request->ip());

        TransactionOut::create([
            'user_id' => $request->user->id,
            'external_id' => $idTransaction,
            'amount' => $request->amount,
            'recebedor_name' => $user->name ?? 'Cliente',
            'recebedor_cpf' => $pixKey,
            'pixKey' => $request->pixKey,
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
            'adquirente_ref' => 'xgate',
            'end2end' => $idTransaction,
            'end_to_end' => $idTransaction,
        ]);

        Helper::incrementAmount($user, $request->amount, 'valor_saque_pendente');
        Helper::decrementAmount($user, $cashout_liquido, 'saldo');

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

    public static function liberarSaqueManualXgate($id)
    {
        $xgate = self::getXGateCredentials();
        if (!$xgate) {
            return back()->with('error', 'XGate não configurado.');
        }

        $cashout = TransactionOut::where('id', $id)->first();
        if (!$cashout) {
            return back()->with('error', 'Saque não encontrado.');
        }

        $token = self::getXGateToken($xgate);
        if (!$token) {
            return back()->with('error', 'Falha na autenticação XGate.');
        }

        $pixKeyType = match (strtolower($cashout->pixKeyType ?? 'cpf')) {
            'email' => 'EMAIL',
            'phone', 'telefone' => 'PHONE',
            'aleatoria', 'random', 'evp' => 'EVP',
            default => 'CPF',
        };

        $pixKey = $cashout->pixKey;
        if (in_array(strtolower($pixKeyType), ['cpf', 'cnpj', 'phone'])) {
            $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
        }

        $document = preg_replace('/\D/', '', $cashout->recebedor_cpf ?? '');

        $customerPayload = [
            'name' => $cashout->recebedor_name ?? 'Cliente',
            'document' => $document ?: '00000000000',
            'notValidationDuplicated' => true,
        ];

        $createCustomer = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/customer', $customerPayload);

        if (!$createCustomer->successful()) {
            return back()->with('error', 'Erro ao criar cliente: ' . ($createCustomer->json('message') ?? $createCustomer->body()));
        }

        $customerId = $createCustomer->json('customer._id');
        if (!$customerId) {
            return back()->with('error', 'Resposta XGate inválida.');
        }

        $pixKeyPayload = ['key' => $pixKey, 'type' => $pixKeyType];
        $createPixKey = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/pix/customer/' . $customerId . '/key', $pixKeyPayload);

        $pixKeyObj = null;
        if ($createPixKey->successful()) {
            $pixKeyObj = $createPixKey->json();
            if (isset($pixKeyObj['data'])) {
                $pixKeyObj = $pixKeyObj['data'];
            }
        } else {
            $keysResponse = Http::withToken($token)
                ->get(self::getXGateBaseUrl() . '/pix/customer/' . $customerId . '/key');
            if ($keysResponse->successful()) {
                $keys = $keysResponse->json();
                $dataKeys = is_array($keys) ? ($keys['data'] ?? $keys) : [];
                $pixKeyObj = is_array($dataKeys) ? ($dataKeys[0] ?? null) : null;
            }
        }

        if (!$pixKeyObj) {
            return back()->with('error', 'Erro ao registrar chave PIX no XGate.');
        }

        $pixKeyForWithdraw = [
            'key' => $pixKeyObj['key'] ?? $pixKey,
            'type' => $pixKeyObj['type'] ?? $pixKeyType,
            '_id' => $pixKeyObj['_id'] ?? null,
        ];
        $pixKeyForWithdraw = array_filter($pixKeyForWithdraw);

        $currency = self::getXGateBrlCurrency($token);
        if (!$currency) {
            return back()->with('error', 'Moeda BRL não encontrada no XGate.');
        }

        $withdrawPayload = [
            'amount' => (float) $cashout->cash_out_liquido,
            'customerId' => $customerId,
            'currency' => $currency,
            'pixKey' => $pixKeyForWithdraw,
        ];

        $withdrawResponse = Http::withToken($token)
            ->post(self::getXGateBaseUrl() . '/withdraw', $withdrawPayload);

        if ($withdrawResponse->successful() || $withdrawResponse->status() === 202) {
            $responseData = $withdrawResponse->json();
            $externalId = $responseData['id'] ?? $responseData['data']['id'] ?? $responseData['_id'] ?? $cashout->external_id;

            $cashout->update([
                'external_id' => $externalId,
                'end2end' => $externalId,
                'end_to_end' => $externalId,
                'descricao_transacao' => 'LIBERADOADMIN',
            ]);

            return back()->with('success', 'Pedido de saque enviado com sucesso!');
        }

        return back()->with('error', 'Erro ao liberar saque: ' . ($withdrawResponse->json('message') ?? $withdrawResponse->body()));
    }
}
