<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\TransactionOut;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\{CashtimeTrait, SixxpaymentsTrait, EfiTrait, WitetecTrait};
use App\Models\{Setting, TransactionIn, User};
use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    use CashtimeTrait, SixxpaymentsTrait, EfiTrait, WitetecTrait;
    /**
     * Handle the withdrawal request.
     * @author: @thigasdev https://t.me/thigasdev
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
public function withdraw(Request $request)
{
    $data = $request->all();
    Log::debug('[SAQUE][DADOS]', $data);
  	$userId = $request->user->id;
    $lockKey = "withdraw_lock_user_{$userId}";
    if (Cache::has($lockKey)) {
            Log::debug('[SAQUE][DADOS][AGUARDAR NOVO SAQUE]', $lockKey);
            return response()->json([
                'status'  => 'error',
                'message' => 'Aguarde antes de realizar um novo saque.',
            ], 429); // HTTP 429 = Too Many Requests
        }
    Cache::put($lockKey, true, now()->addSeconds(60));
  
    Helper::calculaSaldoLiquido($request->user->user_id);
        $setting = Setting::first();

        $user = User::where('id', $request->user->id)->first();

        if ((float) $user->saldo < (float)$request->amount) {
            Log::debug('[SAQUE][DADOS] - SALDO INSUFICIENTE');
            return response()->json(['status' => 'error', 'message' => 'Saldo Insuficiente.'], 401);
        }

        try {
            $validated = $request->validate([
                'token' =>    ['required', 'string'],
                'secret' =>    ['required', 'string'],
                'amount' =>    ['required'],
                'pixKey' => ['required', 'string'],
                'pixKeyType' =>    ['required', 'string', 'in:cpf,email,telefone,aleatoria'],
                'baasPostbackUrl' =>    ['required', 'string']
            ], [
            // Mensagens de erro personalizadas
            'required' => 'Este campo é obrigatório',
            'string'   => 'Este campo deve ser uma string',
            'in'       => 'Valor inválido para o campo :attribute',
        ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::debug('[SAQUE][DADOS][ERRO DE VALIDAÇÃO]', $e->errors());

            return response()->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422); // Status code 422 para erros de validação
        }

        $setting = Setting::first();
        $saque_minimo = $setting->saque_minimo; 
        $saque_maximo = $setting->saque_maximo; 
        $saques_dia = $setting->saques_dia;

        $startOfDay = Carbon::today();
        $endOfDay = Carbon::today()->endOfDay();

        if((int) $saques_dia > 0){
            Log::debug('[SAQUE][DADOS] - SAQUES DIÁRIOS: '.$saques_dia);
            $transacoes = TransactionOut::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();
            if($transacoes >= $saques_dia){
            Log::debug('[SAQUE][DADOS]: QUANTIDADE DE SAQUES DIÁRIOS EXCEDIDO');
                return response()->json([
                'status' => 'error',
                'message' => "Quantidade de saques diários excedido. Limite de $saques_dia por dia.",
            ], 401);
            }
        }
        

        if($saque_minimo > 0 && (float) $data['amount'] < (float) $saque_minimo){
            
            $value = "R$ ".number_format($saque_minimo, 2, ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O saque mínimo é de $value.",
            ], 401);
        }

        if($saque_maximo > 0 && (float) $data['amount'] > (float) $saque_maximo){
            
            $value = "R$ ".number_format($saque_maximo, 2, ',', '.');
            return response()->json([
                'status' => 'error',
                'message' => "O saque máximo é de $value.",
            ], 401);
        }

         $adquirencia = $setting->adquirencia_pix;

        if($request->user->adquirente_default != 'padrao'){
            $adquirencia = $request->user->adquirente_default;
        }

        switch ($adquirencia) {
            case 'cashtime':
                $response = self::requestPaymentCashtime($request);
                break;
            case 'sixxpayments':
                $response = self::requestPaymentSixxpayments($request);
                break;
            case 'efi':
                $response = self::requestPaymentEfi($request);
                break;
            case 'witetec':
                $response = self::requestPaymentWitetec($request);
                break;
            default:
                $response = ['data' => ['status' => 'error', 'message' => 'Erro inesperado'], 'status' => 500];
                break;
        }
        Log::debug('[SAQUE][DADOS][RESPOSTA]', $response);
        // Se passar pela validação, processar o depósito
        return response()->json($response['data'], $response['status']);
}

public function status(Request $request)
{
    $data = $request->all();
    $status = TransactionOut::where('id', $data['id'])->first()->status;
    return response()->json(compact('status'));
}
}