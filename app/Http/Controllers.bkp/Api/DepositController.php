<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{TransactionIn, Setting};
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\{CashtimeTrait, SixxpaymentsTrait, EfiTrait, AppmaxTrait, WitetecTrait, PagarmeTrait};
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class DepositController extends Controller
{
    use CashtimeTrait, SixxpaymentsTrait, 
    EfiTrait, AppmaxTrait, WitetecTrait,
    PagarmeTrait;
    /**
     * Handle the deposit request.
     * @author: thigasdev 
     * @thigasdev https://t.me/thigasdev
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function deposit(Request $request)
    {
        $data = $request->all();

        try {
            $validated = $request->validate([
                'token' => ['required', 'string'],
                'secret' => ['required', 'string'],
                'amount' => ['required'],
                'debtor_name' => ['required', 'string'],
                'email' => ['required', 'string', 'email'],
                'debtor_document_number' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'method_pay' => ['required', 'string'],
                'postback' => ['required', 'string'],
            ], [
            // Mensagens de erro personalizadas
            'required' => 'Este campo é obrigatório',
            'string'   => 'Este campo deve ser uma string',
            'email'    => 'O campo deve ser um email válido',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        $setting = Setting::first();
        $deposito_minimo = $setting->deposito_minimo; 
        $deposito_maximo = $setting->deposito_maximo; 

        if($deposito_minimo > 0 && (float) $data['amount'] < (float) $deposito_minimo){
            $value = "R$ ".number_format($deposito_minimo, 2, ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O depósito mínimo é de $value.",
            ], 401);
        }

        if($deposito_maximo > 0 && (float) $data['amount'] > (float) $deposito_maximo){
            $value = "R$ ".number_format($deposito_maximo, 2, ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O depósito máximo é de $value.",
            ], 401);
        }

         $adquirencia = $setting->adquirencia_pix;

        if($request->user->adquirente_default != 'padrao'){
            $adquirencia = $request->user->adquirente_default;
        }
        
        switch ($adquirencia) {
            case 'cashtime':
                $response = self::requestDepositCashtime($request);
                break;
            case 'sixxpayments':
                $response = self::requestDepositSixxpayments($request);
                break;
            case 'efi':
                $response = self::requestDepositEfi($request);
                break;
            case 'appmax':
                $response = self::requestDepositAppmax($request);
                break;
            case 'witetec':
                $response = self::requestDepositWitetec($request);
                break;
            case 'pagarme':
                $response = self::requestDepositPagarme($request);
                break;
            default:
            $response = ['data' => ['status' => 'error', 'message' => 'Erro inesperado'], 'status' => 500];
                break;
        }

        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
    }

    /**
     * Check the status of a deposit transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $deposit = TransactionIn::where('idTransaction', $request->idTransaction)->first();
        return response()->json(['status' => $deposit->status]);
    }
}
