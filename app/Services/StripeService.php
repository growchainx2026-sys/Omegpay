<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Stripe as StripeModel;
use Illuminate\Http\JsonResponse;
use Stripe\Checkout\Session;
use \Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    protected string $baseUrl = "https://api.stripe.com";
    protected $publicKey;
    protected $secretKey;


    public function __construct()
    {
        $this->getCredentials();
    }

    protected function getCredentials()
    {
        $setting = StripeModel::first();
        $this->publicKey = $setting->public_key;
        $this->secretKey = $setting->secret_key;
    }

    /**
     * Cria PaymentIntent e retorna clientSecret
     *
     * @param float $amount valor em unidades (ex: 10.50 -> R$10,50)
     * @param string $currency 'brl', 'usd', ...
     * @return JsonResponse
     */
    public function paymentCard(float $amount, string $currency, $parcelas): JsonResponse
    {
        $this->getCredentials();
        Stripe::setApiKey($this->secretKey);

        $amountInCents = (int) round($amount * 100);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents, // sempre em centavos
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'payment_method_options' => [
                'card' => [
                    'installments' => [
                        'enabled' => true,
                    ],
                ],
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
            'plans' => $paymentIntent->payment_method_options['card']['installments']['plans'] ?? null,
        ]);
    }

}