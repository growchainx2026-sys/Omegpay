<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use App\Models\Banner;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->status === 'aguardando' || auth()->user()->status === 'analise') {
            return redirect()->route('auth.enviar-docs');
        } elseif (auth()->user()->permission === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            $periodo = $request->input('periodo', 'dia');
            $periodoPersonalizado = $request->input('periodo_personalizado');
            $user = auth()->user();

            $transactionsInQuery = $user->transactions_in();
            $transactionsOutQuery = $user->transactions_out();

            // Inicializar datas
            $from = null;
            $to = null;

            // Definir intervalo conforme o período
            switch ($periodo) {
                case 'dia':
                    $from = Carbon::today();
                    $to = Carbon::now();
                    break;
                case 'semana':
                    $from = Carbon::now()->startOfWeek(Carbon::MONDAY);
                    $to = Carbon::now();
                    break;
                case 'mes':
                    $from = Carbon::now()->startOfMonth();
                    $to = Carbon::now();
                    break;

                case 'personalizado':
                    if (!empty($periodoPersonalizado) && str_contains($periodoPersonalizado, ' - ')) {
                        list($start, $end) = explode(' - ', $periodoPersonalizado);

                        try {
                            $from = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                            $to = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();
                        } catch (\Exception $e) {
                            $periodo = 'dia';
                            $from = Carbon::today();
                            $to = Carbon::now();
                        }
                    } else {
                        $periodo = 'dia';
                        $from = Carbon::today();
                        $to = Carbon::now();
                    }
                    break;

                case 'tudo':
                default:
                    $from = null;
                    $to = null;
                    break;
            }

            // Aplicar filtros por data se necessário
            if ($from && $to) {
                $transactionsInQuery = $transactionsInQuery->whereBetween('created_at', [$from, $to]);
                $transactionsOutQuery = $transactionsOutQuery->whereBetween('created_at', [$from, $to]);
            }

            // Obter os registros agora (apenas uma vez)
            $transactionsIn = $transactionsInQuery->get();
            $transactionsOut = $transactionsOutQuery->get();

            /*
            |--------------------------------------------------------------------------
            | Lógica para o Gráfico de Linhas/Barras (Agrupamento por Dia)
            |--------------------------------------------------------------------------
            */

            // 1. Definir o período para o gráfico com base no filtro selecionado
            if ($from && $to && $periodo !== 'tudo') {
                $startDate = $from->copy()->startOfDay();
                $endDate = $to->copy()->endOfDay();
            } else {
                // Padrão: Últimos 7 dias (para o gráfico de linha/barra)
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
            }

            $period = CarbonPeriod::create($startDate, $endDate);

            // 2. Inicializar coleções de dias com zero
            $valoresDepositos = collect();
            $valoresSacados = collect();

            foreach ($period as $date) {
                $label = $date->format('d/m');
                $valoresDepositos[$label] = 0;
                $valoresSacados[$label] = 0;
            }

            // 3. Agrupar transações por data
            $agrupadosDepositos = $transactionsIn->where('status', 'pago')->groupBy(function ($item) {
                return $item->created_at->format('d/m');
            })->map->sum('amount');

            $agrupadosSaques = $transactionsOut->where('status', 'pago')->groupBy(function ($item) {
                return $item->created_at->format('d/m');
            })->map->sum('amount');

            // 4. Mesclar valores reais com os dias padrão
            $valoresDepositos = $valoresDepositos->merge($agrupadosDepositos);
            $valoresSacados = $valoresSacados->merge($agrupadosSaques);

            // 5. Preparar dados finais
            $dias = $valoresDepositos->keys()->toArray();
            $valores = $valoresDepositos->values()->toArray();
            $valoresSaque = $valoresSacados->values()->toArray();

            $banners = Banner::where('status', 1)->get();

            // ✅ CORREÇÃO: Adicionar variáveis que faltam para a view
            $depositos = $transactionsIn;
            $saques = $transactionsOut;
            $saldos = $transactionsIn->whereIn('status', ['pago', 'revisao'])->sum('amount') - $transactionsOut->where('status', 'pago')->sum('amount');
            $saquesPendentes = $transactionsOut->where('status', 'pendente')->sum('amount');
            $usersCadastrados = \App\Models\User::where('permission', '!=', 'admin')->count();

            return view('pages.dashboard', [
                'banners' => $banners,
                'periodo' => $periodo,
                'periodo_personalizado' => $periodoPersonalizado,
                'dias' => $dias,
                'valoresDepositos' => $valores,
                'valoresSacados' => $valoresSaque,
                'transactionsIn' => $transactionsIn,
                'transactionsOut' => $transactionsOut,
                // ✅ Variáveis corrigidas
                'depositos' => $depositos,
                'saques' => $saques,
                'saldos' => $saldos,
                'saquesPendentes' => $saquesPendentes,
                'usersCadastrados' => $usersCadastrados,
            ]);
        }
    }
}