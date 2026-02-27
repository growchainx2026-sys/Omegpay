<?php

namespace App\Services;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Http;

class AppmaxService
{
    private $baseUrl;
    private $token;
    private $api;
    private $customerId;

    /**
     * Construtor da classe
     *
     * @param  mixed $token
     * @param  string $ip
     * @return void
     */
    public function __construct(mixed $token, string $ip)
    {
        $this->baseUrl  = 'https://api.appmax.com.br/api/';
        $this->token    = $token;
        $this->api      = $ip;
    }

    public function criarPedido(mixed $data)
    {
        $customerId = $this->criarCliente($data);

        $payload = [
                "access-token" => $this->token,
                "total" => (float) number_format($data->amount, 2, '.', ''),
                "products" => [
                    [
                        "sku" => "123456",
                        "name" => "Produto 1",
                        "qty" => 1,
                        "digital_product" => 1
                    ]
                ],
                "customer_id" => (int) $customerId,
            ];

        return Http::withToken($this->token)
            ->post("{$this->baseUrl}v3/order", $payload)
            ->json();
    }

    /**
     * Criar cliente no Appmax
     *
     * @param  mixed $data
     * @return void
     */
    public function criarCliente($data)
    {
        $fullname = explode(' ', $data->debtor_name);
        $firstname = $fullname[0];
        $lastname = $fullname[1] ?? "Silva";
        $email = $data->email;

        $payload = [
            "access-token" => $this->token,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "telephone" => Helper::formatarTelefoneBR($data->phone),
            "ip" => $this->api,
        ];
        $response = Http::withToken($this->token)
            ->post("{$this->baseUrl}v3/customer", $payload)
            ->json();
dd($response);
        $this->customerId = $response['customer_id'];
        return $this->customerId;
    }
}
