<?php

namespace App\Http\Controllers\Api\Adquirentes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProcessaCallback;

class TransfeeraController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        $tipo = $data['object'] == "CashIn" && isset($data['data']['payer']['name']) ? 'deposito' : 'saque';
        Log::debug("[+][CALLBACK][DEPOSIT][TRANSFEERA] -> Tipo de callback: {$tipo}");

        switch ($tipo) {
            case 'deposito':
                $idTransaction = $data['data']['txid'];
                $processa = new ProcessaCallback();
                $processa->deposit($idTransaction, 'pago', null, 'TRANSFEERA');
                return response()->json([]);
                break;
            case 'saque':
                $idTransaction = $data['data']['idempotency_key'];
                $status = 'pago';
                $processa = new ProcessaCallback();
                $processa->withdraw($idTransaction, $status, null, 'TRANSFEERA');
                return response()->json([]);
                break;
        }
    }
}
