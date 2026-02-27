<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\Models\Banner;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        if (config('app.login_maintenance', false)) {
            return view('pages.login-maintenance');
        }

        if (auth()->check()) {
            if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
                return redirect()->route('auth.enviar-docs');
            }


            return redirect()->route('dashboard');
        }
        return view('pages.login');
    }

    public function extrato(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }

        $tipo = $request->input('tipo');
        $status = $request->input('status');
        $dataInicial = $request->input('data_inicial');
        $dataFinal = $request->input('data_final');

        $query = null;

        if ($tipo === 'cash_out') {
            $query = DB::table('transactions_cash_out')
                ->where('user_id', auth()->id())
                ->select(
                    DB::raw("'Saque' as tipo"),
                    'created_at',
                    'external_id',
                    'status',
                    'amount',
                    DB::raw('taxa_cash_out + taxa_fixa as taxas'),
                    DB::raw("'' as split"),
                    'cash_out_liquido as valor',
                    'request_domain as movimentado',
                    'plataforma as origem',
                    'pixKey as description'
                );
        } else {
            $query = DB::table('transactions_cash_in')
                ->where('user_id', auth()->id())
                ->select(
                    DB::raw("'Depósito' as tipo"),
                    'created_at',
                    'external_id',
                    'status',
                    'amount',
                    DB::raw('taxa_cash_in + taxa_fixa as taxas'),
                    'executor_ordem as split',
                    'cash_in_liquido as valor',
                    'request_domain as movimentado',
                    'adquirente_ref as origem',
                    'descricao_transacao as description'
                );
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dataInicial) {
            $query->whereDate('created_at', '>=', $dataInicial);
        }

        if ($dataFinal) {
            $query->whereDate('created_at', '<=', $dataFinal);
        }

        $registros = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        if ($registros->isEmpty()) {
            $registros = collect();
        }

        // Enviar resposta JSON

        return view('pages.extrato', compact('registros'));
    }
    public function deposito(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        Helper::calculaSaldoLiquido(auth()->id());
        return view('pages.deposito');
    }
    public function depositoPix(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        return view('pages.deposito-pix');
    }
    public function transferencia(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        Helper::calculaSaldoLiquido(auth()->id());

        $banners = Banner::where('status', 1)->get();
        return view('pages.transferencia', compact('banners'));
    }
    public function saqueCopiaCola(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        return view('pages.saque-copia-cola');
    }
    public function transferBalance(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        return view('pages.transfer-balance');
    }
    public function aproveWithdraw(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        return view('pages.aprove-withdraw');
    }
    public function accountView(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        return view('pages.account-view');
    }
    public function wallet(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }
        Helper::calculaSaldoLiquido(auth()->id());
        return view('pages.wallet');
    }


    public function whitelist(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        }

        return view('pages.whitelist');
    }

    public function enviarDocs(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return view('pages.enviar-docs');
        }
        return redirect()->route('dashboard');
    }

    public function integracoes(Request $request)
    {
        return view('pages.integracoes');
    }

    public function infracoes(Request $request)
    {
        return view('pages.infracoes');
    }

    public function afiliate(Request $request)
    {
        // Gera código de referência se não existir
        if (!auth()->user()->codigo_referencia) {
            $reference = uniqid();
            User::where('id', auth()->user()->id)->update(['codigo_referencia' => $reference]);
            auth()->user()->codigo_referencia = $reference;
        }

        $periodo = $request->input('periodo', 'dia');
        $user = auth()->user();

        // Define o intervalo de datas
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
            case 'tudo':
            default:
                $from = null;
                $to = null;
                break;
        }

        $splits = $user->transactions_in->where('type', 'split')->where('status', 'pago');
        $indicados = $user->indicados;
        $setting = Setting::first();

        if ($from && $to) {
            $splitsFiltrados = $splits->whereBetween('created_at', [$from, $to]);
        } else {
            $splitsFiltrados = $splits;
        }

        $totalGanhos = $splitsFiltrados->sum('amount');

        return view('pages.afiliate', compact('indicados', 'totalGanhos', 'splits'));
    }


    public function customIndex(Request $request)
    {
        return view('pages.customization');
    }
    public function customUpdate(Request $request)
    {
        $user = auth()->user();
        $isAdmin = in_array($user->permission, ['admin', 'dev']);

        // Clientes podem alterar apenas a cor principal
        if ($request->has('software_color')) {
            $user->software_color = $request->input('software_color');
        }

        // Apenas admins podem alterar outras cores e imagens
        if ($isAdmin) {
            if ($request->has('software_color_background')) {
                $user->software_color_background = $request->input('software_color_background');
            }
            if ($request->has('software_color_sidebar')) {
                $user->software_color_sidebar = $request->input('software_color_sidebar');
            }
            if ($request->has('software_color_text')) {
                $user->software_color_text = $request->input('software_color_text');
            }

            $imageFields = ['logo_light', 'favicon_light'];

            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    // Faz o upload da nova imagem com um nome único
                    $image = $request->file($field);
                    $imageName = $field . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('images', $imageName, 'public');

                    $user->$field = '/' . $imagePath;
                }
            }
        }

        $user->save();
        $user->fresh();

        return back()->with('success', 'Customizações realizadas com sucesso!');
    }



}
