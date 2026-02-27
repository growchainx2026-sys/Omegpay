<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Models\Pagarme;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PagarmeService
{
    protected string $token;
    protected string $baseUrl = "https://api.pagar.me";

    public function __construct(string $secret)
    {
        $access_token = base64_encode("$secret:");
        $this->token = $access_token;
    }

    protected function getcredentials()
    {
        $setting = Pagarme::first();
        $this->token = base64_encode($setting->secret . ":");
    }

    public function paymentPix($data, $amount)
    {
  
        $data = $data->all();
        $this->getcredentials();
        $pessoa = $this->gerarPessoa();
        $client_code = uniqid(strtoupper(str_replace(' ', '_', env('APP_NAME'))) . '_');

        $cpf = $data['debtor_document_number'];
        if(!Helper::validarCPF($cpf)){
            $cpf = $pessoa['cpf'];
        }

        $payload = [
            "customer" => [
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => "55",
                        "area_code" => $this->ajustePhone('' . $data['phone'])['ddd'],
                        "number" => $this->ajustePhone('' . $data['phone'])['phone']
                    ]
                ],
                "name" => $data['debtor_name'],
                "document" => str_replace([".", "-"], "", $cpf),
                "email" => $data['email'],
                "type" => "individual",
                "document_type" => "CPF"
            ],
            "payments" => [
                [
                    "Pix" => [
                        "expires_in" => 3600
                    ],
                    "payment_method" => "pix"
                ]
            ],
            "items" => [
                [
                    "amount" => intval($amount * 100),
                    "code" => $client_code,
                    "quantity" => 1,
                    "description" => "Pagamento $client_code"
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->token
        ])->post($this->baseUrl . "/core/v5/orders", $payload);

        return $response->json();
    }

    public function paymentBillet($amount, $description, $customer)
    {
       
        $pessoa = $this->gerarPessoa();
        $phone = explode(" ", $customer["phone"]);
        $phone_area = str_replace(['(', ')'], '', $phone[0]);
        $phone_number = str_replace(['-', ' '], '', $phone[1]);

        $this->getcredentials();
        $client_code = uniqid(strtoupper(str_replace(' ', '_', env('APP_NAME'))) . '_');

        $nosso_numero = str_pad((string) random_int(0, 99999999999999), 16, '0', STR_PAD_LEFT);

        $payload = [
            "customer" => [
               
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => "55",
                        "area_code" => $phone_area,
                        "number" => $phone_number
                    ]
                ],
                "name" => $customer['name'],
                "type" => "individual",
                "email" => $customer['email'],
                "document" => str_replace([".", "-"], "", $customer['cpf']),
                "document_type" => "CPF",
               
            ],
            "items" => [
                [
                    "amount" => intval($amount),
                    "description" => "Produto X",
                    "code" => $client_code,
                    "quantity" => 1
                ]
            ],
            "payments" => [
                [
                    "boleto" => [
                        "bank" => "104",
                        "instructions" => "Compra produto",
                        "nosso_numero" => $nosso_numero
                    ],
                    "payment_method" => "boleto"
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->token
        ])->post($this->baseUrl . "/core/v5/orders", $payload);

        return $response->json();
    }

    public function paymentCard($amount, $description, $cartao, $parcelas, $customer)
    {
        $this->getcredentials();
        $pessoa = $this->gerarPessoa();

        // ===== LOG 1: DADOS RECEBIDOS =====
        Log::info('[PAGARME_SERVICE][CARD] Iniciando pagamento', [
            'amount' => $amount,
            'installments' => $parcelas,
            'customer_name' => $customer['name'],
            'customer_cpf' => $customer['cpf']
        ]);
        // ==================================

        $phone = explode(" ", $customer["phone"]);
        $phone_area = str_replace(['(', ')'], '', $phone[0]);
        $phone_number = str_replace(['-', ' '], '', $phone[1]);

        $client_code = uniqid(strtoupper(str_replace(' ', '_', env('APP_NAME'))) . '_');

        $payload = [
            "customer" => [
                "phones" => [
                    "mobile_phone" => [
                        "country_code" => "55",
                        "area_code" => $phone_area,
                        "number" => $phone_number
                    ]
                ],
                "name" => $customer['name'],
                "document" => str_replace([".", "-"], "", $customer['cpf']),
                "email" => $customer['email'],
                "type" => "individual",
                "document_type" => "CPF"
            ],
            "payments" => [
                [
                    "credit_card" => [
                        "operation_type" => "auth_and_capture",
                        "installments" => $parcelas,
                        "statement_descriptor" => env("APP_NAME"),
                        "card" => [
                            "number" => str_replace(' ', '', $cartao['number']),
                            "holder_name" => $cartao["holder_name"],
                            "exp_month" => $cartao['exp_month'],
                            "exp_year" => $cartao['exp_year'],
                            "cvv" => $cartao['cvv'],
                            "billing_address" => [
                                'line_1' => $pessoa['endereco'],
                                'zip_code' => $pessoa['cep'],
                                'city' => $pessoa['cidade'],
                                'state' => $pessoa['estado'],
                                'country' => "BR",
                            ]
                        ]
                    ],
                    "payment_method" => "credit_card"
                ]
            ],
            "items" => [
                [
                    "amount" => intval($amount),
                    "code" => $client_code,
                    "quantity" => 1,
                    "description" => "$client_code"
                ]
            ]
        ];

        // ===== LOG 2: PAYLOAD ENVIADO (SEM DADOS SENSÍVEIS) =====
        Log::info('[PAGARME_SERVICE][CARD] Payload montado', [
            'customer' => [
                'name' => $payload['customer']['name'],
                'email' => $payload['customer']['email'],
                'phone' => $phone_area . $phone_number
            ],
            'amount' => $payload['items'][0]['amount'],
            'installments' => $payload['payments'][0]['credit_card']['installments'],
            'card_holder' => $cartao["holder_name"]
        ]);
        // ========================================================

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . $this->token
        ])->post($this->baseUrl . "/core/v5/orders", $payload);

        $responseData = $response->json();

        // ===== LOG 3: RESPOSTA COMPLETA DA API =====
        Log::info('[PAGARME_SERVICE][CARD] Resposta da API', [
            'status_code' => $response->status(),
            'response' => $responseData
        ]);
        // ===========================================

        // ===== LOG 4: VERIFICAÇÃO DE CHARGES =====
        if (!isset($responseData['charges'])) {
            Log::error('[PAGARME_SERVICE][CARD] Campo charges não existe na resposta', [
                'response_keys' => array_keys($responseData),
                'full_response' => $responseData
            ]);
        } else {
            Log::info('[PAGARME_SERVICE][CARD] Campo charges encontrado', [
                'charges_count' => count($responseData['charges']),
                'first_charge_status' => $responseData['charges'][0]['status'] ?? 'N/A'
            ]);
        }
        // =========================================

        return $responseData;
    }

    protected function ajustePhone($string)
    {
        if (strpos($string, "55") === 0 && strpos($string, "62") === 2) {
            $ddd = substr($string, 2, 2);
            $phone = substr($string, 4);
        } else {
            $ddd = substr($string, 0, 2);
            $phone = substr($string, 2);
        }

        return [
            'ddd' => $ddd,
            'phone' => $phone
        ];
    }

    protected function gerarPessoa()
    {
        $url = "https://www.4devs.com.br/ferramentas_online.php";
        $data = "acao=gerar_pessoa&sexo=I&pontuacao=N&idade=0&cep_estado=&txt_qtde=1&cep_cidade=";

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded",
                "Referer: https://www.4devs.com.br/gerador_de_pessoas",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36 OPR/114.0.0.0",
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            $dados = json_decode($response, true);
            if (isset($dados[0]['nome']) && isset($dados[0]['cpf']) && isset($dados[0]['email'])) {
                return $dados[0];
            }
        }

        return null;
    }
}