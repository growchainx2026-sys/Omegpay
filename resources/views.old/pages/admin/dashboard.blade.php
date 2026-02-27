@php
    use Carbon\Carbon;
    $setting = \App\Helpers\Helper::settings();

    function hexToRgba12($hex, $opacity = 1.0)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            // Converte notação curta (#abc → #aabbcc)
            $r = hexdec(str_repeat($hex[0], 2));
            $g = hexdec(str_repeat($hex[1], 2));
            $b = hexdec(str_repeat($hex[2], 2));
        } elseif (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            return 'rgba(0,0,0,' . $opacity . ')'; // fallback
        }

        return "rgba($r, $g, $b, $opacity)";
    }

    function rgbaToHex($rgba)
    {
        // Remove 'rgba(' ou 'rgb(' e ')'
        $rgba = str_replace(['rgba(', 'rgb(', ')', ' '], '', $rgba);

        // Quebra em componentes
        $parts = explode(',', $rgba);

        if (count($parts) < 3) {
            return null; // Inválido
        }

        $r = isset($parts[0]) ? intval($parts[0]) : 0;
        $g = isset($parts[1]) ? intval($parts[1]) : 0;
        $b = isset($parts[2]) ? intval($parts[2]) : 0;

        // Converte para hex e garante dois dígitos com str_pad
        return sprintf('#%02X%02X%02X', $r, $g, $b);
    }

    $opacity = rgbaToHex(hexToRgba12($setting->software_color, 0.5));
