<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Http\Controllers\Controller;
use App\Models\Rapdyn;
use App\Services\ProcessaCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RapdynController extends Controller
{
    public function deposit(Request $request)
    {
        $data = $request->all();
        Log::debug('[CALLBACK][DEPOSIT][RAPDYN] dados recebidos: ' . json_encode($data));

        if (($data['notification_type'] ?? '') !== 'transaction') {
            return response()->json([]);
        }

        $rapdyn = Rapdyn::first();
        if ($rapdyn?->webhook_token_deposit) {
            $token = $request->header('X-Webhook-Token') ?? $request->header('Authorization') ?? $request->input('token') ?? $request->input('webhook_token');
            $token = is_string($token) ? preg_replace('/^Bearer\s+/i', '', $token) : $token;
            if ($token !== $rapdyn->webhook_token_deposit) {
                Log::warning('[CALLBACK][DEPOSIT][RAPDYN] Token de webhook inválido');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        $idTransaction = $data['id'] ?? $data['external_id'] ?? $data['externalId'] ?? $data['payment_id'] ?? $data['transaction_id'] ?? null;
        if (!$idTransaction) {
            Log::warning('[CALLBACK][DEPOSIT][RAPDYN] id não encontrado no payload');
            return response()->json([], 200);
        }

        $status = strtolower((string) ($data['status'] ?? ''));
        if ($status === 'paid') {
            $end2end = $data['pix']['end2EndId'] ?? $data['pix']['end2end_id'] ?? $data['end2EndId'] ?? $data['endToEndId'] ?? null;
            $processa = new ProcessaCallback();
            $processa->deposit($idTransaction, 'pago', $end2end, 'RAPDYN');
        }

        return response()->json([]);
    }

    public function withdraw(Request $request)
    {
        $data = $request->all();
        Log::debug('[CALLBACK][WITHDRAW][RAPDYN] dados recebidos: ' . json_encode($data));

        if (($data['notification_type'] ?? '') !== 'transfer_out') {
            return response()->json([]);
        }

        $rapdyn = Rapdyn::first();
        if ($rapdyn?->webhook_token_withdraw) {
            $token = $request->header('X-Webhook-Token') ?? $request->header('Authorization') ?? $request->input('token') ?? $request->input('webhook_token');
            $token = is_string($token) ? preg_replace('/^Bearer\s+/i', '', $token) : $token;
            if ($token !== $rapdyn->webhook_token_withdraw) {
                Log::warning('[CALLBACK][WITHDRAW][RAPDYN] Token de webhook inválido');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        $idTransaction = $data['transfer_id'] ?? $data['id'] ?? $data['transaction_id'] ?? $data['externalId'] ?? null;
        if (!$idTransaction) {
            Log::warning('[CALLBACK][WITHDRAW][RAPDYN] transfer_id não encontrado no payload');
            return response()->json([], 200);
        }

        $statusRaw = strtoupper((string) ($data['status'] ?? ''));
        $end2end = $data['end2EndId'] ?? $data['endToEndId'] ?? null;

        $status = match ($statusRaw) {
            'COMPLETED' => 'pago',
            'CANCELED', 'FAILED', 'REFUNDED' => 'cancelado',
            default => null,
        };

        if ($status === null) {
            return response()->json([]);
        }

        $processa = new ProcessaCallback();
        $processa->withdraw($idTransaction, $status, $end2end, 'RAPDYN');

        return response()->json([]);
    }
}
