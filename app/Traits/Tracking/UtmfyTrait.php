<?php

namespace App\Traits\Tracking;

use App\Models\{TransactionIn, Produto, Setting};
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class UtmfyTrait
{
    /**
     * @param 'pix'|'credit_card' | 'boleto' $method
     * @param 'waiting_payment' | 'paid' | 'refunded'
     * @param TransactionIn $transaction
     * @param Produto $produto
     * @param string $apiToken
     * @param string $ip
     * @param string $description
     */
    public static function gerarUTM($method, $status, $transaction, $produto, $apiToken, $ip)
    {
        $setting = Setting::first();

        $dataAtual = Carbon::now()->format('Y-m-d H:i:s');
        Http::withHeaders([
            'x-api-token' => $apiToken,
        ])->post('https://api.utmify.com.br/api-credentials/orders', [
            'orderId' => $transaction->idTransaction,
            'platform' => $setting->software_name,
            'paymentMethod' => $method,
            'status' => $status,
            'createdAt' => $dataAtual,
            'approvedDate' => null,
            'refundedAt' => null,
            'customer' => [
                'name' => $transaction->client_name,
                'email' => $transaction->client_email,
                'phone' => $transaction->client_telefone,
                'document' => $transaction->client_document,
                'country' => 'BR',
                'ip' => $ip,
            ],
            'products' => [
                [
                    'id' => $produto->uuid,
                    'name' =>  $produto->description,
                    'planId' => null,
                    'planName' => null,
                    'quantity' => 1,
                    'priceInCents' => (int) $transaction->amount * 100,
                ],
            ],
            'trackingParameters' => [
                'src' => null,
                'sck' => null,
                'utm_source' => null,
                'utm_campaign' => null,
                'utm_medium' => null,
                'utm_content' => null,
                'utm_term' => null,
            ],
            'commission' => [
                'totalPriceInCents' => (int) $transaction->amount * 100,
                'gatewayFeeInCents' => 0,
                'userCommissionInCents' => 0,
            ],
            'isTest' => false,
        ]);
    }
}
