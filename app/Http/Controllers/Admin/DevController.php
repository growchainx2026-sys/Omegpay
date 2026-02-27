<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class DevController extends Controller
{
    public function index()
    {
        return view('admin.dev.index');
    }

    public function manual()
    {
        return view('admin.dev.manual');
    }

    public function pixIn()
    {
        return view('admin.dev.pix-in');
    }

    public function pixOut()
    {
        return view('admin.dev.pix-out');
    }

    public function webhooks()
    {
        return view('admin.dev.webhooks');
    }

    public function adquirentes()
    {
        return view('admin.dev.adquirentes');
    }

    public function sandbox()
    {
        return view('admin.dev.sandbox');
    }

    /**
     * Testa token e secret de um cliente
     */
    public function testTokenSecret(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'secret' => 'required|string',
        ], [
            'token.required' => 'O token é obrigatório.',
            'secret.required' => 'O secret é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = $request->input('token');
        $secret = $request->input('secret');

        // Simula a validação do middleware CheckTokenAndSecret
        $result = [
            'token' => $token,
            'secret' => $secret,
            'validation_steps' => [],
            'user_found' => false,
            'user_data' => null,
            'errors' => [],
            'warnings' => [],
        ];

        // Passo 1: Verificar se token e secret foram fornecidos
        if (empty($token) || empty($secret)) {
            $result['validation_steps'][] = [
                'step' => 1,
                'name' => 'Verificação de parâmetros',
                'status' => 'error',
                'message' => 'Token ou Secret ausentes',
            ];
            $result['errors'][] = 'Você precisa fornecer tanto o token quanto o secret.';
            return response()->json($result, 400);
        }

        $result['validation_steps'][] = [
            'step' => 1,
            'name' => 'Verificação de parâmetros',
            'status' => 'success',
            'message' => 'Token e Secret fornecidos',
        ];

        // Passo 2: Buscar usuário no banco de dados
        $user = User::where('clientId', $token)->where('secret', $secret)->first();

        if (!$user) {
            // Verificar se pelo menos o token existe
            $userByToken = User::where('clientId', $token)->first();
            $userBySecret = User::where('secret', $secret)->first();

            $result['validation_steps'][] = [
                'step' => 2,
                'name' => 'Busca no banco de dados',
                'status' => 'error',
                'message' => 'Usuário não encontrado',
            ];

            if ($userByToken && !$userBySecret) {
                $result['errors'][] = 'Token encontrado, mas o Secret não corresponde.';
                $result['warnings'][] = 'O token existe no banco, mas está associado a outro secret.';
            } elseif (!$userByToken && $userBySecret) {
                $result['errors'][] = 'Secret encontrado, mas o Token não corresponde.';
                $result['warnings'][] = 'O secret existe no banco, mas está associado a outro token.';
            } else {
                $result['errors'][] = 'Token e Secret não encontrados no banco de dados.';
            }

            return response()->json($result, 401);
        }

        $result['user_found'] = true;
        $result['validation_steps'][] = [
            'step' => 2,
            'name' => 'Busca no banco de dados',
            'status' => 'success',
            'message' => 'Usuário encontrado',
        ];

        // Passo 3: Verificar status do usuário
        if ($user->banido == 1) {
            $result['validation_steps'][] = [
                'step' => 3,
                'name' => 'Verificação de status',
                'status' => 'error',
                'message' => 'Usuário banido',
            ];
            $result['errors'][] = 'Usuário está banido.';
        } elseif ($user->status != 'aprovado') {
            $result['validation_steps'][] = [
                'step' => 3,
                'name' => 'Verificação de status',
                'status' => 'error',
                'message' => 'Usuário não aprovado',
            ];
            $result['errors'][] = 'Usuário não está aprovado. Status atual: ' . $user->status;
        } else {
            $result['validation_steps'][] = [
                'step' => 3,
                'name' => 'Verificação de status',
                'status' => 'success',
                'message' => 'Usuário ativo e aprovado',
            ];
        }

        // Dados do usuário (sem informações sensíveis)
        $result['user_data'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'status' => $user->status,
            'banido' => (bool) $user->banido,
            'permission' => $user->permission,
            'clientId' => $user->clientId,
            'secret_length' => strlen($user->secret ?? ''),
            'created_at' => $user->created_at?->format('d/m/Y H:i:s'),
        ];

        // Verificar se o secret no banco corresponde exatamente
        if ($user->secret !== $secret) {
            $result['warnings'][] = 'Atenção: O secret fornecido não corresponde exatamente ao armazenado no banco.';
        }

        // Determinar status final
        $hasErrors = !empty($result['errors']);
        $result['success'] = !$hasErrors;
        $result['message'] = $hasErrors 
            ? 'Token e Secret válidos, mas há problemas com o usuário.'
            : 'Token e Secret válidos! Usuário pode usar a API.';

        Log::info('[DEV][TOKEN_SECRET_TEST] -> Teste de token/secret', [
            'token' => $token,
            'user_id' => $user->id,
            'success' => !$hasErrors,
            'errors' => $result['errors'],
        ]);

        return response()->json($result, $hasErrors ? 401 : 200);
    }

    /**
     * Testa uma requisição real à API simulando o que o cliente faz
     */
    public function testApiRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'secret' => 'required|string',
            'endpoint' => 'required|string',
        ], [
            'token.required' => 'O token é obrigatório.',
            'secret.required' => 'O secret é obrigatório.',
            'endpoint.required' => 'O endpoint é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = $request->input('token');
        $secret = $request->input('secret');
        $endpoint = $request->input('endpoint');
        $method = strtoupper($request->input('method', 'POST'));
        $payload = $request->input('payload', []);

        // Monta a URL completa
        $url = url($endpoint);
        if (!str_starts_with($endpoint, 'http')) {
            $url = url('api/' . ltrim($endpoint, '/'));
        }

        // Adiciona token e secret ao payload
        $payload['token'] = $token;
        $payload['secret'] = $secret;

        try {
            Log::info('[DEV][API_REQUEST_TEST] -> Testando requisição à API', [
                'url' => $url,
                'method' => $method,
                'token' => $token,
            ]);

            $http = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30);

            if ($method === 'GET') {
                $response = $http->get($url, $payload);
            } else {
                $response = $method === 'PUT'
                    ? $http->put($url, $payload)
                    : $http->post($url, $payload);
            }

            return response()->json([
                'success' => $response->successful(),
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
                'url' => $url,
                'payload_sent' => $payload,
            ]);
        } catch (\Exception $e) {
            Log::error('[DEV][API_REQUEST_TEST] -> Erro ao testar requisição', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar requisição: ' . $e->getMessage(),
                'url' => $url,
            ], 500);
        }
    }

    /**
     * Dispara um webhook de teste para a URL informada
     */
    public function sendWebhookTest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'method' => 'required|in:GET,POST,PUT',
            'payload' => 'required|json',
        ], [
            'url.required' => 'Informe a URL do webhook.',
            'url.url' => 'A URL informada não é válida.',
            'method.required' => 'Selecione o método HTTP.',
            'method.in' => 'Método HTTP inválido.',
            'payload.required' => 'O payload é obrigatório.',
            'payload.json' => 'O payload deve ser um JSON válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $url = $request->input('url');
        $method = strtoupper($request->input('method', 'POST'));
        $payloadRaw = $request->input('payload');
        
        // Decodifica o JSON do payload
        $payload = json_decode($payloadRaw, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Payload JSON inválido: ' . json_last_error_msg(),
            ], 422);
        }

        try {
            Log::info('[DEV][WEBHOOK_TEST] -> Enviando webhook de teste', [
                'url' => $url,
                'method' => $method,
                'payload' => $payload,
            ]);

            $http = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30);

            if ($method === 'GET') {
                $response = $http->get($url, $payload);
            } else {
                $response = $method === 'PUT'
                    ? $http->put($url, $payload)
                    : $http->post($url, $payload);
            }

            $success = $response->successful();
            $statusCode = $response->status();
            $responseBody = $response->body();
            $responseHeaders = $response->headers();

            Log::info('[DEV][WEBHOOK_TEST] -> Resposta do webhook', [
                'url' => $url,
                'success' => $success,
                'status' => $statusCode,
                'response_body' => $responseBody,
            ]);

            return response()->json([
                'success' => $success,
                'status' => $statusCode,
                'body' => $responseBody,
                'headers' => $responseHeaders,
                'message' => $success 
                    ? 'Webhook enviado com sucesso!' 
                    : 'Webhook enviado, mas retornou erro.',
            ]);
        } catch (\Exception $e) {
            Log::error('[DEV][WEBHOOK_TEST] -> Erro ao enviar webhook', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar webhook: ' . $e->getMessage(),
                'status' => null,
                'body' => null,
            ], 500);
        }
    }
}

