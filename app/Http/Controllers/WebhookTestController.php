<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class WebhookTestController extends Controller
{
    /**
     * Página para testar webhooks
     */
    public function index()
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }

        return view('pages.webhook-tests');
    }

    /**
     * Envia uma requisição de teste para a URL informada (evita CORS no cliente)
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
            'payload' => 'required',
        ], [
            'url.required' => 'Informe a URL do webhook.',
            'url.url' => 'A URL informada não é válida.',
            'payload.required' => 'O payload é obrigatório.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $url = $request->input('url');
        $payloadRaw = $request->input('payload');
        $payload = is_string($payloadRaw) ? json_decode($payloadRaw, true) : (array) $payloadRaw;
        $method = strtoupper($request->input('method', 'POST'));

        if (!in_array($method, ['GET', 'POST', 'PUT'])) {
            $method = 'POST';
        }

        try {
            $http = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(15);

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
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar: ' . $e->getMessage(),
                'status' => null,
                'body' => null,
            ], 500);
        }
    }
}