@endphp
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Dashboard
        </h1>
        @php
            $periodo = request()->input('periodo', 'dia');
            $start = request()->input('start', now()->startOfDay()->format('Y-m-d'));
            $end = request()->input('end', now()->endOfDay()->format('Y-m-d'));
        @endphp

        <form id="form-filter" method="GET">
            <input type="hidden" name="start" id="start" value="{{ request('start') }}">
            <input type="hidden" name="end" id="end" value="{{ request('end') }}">
            <input type="hidden" name="periodo" id="periodo" value="{{ $periodo }}">

            <input type="text" id="daterange" class="form-select"
                style="border-color:transparent;color:white;border-radius:10px;background:var(--gateway-sidebar-color)!important"
                readonly value="{{ $periodo === 'custom' ? 'Personalizado' : ucfirst($periodo) }}">
        </form>
    </div>
    <div class="row gs-3">
        <div class="col-md-6 col-xl-4 mb-3 ">
            <div class="card card-dash p-4 w-100" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <p class="mb-1 title-">Saldo em carteira ({{ $periodo }})</p>
                <div class="fs-3 text-bold text-gateway">R$
                    {{ number_format($saldos, 2, ',', '.') }}</div>
                <small class="d-block mb-1">Receita:
                    R$
                    {{ number_format((clone $depositos)->whereIn('status', ['pago','revisao'])->sum('taxa_cash_in'), 2, ',', '.') }}</small>
                <small class="d-block">Usuários: {{ $usersCadastrados ?? 0 }}</small>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3 ">
            <div class="card card-dash p-4 w-100" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <p class="mb-1 title-">Total de Vendas ({{ $periodo }})</p>
                <div class="fs-3 text-bold text-gateway">R$
                    {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}</div>
                <small class="d-block mb-1">Número de vendas:
                    {{ (clone $depositos)->whereIn('status', ['pago', 'revisao'])->count() }}</small>
                <small class="d-block">Ticket Médio: R$
                    {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->avg('amount') ?? 0, 2, ',', '.') }}</small>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <p class="mb-1 title-">Total de Retiradas ({{ $periodo }})</p>
                <div class="fs-3 text-bold text-gateway">R$
                    {{ number_format((clone $saques)->where('status', 'pago')->sum('amount'), 2, ',', '.') }}</div>
                <small class="d-block mb-1">Quantidade:
                    {{ (clone $saques)->where('status', 'pago')->count() }}</small>
                <small class="d-block">Pendentes: R$ {{ number_format($saquesPendentes, 2, ',', '.') }}</small>
            </div>
        </div>


        <!-- Formas de Pagamento -->
        <div class="col-md-6 col-xl-4 mb-3 ">
            <div class="card card-dash px-4 pt-4">
                <div class="d-flex flex-column mb-0">
                    <p class="mb-3">Formas de Pagamento ({{ $periodo }})</p>
                    <table class="table table-sm mb-0" style="margin-top: -0.5rem;">
                        <tbody>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Cartão</td>
                                <td class="text-end">
                                    {{ (clone $depositos)->where('method', 'card')->whereIn('status', ['pago', 'revisao'])->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $depositos)->where('method', 'card')->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Pix</td>
                                <td class="text-end">
                                    {{ (clone $depositos)->where('method', 'pix')->where('status', 'pago')->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $depositos)->where('method', 'pix')->where('status', 'pago')->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-start" style="padding-left: 0;">Boleto</td>
                                <td class="text-end">
                                    {{ (clone $depositos)->where('method', 'billet')->whereIn('status', ['pago', 'revisao'])->count() }}
                                </td>
                                <td class="text-end fw-bold">R$
                                    {{ number_format((clone $depositos)->where('method', 'billet')->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Reembolsos -->
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-4">
                <p class="mb-1">Reembolsos ({{ $periodo }})</p>
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

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-2 pt-4">
                <p class="mb-2 ">&nbsp;&nbsp;&nbsp;&nbsp;Taxa de Aprovação ({{ $periodo }})</p>
                <div class="row text-center mt-n5 pt-1 mb-0">
                    <div class="col d-flex flex-column align-items-center">
                        <div id="apex-cartao"></div>
                        <small class="mt-n2">Cartão</small>
                    </div>
                    <div class="col d-flex flex-column align-items-center">
                        <div id="apex-pix"></div>
                        <small class="mt-n2">Pix</small>
                    </div>
                    <div class="col d-flex flex-column align-items-center">
                        <div id="apex-boleto"></div>
                        <small class="mt-n2">Boleto</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card flex-fill w-100" style="height: 375px">
                <div class="card-header">
                    <h5 class="card-title mb-0">Depósitos da semana</h5>
                </div>
                <div class="card-body py-3">
                    <div class="chart chart-sm">
                        <canvas id="graficoDepositosSaques" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card flex-fill w-100" style="height: 375px">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-wrap-ellipsis2">Ranking melhores vendedores</h5>
                </div>
                <div class="card-body pb-3 mt-0 pt-0 w-100">
                    @foreach ($topUsuariosDeposito as $key => $top)
                        <div class="d-flex align-items-center mt-1 px-3"
                            style="border-left: 3px solid {{ $key == 0 ? 'gold' : ($key == 1 ? 'gray' : ($key == 2 ? '#82572c' : 'var(--gateway-background-color)')) }}; height: auto; line-height: 1.2;">
                            @if ($key < 3)
                                <i class="fa-solid fa-trophy"
                                    style="color:{{ $key == 0 ? 'gold' : ($key == 1 ? 'gray' : '#82572c') }}!important"></i>&nbsp;
                            @endif
                            <div class="flex-grow-1 pe-2">
                                <p class="mb-0 fw-bold text-wrap-ellipsis">
                                    {{ $top->name }}
                                </p>
                            </div>

                            <span class="badge ms-2 text-start"
                                style="color: {{ $key == 0 ? 'gold' : ($key == 1 ? 'gray' : ($key == 2 ? '#82572c' : 'var(--gateway-text-color)')) }}!important;border: 1px solid {{ $key == 0 ? 'gold' : ($key == 1 ? 'gray' : ($key == 2 ? '#82572c' : 'var(--gateway-text-color)')) }}; border-radius: 10px;">
                                R$ {{ number_format($top->total_depositado, 2, ',', '.') }}
                            </span>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card flex-fill w-100" style="height: 375px">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ultimas transações</h5>
                </div>
                <div class="card-body pt-0 mt-0 pb-3">
                    @php
                        use Illuminate\Support\Collection;

                        // Pegue os dados de entrada e saída
                        $entradas = (clone $depositos)->map(function ($item) {
                            $item->tipo = 'entrada';
                            return $item;
                        });

                        $saidas = (clone $saques)->map(function ($item) {
                            $item->tipo = 'saida';
                            return $item;
                        });

                        // Mescle, ordene por data e limite a 5
                        $transacoes = $entradas->merge($saidas)->sortByDesc('created_at')->take(6);
                    @endphp

                    @foreach ($transacoes as $key => $transacao)
                        <div class="d-flex align-items-center mt-3 px-3"
                            style="border-left: 3px solid {{ $transacao->status == 'revisao' ? 'gray' :$setting->software_color }}; height: auto; line-height: 1.2;">

                            <div class="flex-grow-1">
                                <p class="mb-0 fw-bold text-truncate"
                                    style="font-size: 16px; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $transacao->tipo === 'entrada' ? 'Entrada' : 'Saída' }}
                                </p>
                                <p class="mb-0" style="font-size: 10px;">
                                    {{ $transacao->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <span class="badge ms-2 text-center"
                                style="color:white !important;background: {{ $transacao->status == 'revisao' ? 'gray' : ( $transacao->tipo === 'entrada' ? $setting->software_color : 'orange') }}; border-radius: 10px;">
                                {{ $transacao->tipo === 'entrada' ? '+ R$' : '- R$' }}
                                {{ number_format($transacao->amount, 2, ',', '.') }}
                            @if($transacao->status == 'revisao')
                            <br>
                                <span class="text-white" >A liberar</span>
                            @endif
                            </span>
                           
                        </div> 
                    @endforeach
                </div>
            </div>

        </div>

        @php
        
            function taxaAprovacao($depositos, $method)
            {
                $total = (clone $depositos)->where('method', $method)->count();
                $paid = (clone $depositos)->where('method', $method)->whereIn('status', ['pago', 'revisao'])->count();
                return $total > 0 ? ($paid / $total) * 100 : 0;
            }

            $txCard = taxaAprovacao($todosDepositos, 'card');
            $txBillet = taxaAprovacao($todosDepositos, 'billet');
            $txPix = taxaAprovacao($todosDepositos, 'pix');
        @endphp

        <!-- Import ApexCharts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            function renderRadialChart(id, value) {
                var options = {
                    chart: {
                        height: 100,
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
                            }, // círculo central maior
                            track: {
                                background: "#e9ecef", // fundo cinza claro
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
                                    formatter: function(val) {
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

            document.addEventListener('DOMContentLoaded', function() {
                renderRadialChart("#apex-cartao", Number("{{ number_format($txCard, 2) }}"));
                renderRadialChart("#apex-pix", Number("{{ number_format($txPix, 2) }}"));
                renderRadialChart("#apex-boleto", Number("{{ number_format($txBillet, 2) }}"));
            });
        </script>

        <script>
            const ctx2 = document.getElementById('graficoDepositosSaques').getContext('2d');

            const graficoLinha = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: @json($dias), // 👈 precisa conter sempre 7 valores (um pra cada dia da semana)
                    datasets: [{
                        label: 'Depósitos',
                        data: @json($valoresDepositos),
                        backgroundColor: '{{ hexToRgba12($setting->software_color, 0.7) }}',
                        borderColor: '{{ $setting->software_color }}',
                        borderWidth: 1,
                        borderRadius: {
                            topLeft: 10,
                            topRight: 10
                        }, // 👈 arredonda só o topo
                        barPercentage: 0.5, // 👈 deixa as barras mais finas (0.1 ~ 1)
                        categoryPercentage: 0.6, // 👈 controla o espaçamento entre as barras
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: false // 👉 remove grade Y
                            }
                        },
                        x: {
                            grid: {
                                display: false // 👉 remove grade X
                            },
                            ticks: {
                                maxRotation: 0, // 👈 impede rotação
                                minRotation: 0
                            }
                        }
                    }
                }
            });
        </script>
        <script>
            var swiper = new Swiper(".mySwiper", {
                spaceBetween: 30,
                centeredSlides: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
        </script>


        <script>
            $(function() {
                let start = "{{ request('start') ?? '' }}";
                let end = "{{ request('end') ?? '' }}";
                let periodo = "{{ $periodo }}";

                // Função para atualizar o texto no input
                function setLabel(label) {
                    $('#daterange').val(label);
                }

                function cb(start, end, label) {
                    $('#start').val(start.format('YYYY-MM-DD'));
                    $('#end').val(end.format('YYYY-MM-DD'));

                    if (label) {
                        switch (label) {
                            case 'Hoje':
                                $('#periodo').val('dia');
                                break;
                            case 'Semana':
                                $('#periodo').val('semana');
                                break;
                            case 'Mês':
                                $('#periodo').val('mes'); // 👈 converte para "mes" sem acento
                                break;
                            case 'Tudo':
                                $('#periodo').val('tudo');
                                break;
                            default:
                                $('#periodo').val('custom');
                        }
                        $('#daterange').val(label);
                    } else {
                        $('#periodo').val('custom');
                        $('#daterange').val('Personalizado');
                    }

                    $('#form-filter').submit();
                }

                $('#daterange').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD/MM/YYYY',
                        applyLabel: 'Aplicar',
                        cancelLabel: 'Cancelar',
                        customRangeLabel: 'Personalizado', // 👈 Aqui muda o texto
                        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                        monthNames: [
                            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                        ],
                        firstDay: 1
                    },
                    ranges: {
                        'Hoje': [moment(), moment()],
                        'Semana': [moment().startOf('week'), moment()],
                        'Mês': [moment().startOf('month'), moment()],
                        'Tudo': [moment('2020-01-01'), moment()]
                    }
                }, cb);

                // Ao carregar a página, define o label atual
                switch (periodo) {
                    case 'dia':
                        setLabel('Hoje');
                        break;
                    case 'semana':
                        setLabel('Semana');
                        break;
                    case 'mes':
                        setLabel('Mês');
                        break;
                    case 'tudo':
                        setLabel('Tudo');
                        break;
                    case 'custom':
                        setLabel('Personalizado');
                        break;
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                // Atualiza os campos hidden
                $('#start').val(picker.startDate.format('YYYY-MM-DD'));
                $('#end').val(picker.endDate.format('YYYY-MM-DD'));

                // Atualiza label
                let label = picker.chosenLabel;
                switch (label) {
                    case 'Hoje':
                        $('#periodo').val('dia');
                        break;
                    case 'Semana':
                        $('#periodo').val('semana');
                        break;
                    case 'Mês':
                        $('#periodo').val('mes');
                        break;
                    case 'Tudo':
                        $('#periodo').val('tudo');
                        break;
                    default:
                        $('#periodo').val('custom');
                        label = 'Personalizado';
                }
                $('#daterange').val(label);

                // Envia formulário
                $('#form-filter').submit();
            });
        </script>
    @endsection
