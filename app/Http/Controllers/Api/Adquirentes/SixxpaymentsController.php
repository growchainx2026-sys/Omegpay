<?php

namespace App\Http\Controllers\Api\Adquirentes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProcessaCallback;

class SixxpaymentsController extends Controller
{
    
    public function deposit(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][DEPOSIT][SIXXPAYMENTS] -> dados recebidos: " . json_encode($data));

        $idTransaction = $data['orderId'];
        if (isset($data['status']) && $data['status'] == 'paid') {
            $processa = new ProcessaCallback();
            $processa->deposit($idTransaction, 'pago', null, 'SIXXPAYMENTS');
        }
        return response()->json([]);
    }

    
    public function withdraw(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][WITHDRAW][SIXXPAYMENTS] -> dados recebidos: " . json_encode($data));

        $idTransaction = $data['id'];
        $status = $data['withdrawStatusId'] == "Successfull" ? 'pago' : 'cancelado';
        $processa = new ProcessaCallback();
        $processa->withdraw($idTransaction, $status, null, 'SIXXPAYMENTS');
        return response()->json([]);
    }
}
