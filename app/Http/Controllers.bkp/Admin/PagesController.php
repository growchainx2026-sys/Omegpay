<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Adquirente, Banner, TransactionIn, TransactionOut, Setting, User };
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DatePeriod;
use DateInterval;

class PagesController extends Controller
{
    public function dashboard(Request $request)
    {
        if (auth()->user()->permission !== 'admin') {
            return back()->route('dashboard');
        }

        $periodo = $request->input('periodo', 'dia');

        $startRequest = $request->input('start');
        $endRequest   = $request->input('end');

        if ($startRequest && $endRequest) {
            // Usando intervalo customizado
            $inicio = Carbon::parse($startRequest)->startOfDay();
            $fim    = Carbon::parse($endRequest)->endOfDay();
            $between = true;
            $periodo = 'custom';
        } else {
            // Intervalos fixos
            switch ($periodo) {
                case 'dia':
                case 'hoje':
                    $inicio = Carbon::today();
                    $fim = Carbon::now();
                    break;
                case 'semana':
                    $inicio = Carbon::now()->startOfWeek();
                    $fim = Carbon::now();
                    break;
                case 'mes':
                case 'mês':
                    $inicio = Carbon::now()->startOfMonth();
                    $fim = Carbon::now();
                    break;
                case 'tudo':
                default:
                    $inicio = null;
                    $fim = null;
                    break;
            }
            $between = $inicio && $fim;
        }

        // Clientes - sem filtro, ou filtrar se quiser
        $clientes = User::get();

        $topUsuariosDeposito = User::select(
            'users.id',
            'users.name',
            DB::raw('COALESCE(SUM(transactions_cash_in.amount), 0) as total_depositado')
        )
            ->leftJoin('transactions_cash_in', function ($join) use ($inicio, $fim) {
                $join->on('users.id', '=', 'transactions_cash_in.user_id');

                if ($inicio && $fim) {
                    $join->whereBetween('transactions_cash_in.created_at', [$inicio, $fim]);
                }
                $join->whereIn('transactions_cash_in.status', ['pago', 'revisao']);

            })
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_depositado')
            ->take(12)
            ->get();

        // Depósitos - filtro aplicado
        $depositosQuery = TransactionIn::query()->whereIn('status', ['pago', 'revisao']);
        $allDeposits = TransactionIn::query();
        if ($between) {
            $depositosQuery->whereBetween('created_at', [$inicio, $fim]);
            $allDeposits->whereBetween('created_at', [$inicio, $fim]);
        }
        $depositos = $depositosQuery->get();
        $todosDepositos = $allDeposits->get();

        // Saques - filtro aplicado
        $saquesQuery = TransactionOut::query()->whereIn('status', ['pago', 'revisao']);
        if ($between) {
            $saquesQuery->whereBetween('created_at', [$inicio, $fim]);
        }
        $saques = $saquesQuery->get();

        $saquesPendentesQuery = TransactionOut::query()->where('status', 'pendente');
        if ($between) {
            $saquesPendentesQuery->whereBetween('created_at', [$inicio, $fim]);
        }

        $saquesPendentes = $saquesPendentesQuery->sum('amount');

        $usersCadastradoQuery = User::query();
        /* if ($between) {
            $usersCadastradoQuery->whereBetween('created_at', [$inicio, $fim]);
        } */

        $usersCadastrados = $usersCadastradoQuery->count();
        // Total em carteiras (saldo) - sem filtro ou filtrar por usuários ativos?
        $total_em_carteira = User::sum('saldo');

        // Lucros (exemplo, soma simples) - ajustar conforme sua regra
        $lucrosQuery = TransactionIn::query()->whereIn('status', ['pago', 'revisao']);
        if ($between) {
            $lucrosQuery->whereBetween('created_at', [$inicio, $fim]);
        }
        // Supondo que lucro seja soma de um campo 'profit' ou calculado (exemplo):
        $lucros = $lucrosQuery->sum('taxa_cash_in') ?? 0;  // Ajuste conforme sua tabela

        // Meds (suponho que seja média, ticket médio, etc)
        // Exemplo: média do valor dos depósitos no período
        $medsQuery = TransactionIn::query()->where('status', 'refused');
        if ($between) {
            $medsQuery->whereBetween('created_at', [$inicio, $fim]);
        }
        $meds = $medsQuery->avg('amount') ?? 0;

        // Ticket médio (exemplo simples: soma dos depósitos / total de depósitos)
        $ticketQuery = TransactionIn::query()->whereIn('status', ['pago', 'revisao']);
        if ($between) {
            $ticketQuery->whereBetween('created_at', [$inicio, $fim]);
        }
        $ticket = $ticketQuery->avg('amount') ?? 0;
        // Banners ativos
        $banners = Banner::where('status', 1)->get();

        // Dados para gráfico - depósitos por hora
        $depositosHoraQuery = DB::table('transactions_cash_in')
            ->selectRaw('HOUR(created_at) as hora, SUM(amount) as total')
            ->whereIn('status', ['pago','revisao']);

        if ($between) {
            $depositosHoraQuery->whereBetween('created_at', [$inicio, $fim]);
        }

        $dados = $depositosHoraQuery->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hora')
            ->get();

        $depositos_horas = [];
        $depositos_valores = [];
        foreach ($dados as $dado) {
            $depositos_horas[] = $dado->hora . 'h';
            $depositos_valores[] = $dado->total;
        }

        // Dados para gráfico - depósitos e saques por dia
        // Define intervalo para gráfico (máximo 30 dias para "tudo")
        if ($periodo === 'tudo' || !$between) {
            $inicioGrafico = Carbon::now()->subDays(29)->startOfDay();
            $fimGrafico = Carbon::now();
        } else {
            $inicioGrafico = $inicio;
            $fimGrafico = $fim;
        }

        $transactions_cash_in = DB::table('transactions_cash_in')
            ->selectRaw("DATE(created_at) as data, SUM(amount) as total")
            ->where('status', 'pago')
            ->whereBetween('created_at', [$inicioGrafico, $fimGrafico])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'data');

