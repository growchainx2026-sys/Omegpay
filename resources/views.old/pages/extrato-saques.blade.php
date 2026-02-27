
@extends('layouts.app')

@section('title', 'Saques')

@section('content')
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Saídas
        </h1>
        <form id="form-filter" action="{{ route('extrato.saques') }}" method="GET">
            <select class="form-select" name="periodo" onchange="document.getElementById('form-filter').submit()"
                style="border-color:transparent;color:white;border-radius:10px;background:var(--gateway-sidebar-color)!important">
                @php $periodo = request()->input('periodo', 'hoje'); @endphp
                <option value="hoje" {{ $periodo == 'hoje' ? 'selected' : '' }}>Hoje</option>
                <option value="semana" {{ $periodo == 'semana' ? 'selected' : '' }}>Semana</option>
                <option value="mes" {{ $periodo == 'mes' ? 'selected' : '' }}>Mês</option>
                <option value="todos" {{ $periodo == 'todos' ? 'selected' : '' }}>Todos</option>
            </select>
        </form>
    </div>
    <div class="row">
@php
    use Carbon\Carbon;

    switch ($periodo) {
        case 'tudo':
            $crescimento = 100;
            $perpendentes = 0;
            $percancelados = 0;
            break;

        case 'mes':
            $mesAtualInicio = Carbon::now()->startOfMonth();
            $mesAtualFim = Carbon::now()->endOfMonth();
            $mesPassadoInicio = Carbon::now()->subMonth()->startOfMonth();
            $mesPassadoFim = Carbon::now()->subMonth()->endOfMonth();

            $vendasAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
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
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100;
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
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
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $vendasAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pago')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($vendasAnterior > 0) {
                $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
            } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                $crescimento = 100;
            } else {
                $crescimento = 0;
            }

            $pendentesAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $pendentesAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'pendente')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($pendentesAnterior > 0) {
                $perpendentes = (($pendentesAtual - $pendentesAnterior) / $pendentesAnterior) * 100;
            } elseif ($pendentesAtual > 0 && $pendentesAnterior == 0) {
                $perpendentes = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $perpendentes = 0;
            }

            $canceladosAnterior =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                    ->sum('amount') ?? 0;

            $canceladosAtual =
                auth()
                    ->user()
                    ->transactions_out()
                    ->where('status', 'cancelado')
                    ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                    ->sum('amount') ?? 0;

            if ($canceladosAnterior > 0) {
                $percancelados = (($canceladosAtual - $canceladosAnterior) / $canceladosAnterior) * 100;
            } elseif ($canceladosAtual > 0 && $canceladosAnterior == 0) {
                $percancelados = 100; // ou null / '—' se quiser indicar indefinido
            } else {
                $percancelados = 0;
            }
            break;

        default:
            $crescimento = 0;
            $perpendentes = 0;
            $percancelados = 0;
            break;
    }

   
@endphp
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Pagos ({{ $periodo }})</span>
                    <i data-lucide="circle-check-big" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">
                    R${{ number_format((clone $saques)->where('status', 'pago')->sum('amount'), '2', ',', '.') }} <span
                        class="text-success fs-6"><span class="text-success"><i
                                data-lucide="{{ $crescimento ?? 0 > 0 ? 'arrow-up' : ($crescimento ?? 0 < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $crescimento ?? 0 >= 0 ? 'green' : 'red' }} !important;"
                                class="me-1"></i>{{ number_format($crescimento ?? 0, '2', ',', '.') }}%</span></h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma', 'api')->sum('amount'), '2', ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="monitor-check" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'pago')->where('plataforma', 'web')->sum('amount'), '2', ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Pendentes ({{ $periodo }})</span>
                    <i data-lucide="clock-alert" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">
                    R${{ number_format((clone $saques)->where('status', 'pendente')->sum('amount'), '2', ',', '.') }} <span
                        class="text-success fs-6"><span class="{{$perpendentes >= 0 ? 'text-success' : 'text-danger'}}"><i
                                data-lucide="{{ $perpendentes > 0 ? 'arrow-up' : ($perpendentes < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $perpendentes >= 0 ? 'green' : 'red' }} !important;"
                                class="me-1"></i>{{ number_format($perpendentes, '2', ',', '.') }}%</span></h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'api')->sum('amount'), '2', ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="monitor-check" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'pendente')->where('plataforma', 'web')->sum('amount'), '2', ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Recusados ({{ $periodo }})</span>
                    <i data-lucide="circle-x" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">
                    R${{ number_format((clone $saques)->where('status', 'cancelado')->sum('amount'), '2', ',', '.') }}
                    <span class="text-success fs-6"><span class="{{$percancelados >= 0 ? 'text-success' : 'text-danger'}}"><i
                                data-lucide="{{ $percancelados ?? 0 > 0 ? 'arrow-up' : ($percancelados ?? 0 < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $percancelados ?? 0 >= 0 ? 'green' : 'red' }} !important;"
                                class="me-1"></i>{{ number_format($percancelados ?? 0, '2', ',', '.') }}%</span>
                </h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="link" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>API</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'api')->sum('amount'), '2', ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="monitor-check" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Gateway</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $saques)->where('status', 'cancelado')->where('plataforma', 'web')->sum('amount'), '2', ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <table class="table" id="table-admin-saques">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Nome Recebedor</th>
                        <th>CPF Recebedor</th>
                        <th>Chave PIX</th>
                        <th>Transação ID</th>
                        <th>Status</th>
                        <th>Valor Liquido</th>
                        <th>Valor Bruto</th>
                        <th>Taxas</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($saques as $saque)
                        <tr>
                            <td>{{ $saque->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $saque->recebedor_name }}</td>
                            <td>{{ $saque->recebedor_cpf }}</td>
                            <td>{{ $saque->pixKey }}</td>
                            <td>{{ $saque->idTransaction }}</td>
                            <td>
                                @if ($saque->status === 'pendente')
                                    <span class="pendente" disabled="">Pendente</span>
                                @elseif ($saque->status === 'pago')
                                    <span class=" pago" disabled="">Pago</span>
                                @elseif ($saque->status === 'cancelado')
                                    <span class=" cancelado" disabled="">Cancelado</span>
                                @elseif ($saque->status === 'revisao')
                                    <span class="padrao" disabled="">Em
                                        revisão</span>
                                @endif
                            </td>
                            <td>R$ {{ number_format($saque->amount, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->cash_out_liquido, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($saque->taxa_cash_out, '2', ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = $("#table-admin-saques").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function() {
                $('#table-admin-saques tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection
