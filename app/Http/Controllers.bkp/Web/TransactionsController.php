<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\TransactionIn;
use App\Models\TransactionOut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Traits\{CashtimeTrait, SixxpaymentsTrait, EfiTrait, WitetecTrait, PagarmeTrait};

class TransactionsController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->input('status');
        $dataInicial = $request->input('data_inicial');
        $dataFinal = $request->input('data_final');
        $origem = $request->input('origem');
        $aplicacao = $request->input('aplicacao'); // plataforma
        $descricaoOuId = $request->input('descricao');

        $cashInQuery = DB::table('transactions_cash_in')
            ->where('user_id', auth()->id())
            ->select(
                'created_at as data',
                'idTransaction as codigo',
                DB::raw("'Depósito' as tipo"),
                'status',
                'amount as valor_solicitado',
                'taxa_cash_in as taxas',
                'executor_ordem as split',
                'cash_in_liquido as valor',
                DB::raw("'entrada' as movimentado"),
                'request_domain as origem',
                'descricao_transacao as description'
            );

        $cashOutQuery = DB::table('transactions_cash_out')
            ->where('user_id', auth()->id())
            ->select(
                'created_at as data',
                'idTransaction as codigo',
                DB::raw("'Saque' as tipo"),
                'status',
                'amount as valor_solicitado',
                'taxa_cash_out as taxas',
                DB::raw("'' as split"),
                'cash_out_liquido as valor',
                DB::raw("'saída' as movimentado"),
                'request_domain as origem',
                'type as description'
            );

        // Filtros
        foreach ([$cashInQuery, $cashOutQuery] as $query) {
            if ($status && $status !== 'Todos') {
                $query->where('status', $status);
            }

            if ($dataInicial) {
                $query->whereDate('data', '>=', Carbon::parse($dataInicial));
            }

            if ($dataFinal) {
                $query->whereDate('data', '<=', Carbon::parse($dataFinal));
            }

            if ($origem && $origem !== 'Todos') {
                $query->where('request_domain', 'like', "%$origem%");
            }

            if ($aplicacao && $aplicacao !== 'Todos') {
                $query->where('plataforma', 'like', "%$aplicacao%");
            }

            if ($descricaoOuId) {
                $query->where(function ($q) use ($descricaoOuId) {
                    $q->where('idTransaction', 'like', "%$descricaoOuId%")
                        ->orWhere('descricao_transacao', 'like', "%$descricaoOuId%")
                        ->orWhere('type', 'like', "%$descricaoOuId%");
                });
            }
        }

        $transacoes = $cashInQuery->unionAll($cashOutQuery)
            ->orderByDesc('data')
            ->get();

        return response()->json(['data' => $transacoes]);
    }

    public function extratoDepositos(Request $request)
    {
        $periodo = $request->input('periodo', 'dia');
        $user = auth()->user();
        $transactionsIn = $user->transactions_in();

        switch ($periodo) {
            case 'dia':
                $from = Carbon::today();
                $to = Carbon::now();
                break;
            case 'semana':
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now();
                break;
            case 'mes':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now();
                break;
            case 'todos':
            default:
                $from = null;
                $to = null;
                break;
        }

        if ($from && $to) {
            $transactionsIn = $transactionsIn->whereBetween('created_at', [$from, $to]);
        }

        $pagos = (clone $transactionsIn)->where('status', 'pago')->sum('amount');
        $totalPagos = (clone $transactionsIn)->where('status', 'pago')->count();

        $pendentes = (clone $transactionsIn)->where('status', 'pendente')->sum('amount');
        $totalPendentes = (clone $transactionsIn)->where('status', 'pendente')->count();

        $ticketMedio = (clone $transactionsIn)->where('status', 'pago')->avg('amount');
        $totalTicketMedio = (clone $transactionsIn)->where('status', 'pago')->count();

        $taxas = (clone $transactionsIn)->where('status', 'pago')->sum('taxa_cash_in');
        $totalTaxas = (clone $transactionsIn)->where('status', 'pago')->count();

        $depositos = (clone $transactionsIn->get());

        return view('pages.extrato-depositos', compact(
            'periodo',
            'pagos',
            'totalPagos',
            'pendentes',
            'totalPendentes',
            'ticketMedio',
            'totalTicketMedio',
            'taxas',
            'totalTaxas',
            'depositos',
        ));
    }

    public function extratoSaques(Request $request)
    {
        $periodo = $request->input('periodo', 'dia');
        $user = auth()->user();
        $transactionsOut = $user->transactions_out();

        switch ($periodo) {
            case 'dia':
                $from = Carbon::today();
                $to = Carbon::now();
                break;
            case 'semana':
                $from = Carbon::now()->startOfWeek();
                $to = Carbon::now();
                break;
            case 'mes':
                $from = Carbon::now()->startOfMonth();
                $to = Carbon::now();
                break;
            case 'todos':
            default:
                $from = null;
                $to = null;
                break;
        }

        if ($from && $to) {
            $transactionsOut = $transactionsOut->whereBetween('created_at', [$from, $to]);
        }

        $pagos = (clone $transactionsOut)->where('status', 'pago')->sum('amount');
        $totalPagos = (clone $transactionsOut)->where('status', 'pago')->count();

        $pendentes = (clone $transactionsOut)->where('status', 'pendente')->sum('amount');
        $totalPendentes = (clone $transactionsOut)->where('status', 'pendente')->count();

        $recusados = (clone $transactionsOut)->where('status', 'cancelado')->avg('amount');
        $totalRecusados = (clone $transactionsOut)->where('status', 'cancelado')->count();

        $saques = (clone $transactionsOut->get());

        return view('pages.extrato-saques', compact(
            'periodo',
            'pagos',
            'totalPagos',
            'pendentes',
            'totalPendentes',
            'recusados',
            'totalRecusados',
            'saques',
        ));
    }

    public function depositoWeb(Request $request)
    {
        $data = $request->all();

        $data['cash_in_liquido'] = (float) str_replace(['R$ ', ','], ['', '.'], $data['cash_in_liquido']);
        $data['amount'] = (float) $data['amount'];

        $user = User::where('id', auth()->id())->first();

        $newrequest = new Request([
            'token' => $user->token,
            'secret' => $user->secret,
            'amount' => $data['amount'],
            'debtor_name' => $user->name,
            'email' => $user->email,
            'debtor_document_number' => $user->cpf_cnpj,
            'phone' => $user->telefone,
            'method_pay' => "pix",
            'postback' => "web",
            'user' => $user
        ]);

        $setting = Setting::first();
        $deposito_minimo = $setting->deposito_minimo; 
        $deposito_maximo = $setting->deposito_maximo; 

        if($deposito_minimo > 0 && (float) $data['amount'] < (float) $deposito_minimo){
            $value = "R$ ".number_format($deposito_minimo, 2, ',', '.');
            return back()->with('error', "O depósito mínimo é de $value.");
        }

        if($deposito_maximo > 0 && (float) $data['amount'] > (float) $deposito_maximo){
            $value = "R$ ".number_format($deposito_maximo, 2, ',', '.');
            return back()->with('error', "O depósito máximo é de $value.");
        }

        $adquirencia = $setting->adquirencia_pix;

        if ($user->adquirente_default != 'padrao') {
            $adquirencia = $request->user->adquirente_default;
        }

        switch ($adquirencia) {
            case 'cashtime':
                $response = CashtimeTrait::requestDepositCashtime($newrequest);
                ;
                break;
            case 'sixxpayments':
                $response = SixxpaymentsTrait::requestDepositSixxpayments($newrequest);
                break;
            case 'efi':
                $response = EfiTrait::requestDepositEfi($newrequest);
                break;
            case 'witetec':
                $response = WitetecTrait::requestDepositWitetec($newrequest);
                break;
            case 'pagarme':
                $response = PagarmeTrait::requestDepositPagarme($newrequest);
                break;
            default:
                $response = ['data' => ['status' => 'error', 'message' => 'Erro inesperado'], 'status' => 500];
                break;
        }
        //dd($response);
        $qrcode = $response['data']['qrcode'];
        $qrimage = $response['data']['qr_code_image_url'];
        $amount = "R$ " . number_format($data['amount'], 2, ',', '.');
        $success = "QrCode gerado com sucesso.";


        return back()->with([
            'qrcode' => $qrcode,
            'qr_code_image_url' => $qrimage,
            'amount' => $amount,
            'success' => $success
        ])->withInput(); // Isso limpará a sessão após a primeira exibição
    }

    public function transferirSaldo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo_referencia' => 'required|string',
            'amount' => 'required|numeric|min:0|max:' . auth()->user()->saldo,
        ]);

        if ($validator->fails()) {
            $errorsArray = $validator->errors()->toArray();
            if ($errorsArray['codigo_referencia']) {
                $errorsArray['codigo_referencia'][0] = "Digite o id do usuário.";
            }
            if ($errorsArray['amount']) {
                $errorsArray['amount'][0] = "Digite um valor.";
            }

            Cookie::queue('form_errors', json_encode($errorsArray), 5); // expira em 5 minutos
            return redirect()->back()->withInput();
        }
        $data = $request->only(['codigo_referencia', 'amount']);
        $userRecebedor = User::where('codigo_referencia', $data['codigo_referencia'])->first();
        //dd($user);
        if (!$userRecebedor) {
            $error = [];
            $error['codigo_referencia'][0] = "Usuário não encontrado. Verifique e tente novamente.";
            Cookie::queue('form_errors', json_encode($error), 5); // expira em 5 minutos
            return redirect()->back()->withInput();
        }

        $novosaldoauth = (float) auth()->user()->saldo - (float) $data['amount'];
        $novosaldorecebedor = (float) $userRecebedor->saldo + (float) $data['amount'];
        $userRecebedor->update(['saldo' => $novosaldorecebedor]);

        $userAuth = User::where('id', auth()->id())->first();
        $userAuth->update(['saldo' => $novosaldoauth]);

        $saldotransferido = "R$ " . number_format($data['amount'], '2', ',', '.');
        $nomesacado = explode(' ', $userRecebedor->name)[0];
        return redirect()->back()->with('success', "$saldotransferido foi enviado para $nomesacado com sucesso.");
    }

    public function saquePix(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|string',
            'pixKey' => 'required|string',
            'pixKeyType' => 'required|string' //|in:cpf,email,telefone,aleatoria
        ], [
            // Mensagens de erro personalizadas
            'required' => 'Este campo é obrigatório',
            'string' => 'Este campo deve ser uma string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('id', auth()->id())->first();

        $data['amount'] = str_replace(',', '.', $data['amount']);
        $data['amount'] = str_replace(['R$', ' '], '', $data['amount']);
        $data['amount'] = (float) $data['amount'];

        $newrequest = new Request([
            'token' => $user->clientId,
            'secret' => $user->secret,
            'amount' => $data['amount'],
            'pixKey' => $data['pixKey'],
            'pixKeyType' => $data['pixKeyType'],
            'baasPostbackUrl' => "web",
            'user' => $user
        ]);


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
                return back()->with('error', "Quantidade de saques diários excedido. Limite de $saques_dia por dia.");
            }
        }
        

        if($saque_minimo > 0 && (float) $data['amount'] < (float) $saque_minimo){
            
            $value = "R$ ".number_format($saque_minimo, 2, ',', '.');
            return back()->with('error', "O saque mínimo é de $value.");
        }

        if($saque_maximo > 0 && (float) $data['amount'] > (float) $saque_maximo){
            
            $value = "R$ ".number_format($saque_maximo, 2, ',', '.');
            return back()->with('error', "O saque máximo é de $value.");
        }

        $adquirencia = Setting::first()->adquirencia;
        switch ($adquirencia) {
            case 'cashtime':
                $response = CashtimeTrait::requestPaymentCashtime($newrequest);
                break;
            case 'sixxpayments':
                $response = SixxpaymentsTrait::requestPaymentSixxpayments($newrequest);
                break;
            case 'efi':
                $response = EfiTrait::requestPaymentEfi($newrequest);
                break;
            case 'witetec':
                $response = WitetecTrait::requestPaymentWitetec($newrequest);
                break;
            case 'pagarme':
                $response = PagarmeTrait::requestPaymentPagarme($newrequest);
                break;
            default:
                $response = ['data' => ['status' => 'error', 'message' => 'Erro inesperado'], 'status' => 500];
                break;
        }

        if (isset($response['status']) && $response['status'] == 200) {
            return back()->with('success', "Saque realizado com sucesso.");
        } else {
            return back()->withErrors(['error' => $response['message'] ?? 'Erro ao realizar o saque.']);
        }
    }
}