        $transactions_cash_out = DB::table('transactions_cash_out')
            ->selectRaw("DATE(created_at) as data, SUM(amount) as total")
            ->where('status', 'pago')
            ->whereBetween('created_at', [$inicioGrafico, $fimGrafico])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'data');

        $transactions_in = DB::table('transactions_cash_in')
            ->selectRaw("DATE(created_at) as data, SUM(amount) as total")
            ->whereIn('status', ['pago', 'revisao'])
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'data');

        $transactions_out = DB::table('transactions_cash_out')
            ->selectRaw("DATE(created_at) as data, SUM(amount) as total")
            ->where('status', 'pago')
            ->groupBy(DB::raw("DATE(created_at)"))
            ->pluck('total', 'data');

        $dias = [];
        $valoresDepositos = [];
        $valoresSaques = [];

        // Define o período fixo (semana atual: segunda → domingo)
        $inicioSemana = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $fimSemana    = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $period = new DatePeriod($inicioSemana, new DateInterval('P1D'), $fimSemana->copy()->addDay());

        // Loop pelos 7 dias da semana
        foreach ($period as $date) {
            $dataKey = $date->format('Y-m-d'); // chave usada nos arrays de transações
            $dias[] = $date->format('d/m');    // label no gráfico
            $valoresDepositos[] = $transactions_in[$dataKey] ?? 0;
            $valoresSaques[]    = $transactions_out[$dataKey] ?? 0;
        }
        $saldos = User::sum('saldo');

        $usuariosPendentes = User::where('status', 'pendente')->count();
        // Passar para a view
        return view('pages.admin.dashboard', compact(
            'saldos',
            'banners',
            'clientes',
            'depositos',
            'saques',
            'todosDepositos',
            'saquesPendentes',
            'total_em_carteira',
            'lucros',
            'meds',
            'ticket',
            'depositos_horas',
            'depositos_valores',
            'dias',
            'valoresDepositos',
            'valoresSaques',
            'periodo',
            'usersCadastrados',
            'usuariosPendentes',
            'topUsuariosDeposito'
        ));
    }

    public function balance()
    {
        if(auth()->user()->permission !== 'admin'){
            return back()->route('dashboard');
        }

       
        $users = User::get();

        return view('pages.admin.balance', compact('users'));
    }

    public function customization(Request $request)
    {
        $settings = Setting::first();
        return view('pages.admin.customization', compact('settings'));
    }
    public function taxas(Request $request)
    {
         $settings = Setting::first();
        return view('pages.admin.taxas', compact('settings'));
    }

}
