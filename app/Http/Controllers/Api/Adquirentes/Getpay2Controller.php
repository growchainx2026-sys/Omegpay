<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Http\Controllers\Controller;
use App\Models\Getpay2;
use App\Services\ProcessaCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Getpay2Controller extends Controller
{
    public function deposit(Request $request)
    {
        $data = $request->all();
        Log::debug('[CALLBACK][DEPOSIT][GETPAY2] dados recebidos: ' . json_encode($data));

        $getpay = Getpay2::first();
        if ($getpay?->webhook_token_deposit) {
            $token = $request->header('X-Webhook-Token') ?? $request->input('token') ?? $request->input('webhook_token');
            if ($token !== $getpay->webhook_token_deposit) {
                Log::warning('[CALLBACK][DEPOSIT][GETPAY2] Token de webhook inválido');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        $idTransaction = $data['payment_id'] ?? $data['externalId'] ?? $data['id'] ?? $data['orderId'] ?? null;
        if (!$idTransaction) {
            Log::warning('[CALLBACK][DEPOSIT][GETPAY2] idTransaction não encontrado no payload');
            return response()->json([], 200);
        }

        $status = $data['status'] ?? $data['payment_status'] ?? null;
        if ($status === 'paid' || $status === 'completed' || $status === 'pago' || $status === 'PAID') {
            $end2end = $data['endToEndId'] ?? $data['end2end'] ?? $data['e2eid'] ?? null;
            $processa = new ProcessaCallback();
            $processa->deposit($idTransaction, 'pago', $end2end, 'GETPAY2');
        }

        return response()->json([]);
    }

    public function withdraw(Request $request)
    {
        $data = $request->all();
        Log::debug('[CALLBACK][WITHDRAW][GETPAY2] dados recebidos: ' . json_encode($data));

        $getpay = Getpay2::first();
        if ($getpay?->webhook_token_withdraw) {
            $token = $request->header('X-Webhook-Token') ?? $request->input('token') ?? $request->input('webhook_token');
            if ($token !== $getpay->webhook_token_withdraw) {
                Log::warning('[CALLBACK][WITHDRAW][GETPAY2] Token de webhook inválido');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        $idTransaction = $data['id'] ?? $data['transaction_id'] ?? $data['externalId'] ?? null;
        if (!$idTransaction) {
            Log::warning('[CALLBACK][WITHDRAW][GETPAY2] idTransaction não encontrado no payload');
            return response()->json([], 200);
        }

        $statusRaw = $data['status'] ?? $data['withdrawStatusId'] ?? $data['payment_status'] ?? null;
        $status = in_array(strtolower((string) $statusRaw), ['paid', 'success', 'successful', 'completed', 'pago'])
            ? 'pago'
            : 'cancelado';

        $processa = new ProcessaCallback();
        $processa->withdraw($idTransaction, $status, null, 'GETPAY2');

        return response()->json([]);
    }
}
