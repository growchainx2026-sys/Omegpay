<?php

namespace App\Http\Controllers\Api\Adquirentes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\ProcessaCallback;
use App\Traits\EfiTrait;

class EfiController extends Controller
{
    public function registerWebhook(Request $request)
    {
        EfiTrait::cadastrarWebhook();
        return response()->json(['status' => true]);
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][CALLBACK][DEPOSIT][EFI] -> dados recebidos: " . json_encode($data));
        if (isset($data['evento']) && $data['evento'] == 'teste_webhook') {
            Log::debug("[+][CALLBACK][DEPOSIT][EFI] -> Webhooks registrado com sucesso. ");
            return response()->json([], 200);
        }

        $dados = $data['pix'][0];
        $tipo = isset($dados['tipo']) && $dados['tipo'] == 'SOLICITACAO' ? 'saque' : 'deposito';
        Log::debug("[+][CALLBACK][DEPOSIT][EFI] -> Tipo de callback: {$tipo}");

        switch ($tipo) {
            case 'deposito':
                $idTransaction = $dados['txid'];
                $existEndToEndId = isset($dados['endToEndId']) && isset($dados['gnExtras']['pagador']);
                if ($existEndToEndId) {
                    $processa = new ProcessaCallback();
                    $processa->deposit($idTransaction, 'pago', $dados['endToEndId'], 'EFI');
                }
                return response()->json([]);
                break;
            case 'saque':
                $idTransaction = $dados['gnExtras']['idEnvio'];
                $status = $dados['status'] == 'REALIZADO' ? 'pago' : 'cancelado';
                $processa = new ProcessaCallback();
                $processa->withdraw($idTransaction, $status, null, 'EFI');
                return response()->json([]);
                break;
        }
    }

    public function webhookBillet(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][EFICONTROLLER][WEBHOOK][BILLET] -> dados recebidos: " . json_encode($data));
        EfiTrait::consultaTransaction($data['notification'], 'billet');
    }

    public function webhookCard(Request $request)
    {
        $data = $request->all();
        Log::debug("[+][EFICONTROLLER][WEBHOOK][CARD] -> dados recebidos: " . json_encode($data));
        EfiTrait::consultaTransaction($data['notification'], 'card');
    }
}
