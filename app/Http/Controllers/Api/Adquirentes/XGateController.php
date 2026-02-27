<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Http\Controllers\Controller;
use App\Services\ProcessaCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XGateController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::debug('[CALLBACK][XGATE] dados recebidos: ' . json_encode($data));

        $id = $data['id'] ?? null;
        if (!$id) {
            Log::warning('[CALLBACK][XGATE] id não encontrado no payload');
            return response()->json([]);
        }

        $status = strtoupper((string) ($data['status'] ?? ''));
        $operation = strtoupper((string) ($data['operation'] ?? ''));

        if ($operation === 'DEPOSIT') {
            if ($status === 'PAID') {
                $processa = new ProcessaCallback();
                $processa->deposit($id, 'pago', $id, 'XGATE');
            }
        } elseif ($operation === 'WITHDRAW') {
            $statusMapped = in_array($status, ['PAID']) ? 'pago' : 'cancelado';
            $processa = new ProcessaCallback();
            $processa->withdraw($id, $statusMapped, null, 'XGATE');
        }

        return response()->json([]);
    }
}
