@extends('layouts.app')

@section('title', 'Depósitos')

@section('content')
    <div class="header mb-3 d-flex align-items-center justify-content-between">
        <h1 class="header-title">
            Entradas
        </h1>
        <form id="form-filter" action="{{ route('extrato.depositos') }}" method="GET">
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
                    $abandono = 0;
                    $lucro = 100;
                    break;

                case 'mes':
                    $mesAtualInicio = Carbon::now()->startOfMonth();
                    $mesAtualFim = Carbon::now()->endOfMonth();
                    $mesPassadoInicio = Carbon::now()->subMonth()->startOfMonth();
                    $mesPassadoFim = Carbon::now()->subMonth()->endOfMonth();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('amount') ?? 0;

                    if ($vendasAnterior > 0) {
                        $crescimento = (($vendasAtual - $vendasAnterior) / $vendasAnterior) * 100;
                    } elseif ($vendasAtual > 0 && $vendasAnterior == 0) {
                        $crescimento = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $crescimento = 0;
                    }

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesPassadoInicio, $mesPassadoFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$mesAtualInicio, $mesAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                case 'semana':
                    $semanaAtualInicio = Carbon::now()->startOfWeek();
                    $semanaAtualFim = Carbon::now()->endOfDay(); // até hoje
                    $semanaPassadaInicio = Carbon::now()->subWeek()->startOfWeek();
                    $semanaPassadaFim = Carbon::now()->subWeek()->endOfWeek();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
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

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100;
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaPassadaInicio, $semanaPassadaFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$semanaAtualInicio, $semanaAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                case 'dia':
                    $diaAtualInicio = Carbon::now()->startOfDay();
                    $diaAtualFim = Carbon::now()->endOfDay();
                    $diaPassadoInicio = Carbon::now()->subDay()->startOfDay();
                    $diaPassadoFim = Carbon::now()->subDay()->endOfDay();

                    $vendasAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('amount') ?? 0;

                    $vendasAtual =
                        $depositos
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

                    $abandonoAnterior =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('amount') ?? 0;

                    $abandonoAtual =
                        $depositos
                            ->where('status', 'pendente')
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('amount') ?? 0;

                    if ($abandonoAnterior > 0) {
                        $abandono = (($abandonoAtual - $abandonoAnterior) / $abandonoAnterior) * 100;
                    } elseif ($abandonoAtual > 0 && $abandonoAnterior == 0) {
                        $abandono = 100;
                    } else {
                        $abandono = 0;
                    }

                    $lucroAnterior =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaPassadoInicio, $diaPassadoFim])
                            ->sum('cash_in_liquido') ?? 0;

                    $lucroAtual =
                        $depositos
                            ->whereIn('status', ['pago', 'revisao'])
                            ->whereBetween('created_at', [$diaAtualInicio, $diaAtualFim])
                            ->sum('cash_in_liquido') ?? 0;

                    if ($lucroAnterior > 0) {
                        $lucro = (($lucroAtual - $lucroAnterior) / $lucroAnterior) * 100;
                    } elseif ($lucroAtual > 0 && $lucroAnterior == 0) {
                        $lucro = 100; // ou null / '—' se quiser indicar indefinido
                    } else {
                        $lucro = 0;
                    }
                    break;

                default:
                    $crescimento = 0;
                    $abandono = 0;
                    $lucro = 0;
                    break;
            }
        @endphp
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash  p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Aprovadas ({{ $periodo }})</span>
                    <i data-lucide="trending-up" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">R$
                    {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->sum('amount'), 2, ',', '.') }}
                    <span class="{{ $crescimento >= 0 ? 'text-success' : 'text-danger' }} fs-6"><span class="text-danger">
                            <i data-lucide="{{ $crescimento > 0 ? 'arrow-up' : ($crescimento < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $crescimento > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        </span>
                        {{ number_format($crescimento, 2) }}%
                    </span>
                </h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'card')->sum('amount'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->where('status', 'pago')->where('method', 'pix')->sum('amount'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'billet')->sum('amount'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Abandonadas</span>
                    <i data-lucide="trending-down" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">R$
                    {{ number_format((clone $depositos)->where('status', 'pendente')->sum('amount'), 2, ',', '.') }}
                    <span class="{{ $abandono >= 0 ? 'text-success' : 'text-danger' }} fs-6"><span class="text-danger">
                            <i data-lucide="{{ $abandono > 0 ? 'arrow-up' : ($abandono < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $abandono > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        </span>
                        {{ number_format($abandono, 2) }}%
                    </span>
                </h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'card')->sum('amount'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'pix')->sum('amount'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$
                        {{ number_format((clone $depositos)->where('status', 'pendente')->where('method', 'billet')->sum('amount'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Lucros</span>
                    <i data-lucide="dollar-sign" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">R$ {{number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->sum('cash_in_liquido'), 2, ',', '.')}} 
                    <span class="{{ $lucro >= 0 ? 'text-success' : 'text-danger' }} fs-6"><span class="{{ $lucro > 0 ? 'text-success' : 'text-danger' }}">
                            <i data-lucide="{{ $lucro > 0 ? 'arrow-up' : ($lucro < 0 ? 'arrow-down' : '') }}"
                                style="stroke: {{ $lucro > 0 ? 'green' : 'red' }} !important;width: 14px;"></i>
                        </span>
                    {{ number_format($lucro, 2) }}%
                    </h4>

                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'card')->sum('cash_in_liquido'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $depositos)->where('status', 'pago')->where('method', 'pix')->sum('cash_in_liquido'), 2, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ {{ number_format((clone $depositos)->whereIn('status', ['pago', 'revisao'])->where('method', 'billet')->sum('cash_in_liquido'), 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-dash p-4" style="border-left: 5px solid var(--gateway-primary-color) !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Estornos</span>
                    <i data-lucide="refresh-cw" class="text-muted" style="width:16px;height:16px;"></i>
                </div>

                <!-- Valor principal -->
                <h4 class="fw-bold mb-0 t900">R$ 0,00 <span class="text-success fs-6">0.00%</span></h4>
                <!-- Divisor -->
                <hr class="my-3">

                <!-- Lista de métodos -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="credit-card" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Cartão</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        <i data-lucide="qr-code" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Pix</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i data-lucide="banknote" class="me-2 text-muted" style="width:18px;height:18px;"></i>
                        <span>Boleto</span>
                    </div>
                    <span class="fw-semibold">R$ 0,00</span>
                </div>
            </div>
        </div>
        {{--  <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Pagos ({{ $periodo }})</h6>
                        </div>

                        <div class="col-auto">
                            <div class="text-success icone-card" style="font-size:28px"><i
                                    class="fa-solid fa-money-bill-transfer"></i></div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">R${{ number_format($pagos, '2', ',', '.') }}</h6>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>{{ $totalPagos }} </span>
                        Transações
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Pendentes ({{ $periodo }})</h6>
                        </div>

                        <div class="col-auto">
                            <div class="text-success icone-card" style="font-size:28px"><i
                                    class="fa-solid fa-money-bill-transfer"></i></div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">R$ {{ number_format($pendentes, 2, ',', '.') }}
                    </h6>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                            {{ $totalPendentes }}
                        </span>
                        Transações
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Ticket Médio ({{ $periodo }})</h6>
                        </div>

                        <div class="col-auto">
                            <div class="text-success icone-card" style="font-size:28px"><i
                                    class="fa-solid fa-money-bill-transfer"></i></div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">R$ {{ number_format($ticketMedio, 2, ',', '.') }}
                    </h6>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                            {{ $totalTicketMedio }}
                        </span>
                        Transações
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h6 class="card-title text-start">Taxas ({{ $periodo }})</h6>
                        </div>

                        <div class="col-auto">
                            <div class="text-success icone-card" style="font-size:28px"><i
                                    class="fa-solid fa-money-bill-transfer"></i></div>
                        </div>
                    </div>
                    <h6 class="text-start display-5">R$ {{ number_format($taxas, 2, ',', '.') }}
                    </h6>
                    <div class="mb-0">
                        <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i>
                            {{ $totalTaxas }}
                        </span>
                        Transações
                    </div>
                </div>
            </div>
        </div> --}}
    </div>


    <div class="card card-dash">
        <div class="card-body">
            <table class="table" id="table-admin-depositos">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente Nome</th>
                        <th>Cliente CPF</th>
                        <th>Transação ID</th>
                        <th>Status</th>
                        <th>Valor Bruto</th>
                        <th>Taxas</th>
                        <th>Reserva</th>
                        <th>Valor Liquido</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($depositos as $deposito)
                        <tr>
                            <td>{{ $deposito->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                            <td>{{ $deposito->client_name }}</td>
                            <td>{{ $deposito->client_cpf }}</td>
                            <td>
                                
                                @if ($deposito->method == 'pix')
                                    <i data-lucide="qr-code" class="me-2" style="stroke: var(--gateway-primary-color) !important;"></i>
                                @elseif($deposito->method == 'billet')
                                    <i data-lucide="barcode" class="me-2" style="stroke: var(--gateway-primary-color) !important;"></i>
                                @elseif($deposito->method == 'card')
                                    <i data-lucide="credit-card" class="me-2" style="stroke: var(--gateway-primary-color) !important;"></i>
                                @endif
                                &nbsp;{{ $deposito->idTransaction }}
                            </td>
                            <td>
                                @if ($deposito->status === 'pendente')
                                    <span class="pendente" disabled="">Pendente</span>
                                @elseif ($deposito->status === 'pago')
                                    <span class="pago" disabled="">Pago</span>
                                @elseif ($deposito->status === 'cancelado')
                                    <span class="cancelado" disabled="">Cancelado</span>
                                @elseif ($deposito->status === 'revisao')
                                    <span class="padrao" disabled="">A liberar</span>  <i data-bs-toggle="modal" data-bs-target="#info-revisao-deposito-{{ $deposito->id }}"
                                        data-lucide="info" class="me-2"
                                        style="cursor: pointer;stroke: var(--gateway-primary-color) !important;"></i>
                                @endif
                            </td>
                            <td>R$ {{ number_format($deposito->amount, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->taxa_cash_in, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->taxa_reserva, '2', ',', '.') }}</td>
                            <td>R$ {{ number_format($deposito->cash_in_liquido, '2', ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($depositos->sortByDesc('created_at') as $deposito)
        <!-- Modal -->
        <div class="modal fade" id="info-revisao-deposito-{{ $deposito->id }}" tabindex="-1"
            aria-labelledby="info-revisao-deposito-{{ $deposito->id }}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="info-revisao-deposito-{{ $deposito->id }}Label">Dados do Depósito</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $setting = \App\Models\Setting::first();

                            $tempo = $setting->card_days_to_release;
                            if ($deposito->method == 'billet') {
                                $tempo = $setting->billet_days_to_release;
                            }

                            if($deposito->method == 'card' && $deposito->dias_recebimento > 0){
                                $tempo = $deposito->dias_recebimento;
                            }

                            $pagoem = $deposito->created_at;
                            $dataliberacao = \Carbon\Carbon::parse($pagoem)->addDays($tempo);
                            $diasrestantes = (int) \Carbon\Carbon::now()->diffInDays($dataliberacao, false);
                            $dataliberacaoFormatada = $dataliberacao->locale('pt_BR')->translatedFormat('d/m/Y');
                        @endphp

                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td>Valor bruto da venda:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->amount, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Valor líquido a receber:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->cash_in_liquido, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Taxas aplicadas:</td>
                                    <td class="text-end">R$ {{ number_format($deposito->taxa_cash_in, 2, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Prazo de recebimento:</td>
                                    <td class="text-end">{{ $tempo }} dias</td>
                                </tr>
                                <tr>
                                    <td>Dias restantes:</td>
                                    <td class="text-end">{{ (int) $diasrestantes }} dias</td>
                                </tr>
                                <tr>
                                    <td>Data de liberação automática:</td>
                                    <td class="text-end">{{ $dataliberacaoFormatada }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var table = $("#table-admin-depositos").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    search: ''
                }
            });

            table.on('draw', function() {
                $('#table-admin-depositos tbody tr').each(function() {
                    $(this).find('td').css('border-bottom', 'none');
                });
            });

            // Garante que o evento draw também seja executado na primeira renderização
            table.draw();
        });
    </script>
@endsection
