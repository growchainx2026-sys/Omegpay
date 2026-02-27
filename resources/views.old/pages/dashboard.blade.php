@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        use Carbon\Carbon;
        // Assume que $periodo está sendo passado do Controller (ex: 'dia', 'mes', 'personalizado')
        // Assume que $periodo_personalizado (ex: '01/05/2024 - 15/05/2024') está sendo passado via request

        // Inicializa a variável $text
        $text = 'Hoje';
        if ($periodo == 'mes') {
            $text = 'Mês';
        } elseif ($periodo == 'semana') {
            $text = 'Semana';
        } elseif ($periodo == 'tudo') {
            $text = 'Tudo';
        } elseif ($periodo == 'personalizado') {
            $text = 'Personalizado';
        }
    @endphp

    <div class="row">
        <div class="col-12 mb-3">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        <div class="swiper-slide">
                            <img src="/storage/{{ $banner->image }}" class="img-fluid w-100" style="border-radius:10px;"
                                alt="Imagem">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>

    <div class="header mt-3 mb-3 d-flex justify-content-between">
        <h1 class="header-title">
            Dashboard
        </h1>
        <form id="form-filter" action="{{ route('dashboard') }}" method="GET">
            <div class="d-flex align-items-center">
                
                {{-- O INPUT de Data --}}
                <input type="text" 
                    name="periodo_personalizado" 
                    id="custom-period-input" 
                    value="{{ request('periodo_personalizado') }}" {{-- Manter o valor no input --}}
                    class="form-control w-auto me-2" 
                    placeholder="dd/mm/aaaa - dd/mm/aaaa"
                    style="display: none; border-radius: 10px; width: 250px;"> 

                <select name="periodo" 
                        class="form-select w-auto" 
                        onchange="handlePeriodChange(this.value)"
                        style="border-radius: 10px;">
                    
                    <option value="tudo" {{ $periodo == 'tudo' ? 'selected' : '' }}>Tudo</option>
                    <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
                    <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
                    <option value="dia" {{ $periodo == 'dia' ? 'selected' : '' }}>Dia</option>
                    
                    <option value="personalizado" {{ $periodo == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                </select>
            </div>
        </form>
    </div>

    <div class="row g-3">

        <div class="col-md-6 col-xl-4">
            <div class="card card-dash border-left-card p-4"
                style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <p class="mb-n2">Saldo disponível</p>
                <div class="fs-3 text-bold text-gateway">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}
                    <p class="text-muted" style="font-size: 14px;font-weight:300;">A liberar: R$
                        {{ number_format(auth()->user()->saldo_a_liberar ?? 0, 2, ',', '.') }}</p>
                    <p class="text-muted mt-n3" style="font-size: 14px;font-weight:300;">Reserva: R$
                        {{ number_format(auth()->user()->saldo_reserva ?? 0, 2, ',', '.') }}</p>
                </div>
                <a href="/financeiro">
                    <button class="btn btn-primary btn-sm mt-2 w-100">Sacar</button>
                </a>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card card-dash border-card-dash p-4"
                style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <p class="mb-1 title-">Total de Vendas ({{ $text }})</p>
                <div class="fs-3 text-bold text-gateway">R$
                    {{ number_format((clone $transactionsIn)->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                </div>
                <small class="d-block mb-1">Número de vendas:
                    {{ (clone $transactionsIn)->whereIn('status', ['pago', 'revisao'])->count() }}</small>
                @php
                    $totalVendas = (clone $transactionsIn)->whereIn('status', ['pago', 'revisao'])->sum('amount') ?? 0;
                    $countVendas = (clone $transactionsIn)->whereIn('status', ['pago', 'revisao'])->count() ?? 0;
                    $ticketMedio = $countVendas > 0 ? $totalVendas / $countVendas : 0;
                @endphp
                <small class="d-block">Ticket Médio: R$ {{ number_format($ticketMedio, 2, ',', '.') }}</small>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 ">
            <div class="card card-dash border-card-dash px-4 pt-4"
                style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex flex-column mb-0">
                    <p class="mb-3">Formas de Pagamento ({{ $text }})</p>
                    <table class="table table-sm mb-0" style="margin-top: -0.5rem;">
                        <tbody>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Cartão</td>
                                <td class="text-end">
                                    {{ (clone $transactionsIn)->where('method', 'card')->whereIn('status', ['pago', 'revisao'])->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $transactionsIn)->where('method', 'card')->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Pix</td>
                                <td class="text-end">
                                    {{ (clone $transactionsIn)->where('method', 'pix')->where('status', 'pago')->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $transactionsIn)->where('method', 'pix')->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Boleto</td>
                                <td class="text-end">
                                    {{ (clone $transactionsIn)->where('method', 'billet')->whereIn('status', ['pago', 'revisao'])->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $transactionsIn)->where('method', 'billet')->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card card-dash p-4">
                <p class="mb-1">Reembolsos ({{ $text }})</p>
                <div class="mb-1 d-flex justify-content-between">
                    <span>Estornos</span> <span class="tbold">R$ 0,00</span>
                </div>
                <div class="mb-1 d-flex justify-content-between">
                    <span>Chargeback</span> <span class="tbold">R$ 0,00</span>
                </div>
                <div class="mb-1 d-flex justify-content-between">
                    <span>Taxa de Estorno</span> <span class="tbold">0.0%</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card card-dash p-1 d-flex flex-column justify-content-center">
                <p class="mb-3 text-center fw-semibold">Taxa de Aprovação ({{ $text }})</p>
                <div class="chart-row-taxas d-flex justify-content-around align-items-center text-center">
                    <div class="chart-item-taxas">
                        <div id="apex-cartao" class="chart-taxas"></div>
                        <small>Cartão</small>
                    </div>
                    <div class="chart-item-taxa">
                        <div id="apex-pix" class="chart-taxas"></div>
                        <small>Pix</small>
                    </div>
                    <div class="chart-item">
                        <div id="apex-boleto" class="chart-taxas"></div>
                        <small>Boleto</small>
                    </div>
                </div>
            </div>
        </div>

        @php
            // As variáveis $vendasAtual, $vendasAnterior e $crescimento já deveriam vir do Controller
            // mas mantemos a lógica aqui para fins de correção do Blade.
            
            $vendasAtual = 0;
            $vendasAnterior = 0;
            $crescimento = 0;

            switch ($periodo) {
                case 'personalizado':
                    // Verifica se o campo personalizado foi enviado e tem o formato esperado
                    $periodo_personalizado = request('periodo_personalizado');
                    if (!empty($periodo_personalizado) && str_contains($periodo_personalizado, ' - ')) {
                        list($start, $end) = explode(' - ', $periodo_personalizado);
                        
                        try {
                            $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                            $endDate = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();
                            
                            // 1. Vendas atuais (período personalizado)
                            // $transactionsIn já deve ser filtrado no Controller, mas aqui garantimos o cálculo
                            $vendasAtual = auth()->user()
                                ->transactions_in()
                                ->whereIn('status', ['pago', 'revisao'])
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->sum('amount') ?? 0;

                            // 2. Período anterior (Calcula o delta de tempo e aplica ao passado)
                            $diffInDays = $endDate->diffInDays($startDate) + 1;
                            
                            $periodoAnteriorFim = $startDate->copy()->subSecond()->endOfDay();
                            $periodoAnteriorInicio = $periodoAnteriorFim->copy()->subDays($diffInDays - 1)->startOfDay();

                            $vendasAnterior = 
                                auth()
                                    ->user()
                                    ->transactions_in()
                                    ->whereIn('status', ['pago', 'revisao'])
                                    ->whereBetween('created_at', [$periodoAnteriorInicio, $periodoAnteriorFim])
                                    ->sum('amount') ?? 0;

                            if ($vendasAnterior > 0) {
                                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                                $crescimento = 100;
                            } else {
                                $crescimento = 0;
                            }

                        } catch (\Exception $e) {
                            // Erro de formato de data
                        }
                    }
                    break;
                case 'tudo':
                    $vendasAtual = auth()->user()->transactions_in()->whereIn('status', ['pago', 'revisao'])->sum('amount') ?? 0;
                    $vendasAnterior = 0;
                    $crescimento = $vendasAtual > 0 ? 100 : 0;
                    break;

                case 'mes':
                    $mesAtualInicio = Carbon::now()->startOfMonth();
                    $mesAtualFim = Carbon::now()->endOfMonth();
                    $mesPassadoInicio = Carbon::now()->subMonth()->startOfMonth();
                    $mesPassadoFim = Carbon::now()->subMonth()->endOfMonth();

                    $vendasAnterior =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100; 
                    } else {
                        $crescimento = 0;
                    }
                    break;

                case 'semana':
                    $semanaAtualInicio = Carbon::now()->startOfWeek();
                    $semanaAtualFim = Carbon::now()->endOfDay(); // até hoje
                    $semanaPassadaInicio = Carbon::now()->subWeek()->startOfWeek();
                    $semanaPassadaFim = Carbon::now()->subWeek()->endOfWeek();

                    $vendasAnterior =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100;
                    } else {
                        $crescimento = 0;
                    }
                    break;

                case 'dia':
                    $diaAtualInicio = Carbon::now()->startOfDay();
                    $diaAtualFim = Carbon::now()->endOfDay();
                    $diaPassadoInicio = Carbon::now()->subDay()->startOfDay();
                    $diaPassadoFim = Carbon::now()->subDay()->endOfDay();

                    $vendasAnterior =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        auth()
                            ->user()
                            ->transactions_in()
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100;
                    } else {
                        $crescimento = 0;
                    }
                    break;

                default:
                    $crescimento = 0;
                    break;
            }
        @endphp

        <div class="col-md-6 col-xl-4">
            <div class="card card-dash p-4">
                <p class="mb-1">Crescimento de Vendas ({{ $text }})</p>
                <div class="text-gateway fs-4">{{ number_format($crescimento, 2, ',', '.') }}%<span class="text-danger"><i
                            data-lucide="{{ $crescimento > 0 ? 'arrow-up' : ($crescimento < 0 ? 'arrow-down' : '') }}"
                            style="stroke: {{ $crescimento > 0 ? 'green' : 'red' }} !important;"></i></span></div>
                <div class="d-flex justify-content-between mb-n2">
                    <small class="fs-6">Crescimento</small>
                    <small class="fs-6 t900"> R$
                        {{ number_format(($vendasAtual ?? 0) - ($vendasAnterior ?? 0), 2, ',', '.') }}</small>
                </div>
                <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-gateway w-100"></div>
                </div>
                <div class="d-flex justify-content-between mt-n2">
                    <small class="fs-6"></small>
                    <small>{{ number_format($crescimento, 2, ',', '.') }}%</small>
                </div>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="card card-dash p-4">
                <div class="mb-n3">
                    <p class="mb-n1">Gráfico de Receita ({{ $text }})</p>
                </div>
                <div id="apex-receita"></div>
            </div>
        </div>

    </div>

    @php
        // Garantir que $transactionsIn seja a query filtrada do Controller.
        // Se ela não for, o cálculo abaixo estará incorreto.
        
        $cardPaid = (clone $transactionsIn)->where('method', 'card')->whereIn('status', ['pago', 'revisao'])->count();
        $cardTotal = (clone $transactionsIn)->where('method', 'card')->count();
        $txCard = $cardTotal > 0 ? ($cardPaid / $cardTotal) * 100 : 0;

        $billetPaid = (clone $transactionsIn)->where('method', 'billet')->whereIn('status', ['pago', 'revisao'])->count();
        $billetTotal = (clone $transactionsIn)->where('method', 'billet')->count();
        $txBillet = $billetTotal > 0 ? ($billetPaid / $billetTotal) * 100 : 0;

        $pixPaid = (clone $transactionsIn)->where('method', 'pix')->where('status', 'pago')->count();
        $pixTotal = (clone $transactionsIn)->where('method', 'pix')->count();
        $txPix = $pixTotal > 0 ? ($pixPaid / $pixTotal) * 100 : 0;
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function renderRadialChart(id, value) {
            var options = {
                chart: {
                    height: 150,
                    type: "radialBar",
                    sparkline: {
                        enabled: true
                    }
                },
                series: [value],
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "50%"
                        },
                        track: {
                            background: "#e9ecef",
                            strokeWidth: "100%"
                        },
                        dataLabels: {
                            name: {
                                show: false
                            },
                            value: {
                                fontSize: "14px",
                                fontWeight: "bold",
                                offsetY: 5,
                                formatter: function (val) {
                                    return val + "%";
                                }
                            }
                        }
                    }
                },
                stroke: {
                    lineCap: "round"
                },
                colors: [
                    getComputedStyle(document.documentElement)
                        .getPropertyValue('--gateway-primary-color')
                        .trim()
                ]
            };

            var chart = new ApexCharts(document.querySelector(id), options);
            chart.render();
        }

        document.addEventListener('DOMContentLoaded', function () {
            renderRadialChart("#apex-cartao", Number("{{ number_format($txCard, 2) }}"));
            renderRadialChart("#apex-pix", Number("{{ number_format($txPix, 2) }}"));
            renderRadialChart("#apex-boleto", Number("{{ number_format($txBillet, 2) }}"));
        });
    </script>

    @php
    // Nomes fixos dos dias (segunda → domingo)
    $daysOfWeek = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];

    // Inicializa arrays
    $aprovadas = [];
    $pendentes = [];
    $revisao = []; // Corrigido o caractere invisível aqui

    $labels = [];
    
    switch ($periodo) {
        case 'personalizado':
             $periodo_personalizado = request('periodo_personalizado');
             if (!empty($periodo_personalizado) && str_contains($periodo_personalizado, ' - ')) {
                list($start, $end) = explode(' - ', $periodo_personalizado);
                
                try {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($start))->startOfDay();
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($end))->endOfDay();
                
                    $transactions = auth()->user()
                        ->transactions_in()
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get();
                        
                    // Cria labels para cada dia do range
                    $labels = [];
                    $current = $startDate->copy();
                    while ($current->lte($endDate)) {
                        $labels[] = $current->format('d/m');
                        $current->addDay();
                    }

                    $aprovadas = $pendentes = $revisao = array_fill(0, count($labels), 0);
                    
                    foreach ($transactions as $t) {
                        $dayIndex = Carbon::parse($t->created_at)->startOfDay()->diffInDays($startDate->startOfDay()); // Calcula o índice a partir do dia inicial

                        if (isset($labels[$dayIndex])) { // Garante que o índice é válido
                            switch ($t->status) {
                                case 'pago':
                                    $aprovadas[$dayIndex] += (float)$t->amount;
                                    break;
                                case 'revisao':
                                    $revisao[$dayIndex] += (float)$t->amount;
                                    break;
                                default:
                                    $pendentes[$dayIndex] += (float)$t->amount;
                                    break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Logs error
                }
            }
            break;

        case 'dia':
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $transactions = auth()->user()
                ->transactions_in()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $labels = [Carbon::now()->format('d/m')];
            $aprovadas[] = $revisao[] = $pendentes[] = 0;
            foreach ($transactions as $t) {
                switch ($t->status) {
                    case 'pago': $aprovadas[0] += (float) $t->amount; break;
                    case 'revisao': $revisao[0] += (float) $t->amount; break;
                    default: $pendentes[0] += (float) $t->amount; break;
                }
            }
            break;

        case 'semana':
            $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $endDate= Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            $transactions = auth()->user()
                ->transactions_in()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $labels = $daysOfWeek;
            $aprovadas = $pendentes = $revisao = array_fill(0, 7, 0);
            foreach ($transactions as $t) {
                $dayIndex = Carbon::parse($t->created_at)->dayOfWeekIso - 1;
                switch ($t->status) {
                    case 'pago': $aprovadas[$dayIndex] += (float)$t->amount; break;
                    case 'revisao': $revisao[$dayIndex] += (float)$t->amount; break;
                    default: $pendentes[$dayIndex] += (float)$t->amount; break;
                }
            }
            break;

        case 'mes':
            $startDate = Carbon::now()->startOfMonth()->startOfDay();
            $endDate= Carbon::now()->endOfMonth()->endOfDay();
            $transactions = auth()->user()
                ->transactions_in()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $daysInMonth = Carbon::now()->daysInMonth;
            $labels = range(1, $daysInMonth);
            $aprovadas = $pendentes = $revisao = array_fill(0, $daysInMonth, 0);
            foreach ($transactions as $t) {
                $dayIndex = Carbon::parse($t->created_at)->day - 1;
                switch ($t->status) {
                    case 'pago': $aprovadas[$dayIndex] += (float)$t->amount; break;
                    case 'revisao': $revisao[$dayIndex] += (float)$t->amount; break;
                    default: $pendentes[$dayIndex] += (float)$t->amount; break;
                }
            }
            break;

        default: // tudo
            $transactions = auth()->user()->transactions_in()->get();
            $labels = $daysOfWeek; // Usa dias da semana, mas os valores são acumulados de todas as transações, o que pode não ser ideal para 'tudo'
            $aprovadas = $pendentes = $revisao = array_fill(0, 7, 0);
            foreach ($transactions as $t) {
                $dayIndex = Carbon::parse($t->created_at)->dayOfWeekIso - 1;
                switch ($t->status) {
                    case 'pago': $aprovadas[$dayIndex] += (float)$t->amount; break;
                    case 'revisao': $revisao[$dayIndex] += (float)$t->amount; break;
                    default: $pendentes[$dayIndex] += (float)$t->amount; break;
                }
            }
            break;
    }
    @endphp

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [
                { name: "Aprovadas", data: @json(array_map('floatval', $aprovadas)) },
                { name: "Pendentes", data: @json(array_map('floatval', $pendentes)) },
                { name: "A liberar", data: @json(array_map('floatval', $revisao)) }
            ],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            xaxis: {
                categories: @json($labels ?? $daysOfWeek),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: '13px' } }
            },
            grid: { yaxis: { lines: { show: false } } },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadius: 5,
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val > 0 ? "R$ " + val.toLocaleString("pt-BR") : '';
                },
                offsetY: -20,
                style: { fontSize: '12px', colors: ["#304758"] }
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                        return "R$ " + val.toLocaleString("pt-BR");
                    }
                }
            },
            tooltip: {
                y: { formatter: val => "R$ " + val.toLocaleString("pt-BR") }
            },
            colors: [
                "var(--gateway-primary-color)",
                "#fa8c05",
                "#a3a2a2"
            ],
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                itemMargin: { horizontal: 10 },
                markers: { width: 10, height: 10, radius: 4 }
            }
        };

        new ApexCharts(document.querySelector("#apex-receita"), options).render();
    });
    </script>


    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const formFilter = document.getElementById('form-filter');
        const customInput = document.getElementById('custom-period-input');
        const selectPeriodo = document.querySelector('select[name="periodo"]');
        
        // Função para mostrar/esconder o input de data e submeter o formulário
        function handlePeriodChange(selectedValue) {
            if (selectedValue === 'personalizado') {
                customInput.style.display = 'block';
                
                if ($(customInput).data('daterangepicker')) {
                    // Inicializa o picker se ele ainda não tiver um valor para o caso de estar no 'personalizado' no load
                    if (!customInput.value) {
                         $(customInput).data('daterangepicker').show();
                    } else {
                        // Se já tiver valor, apenas mantém visível
                    }
                }
            } else {
                // Para outros valores, esconde o input e submete
                customInput.style.display = 'none';
                formFilter.submit();
            }
        }

        $(document).ready(function() {
            
            // 2. INICIALIZAÇÃO do Daterangepicker
            $(customInput).daterangepicker({
                autoUpdateInput: false, 
                // Se o campo já tem um valor, o picker deve carregar com ele
                @if(request('periodo_personalizado') && $periodo == 'personalizado')
                    startDate: moment("{{ explode(' - ', request('periodo_personalizado'))[0] }}", "DD/MM/YYYY"),
                    endDate: moment("{{ explode(' - ', request('periodo_personalizado'))[1] }}", "DD/MM/YYYY"),
                @endif
                locale: {
                    format: 'DD/MM/YYYY', 
                    cancelLabel: 'Limpar',
                    applyLabel: 'Aplicar',
                    customRangeLabel: 'Customizar',
                    separator: ' - ',
                    daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
                    monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                }
            });
            
            // 3. EVENTO DE SELEÇÃO NO DATARANGEPICKER
            $(customInput).on('apply.daterangepicker', function(ev, picker) {
                // Ao aplicar a data, preenche o input com as datas selecionadas
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                
                // Submete o formulário APÓS a seleção da data
                formFilter.submit();
            });

            // 4. EVENTO PARA LIMPAR DATAS (Aparece com o botão "Limpar" no picker)
            $(customInput).on('cancel.daterangepicker', function(ev, picker) {
                // Se o usuário limpar, limpa o input, muda o select para 'tudo' e submete
                $(this).val('');
                selectPeriodo.value = 'tudo';
                formFilter.submit();
            });
            
            // 5. Verifica o estado inicial (para manter o input de data visível se estiver em 'personalizado' no load)
            if (selectPeriodo.value === 'personalizado') {
                customInput.style.display = 'block';
                
                // Preenche o input se o valor estiver no URL e o daterangepicker estiver carregado
                @if(request('periodo_personalizado') && $periodo == 'personalizado')
                    customInput.value = "{{ request('periodo_personalizado') }}";
                @endif

            }
        });
    </script>

@endsection