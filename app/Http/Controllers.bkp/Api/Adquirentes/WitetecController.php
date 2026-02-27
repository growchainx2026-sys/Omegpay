<?php

namespace App\Http\Controllers\Api\Adquirentes;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Mail\SendCredentialsAluno;
use App\Models\AffiliateHistory;
use App\Models\Aluno;
use App\Models\Pedido;
use App\Models\TransactionIn;
use App\Models\TransactionOut;
use App\Models\User;
use App\Notifications\GeralNotification;
use App\Services\ProcessaCallback;
use App\Traits\Tracking\UtmfyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Traits\WitetecTrait;

class WitetecController extends Controller
{

    public function registerWebhook(Request $request)
    {
        WitetecTrait::cadastrarWebhookWitetec();
        return response()->json(['status' => true]);
    }

    public function callbackDeposit(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][DEPOSIT][WITETEC] -> dados recebidos: " . json_encode($data));

        $idTransaction = $data['id'];
        $status = 'pendente';
        $end2end = $data['end2endId'] ?? null;

        switch ($data["eventType"]) {
            case "TRANSACTION_PAID":
                $status = 'pago';
                break;
            case "TRANSACTION_FAILED":
            case "TRANSACTION_REFUSED":
                $status = 'cancelado';
        }

        $processa = new ProcessaCallback();
        $processa->deposit($idTransaction, $status, $end2end, 'WITETEC');
        return response()->json([]);
    }

    public function callbackWithdraw(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][WITHDRAW][WITETEC] -> dados recebidos: " . json_encode($data));

        $idTransaction = $data['id'];
        $status = 'pendente';

        switch ($data["eventType"]) {
            case "WITHDRAW_PAID":
                $status = 'pago';
                break;
            case "WITHDRAWAL_FAILED":
            case "WITHDRAWAL_CANCELED":
            case "WITHDRAWAL_REFUNDED":
            case "WITHDRAWAL_REJECTED":
                $status = 'cancelado';
                break;
        }

        $processa = new ProcessaCallback();
        $processa->withdraw($idTransaction, $status, null, 'WITETEC');
        return response()->json([]);
    }
}
